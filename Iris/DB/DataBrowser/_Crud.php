<?php
namespace Iris\DB\DataBrowser;
use Iris\DB\_Entity;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 */

/**
 * This class provides the main methods for managing entities through
 * simple forms. If necessary the form and even the entity may be 
 * self generated. For more precise tuning a series of callback can be
 * defined in a subclass.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
abstract class _Crud implements \Iris\Translation\iTranslatable{
    use \Iris\Translation\tSystemTranslatable;

    const DISPLAY = 0;
    const END = 1;
    const GOON = 2;

    const ERR_NOT_FOUND=11;
    const ERR_INTEGRITY=12;
    const ERR_SAVE = 13;
    const ERR_DUPLICATE = 14;
    const ERR_INVALID = 15;
    const ERR_INCOMPLETE = 16;

    const CREATE = 1;
    const READ = 2;
    const UPDATE = 3;
    const DELETE = 4;

    /**
     * The form used to managed the entity
     * 
     * @var \Iris\Forms\_Form 
     */
    protected $_form = NULL;

    /**
     * The name of the form variable in the view (in auto mode with DispatchAction)
     * 
     * @var string
     */
    public $_formName = 'form';

    /**
     * The type of action requested (one of CRUD)
     * 
     * @var int 
     */
    protected $_mode;

    /**
     * The entity behind the object
     * 
     * @var \Iris\DB\Entity  
     */
    protected $_entity;

    /**
     * The object currently edited
     * 
     * @var \Iris\DB\Object
     */
    protected $_data;

    /**
     * URL of the action managing Crud (will be called various times: to
     * display, validate and save data (normally taken from the router)
     * 
     * @var string
     */
    protected $_currentTreatment;

    /**
     * URL where to continue in case of irrecoverable error (not a simple
     * bad filled field)
     * 
     * @var string URL de la page d'erreur 
     */
    protected $_errorTreatment = "/ERROR";

    /**
     * URL where to continue in case the action has succeeded
     * 
     * @var string
     */
    protected $_endTreatment;

    /**
     * A correspondance between the action and the submit button text
     * (which can be localized afterwards)
     * 
     * @var string[]
     */
    protected static $_SubmitButtonText = array(
        'create' => 'Insert',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
        'upload' => 'Upload'
    );

    /**
     * This parameter may be used in some subclasses
     * 
     * @param mixed $param 
     */
    public function __construct($param=\NULL) {
        $response = \Iris\Engine\Response::GetDefaultInstance();
        $module = $response->getModuleName();
        $controller = $response->getControllerName();
        $action = $response->getActionName();
        $this->_currentTreatment = "/$module/$controller/$action";
        $this->_endTreatment = $this->_currentTreatment;
    }

    /**
     *
     * @param <array> $data
     * @return <mixed>
     */
    public function create($type=NULL, $data=null) {
        $this->autoForm('create');
        try {
            $this->_initObjects(self::CREATE, $type);
            if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
                $formData = $this->_form->getDataFromPost();
                if ($this->_form->isValid($formData)) {
                    if ($this->_postValidate(self::CREATE, $formData)) {
                        $modele = $this->_entity->createRow($formData);
                        $this->_preCreate($formData, $modele);
                        try {
                            $done = $modele->save();
                            if (!$done) {
                                return self::ERR_DUPLICATE;
                            }
                            $this->_postCreate($modele);
                            return self::END;
                        }
                        catch (_Exception $exc) {
                            return self::ERR_DUPLICATE;
                        }
                    }
                }
            }
            $this->_preDisplay($type); //by Ref
            return self::DISPLAY;
        }
        catch (_Exception $exc) {
            die("ERREUR");
        }
    }

    public function delete($idValues) {
        $this->autoForm('delete');
        $this->_find($idValues);
        $this->_initObjects(self::DELETE, $idValues);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            $object = $this->retrieveData();
            if (is_null($object)) {
                return self::ERR_NOT_FOUND;
            }
            // some special treatments do not go further
            // for instance no database
            if ($this->_preDelete($object) == self::END) {
                return self::END;
            }
            try {
                $done = $object->delete();
                if ($done) {
                    $this->_postDelete($object);
                    return self::END;
                }
                else {
                    return self::ERR_INTEGRITY;
                }
            }
            catch (_Exception $exc) {
                return self::ERR_INTEGRITY;
            }
        }
        $data = $this->retrieveData();
        if (is_null($data)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($data->asArray());


        return $this->_preDisplay($data); //by ref
    }

    public function update($idValues) {
        $this->autoForm('update');
        $this->_find($idValues);
        $this->_initObjects(self::UPDATE, $idValues);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            if ($this->_form->isValid($formData)) {
                if (!$this->_postValidate(self::UPDATE, $formData)) {
                    return self::INVALID;
                }
                $object = $this->retrieveData();
                $object->replaceData($formData);
                $this->_preUpdate($formData, $object);
                try {
                    $done = $object->save();
                    if ($done) {
                        $this->_postUpdate($object);
                        return self::END;
                    }
                    else {
                        return self::ERR_SAVE;
                    }
                }
                catch (_Exception $exc) {
                    return self::ERR_SAVE;
                }
            }
            return self::DISPLAY;
        }

        $data = $this->retrieveData();
        if (is_null($data)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($data->asArray());
        return $this->_preDisplay($data); //by ref
    }

    /**
     *
     * @param type $conditions
     * @return type 
     * @deprecated
     */
    public function read($idValues) {
        $this->autoForm('read');
        $this->_find($idValues);
        $this->_initObjects(self::READ, $idValues);
        $data = $this->retrieveData();
        if (is_null($data)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($data->asArray());
        return $this->_preDisplay($data); //by ref
    }

    public function goFirst($idValues) {
        $entity = $this->_entity;
        $id = $entity->getId(_Entity::FIRST);
        $this->read($id);
    }

    /*
     * ------------------------------------------------------------------
     * Accessors
     * ------------------------------------------------------------------
     */

    /**
     * Get the form used by CRUD
     * 
     * @return \Iris\Forms\_Form
     */
    public function getForm() {
        return $this->_form;
    }

    /**
     * Set the form to be used by CRUD
     * 
     * @param \Iris\Forms\_Form $form 
     */
    public function setForm($form=NULL) {
        $this->_form = $form;
    }

    /**
     * Forces an autoform if necessary
     */
    public function autoForm($action) {
        if (is_null($this->_form)) {
            $this->_form = new \Iris\Forms\AutoForm($this->_entity);
        }
        $this->_setSubmitMessage($action);
    }

    /**
     * Get the entity corresponding to the CRUD
     * 
     * @return \Iris\DB\_Entity
     */
    public function getEntity() {
        return $this->_entity;
    }

    /**
     * Set the entity corresponding to the CRUD
     * 
     * @param t\Iris\DB\_Entity $entity 
     */
    public function setEntity($entity) {
        $this->_entity = $entity;
    }

    /**
     * Set the three action associated to the treatment: what to do if <ul>
     * <li>an error occurs
     * <li>everything is done
     * <li>forms need to be modified again
     * </ul>
     * 
     * @param string $errorTreatment URL for error treatment
     * @param string $endTreatment URL for next task after data treatment
     * @param string $currentTreatment URL for the current page when validation fails
     */
    public function setActions($errorTreatment, $endTreatment=NULL, $currentTreatment=NULL) {
        $this->_errorTreatment = $errorTreatment;
        if ($endTreatment != NULL) {
            $this->_endTreatment = $endTreatment;
        }
        if ($currentTreatment == !NULL) {
            $this->_currentTreatment = $currentTreatment;
        }
    }

    /**
     * 
     * @param int $mode one of the four in CRUD
     * @param mixed $id id of current line in RUD/ or category id for create
     */
    protected function _initObjects($mode, $id=NULL) {

        $this->_mode = $mode;
        if ($mode == self::DELETE) {
            $this->_form->makeReadOnly();
        }
        if ($mode == self::READ) {
            $this->_form->makeReadOnly();
        }
        $this->_form->setAction($this->_currentTreatment . "/$id");
        $this->_formPrepare();
    }

    /*
     * ====================================================================
     * These methods can be overridden in subclasses to adapt treatment to 
     * particular cases
     * ====================================================================
     */

    /**
     * The form has to be modified before display
     */
    protected function _formPrepare() {
        
    }

    /**
     * The object need perhaps some modication just before
     * to be inserted in the database
     * 
     * @param mixed[] $formData data form the form
     * @param \Iris\DB\Object $object the future object to be inserted
     */
    protected function _preCreate($formData, &$object) {
        
    }

    /**
     * After the object being inserted in the database, maybe other 
     * things have to be done
     * 
     * @param \Iris\DB\Object $object 
     */
    protected function _postCreate($object) {
        
    }

    /**
     * An action to be taken before the deletion. Can stop
     * the process by returning self::END.
     * 
     * @param type $object
     * @return type What to do next (normally go on)
     */
    protected function _preDelete(&$object) {
        return self::GOON;
    }

    /**
     * An action to be taken after the deletion
     * @param type $object 
     */
    protected function _postDelete(&$object) {
        
    }

    /**
     * Two thing can be done before displaying:<ul>
     * <li>modify the data
     * <li>decide to stop by returning an error code instead of DISPLAY
     * </ul>
     *
     * @param mixed[] $data the data used by the form
     * @return int what to do next (by default DISPLAY)
     */
    protected function _preDisplay(&$data) {
        return self::DISPLAY;
    }

    // There is no _postDisplay callback

    /**
     * In auto crud, some treatment can be done (to entity or form)
     * just before the beginning of the action
     * 
     * @param \Iris\MVC\_Controller $controller the active controller
     * @param string $action the active action
     */
    protected function _preProcess(\Iris\MVC\_Controller $controller, &$action) {
        
    }

    /**
     *
     * @param \Iris\DB\Object $object 
     */
    protected function _preRead(&$object) {
        
    }

    /**
     *
     * @param \Iris\DB\Object $object 
     */
    protected function _postRead($object) {
        
    }

    /**
     * The object need perhaps some modication just before
     * to be updated in the database
     * 
     * @param mixed[] $data
     * @param \Iris\DB\Object $object 
     */
    protected function _preUpdate($formData, &$object) {
        
    }

    /**
     * After the object being updated in the database, maybe other 
     * things have to be done
     * 
     * @param \Iris\DB\Object $object 
     */
    protected function _postUpdate($object) {
        
    }

    // there is no _preValidate() method

    /**
     * The way to test some special validation condition not simply depending
     * on a single field
     * 
     * @param int $mode one of CRUD
     * @param mixed[] $formData data from the form
     * @return boolean (if FALSE go to form display)
     */
    protected function _postValidate($mode, $formData) {
        return true;
    }

    /**
     * Finishes the CRUD treatment by sending a form render to the view or 
     * reacting to the error/end code
     * 
     * @param \Iris\MVC\_Controller $controller the controller that has called the CRUD
     * @param int $code error/continuation code
     * @param string $scriptName the name of the view script in which display the form 
     */
    public function process(\Iris\MVC\_Controller $controller, $code, $scriptName) {
        //$this->_setSubmitMessage($scriptName);
        switch ($code) {
            case self::DISPLAY:
                if ($this->_mode == self::READ) {
                    $controller->__($this->_formName, $this->getForm()->display());
                }
                else {
                    $controller->__($this->_formName, $this->getForm()->render());
                }
                $controller->setViewScriptName($scriptName);
                break;
            // The treatement has come to an end
            case self::END:
                // a leading / means a complete rerouting
                if ($this->_endTreatment[0] == '/') {
                    $controller->reroute($this->_endTreatment);
                }
                // without leading /, a local action is taken
                else {
                    $controller->redirect($this->_endTreatment);
                }
                break;
            // all other codes are errors    
            default:
                $controller->redirect("$this->_errorTreatment/$code");
                break;
        }
    }

    /**
     * A masked method to read the data (they are cached for later use)
     *  
     * @param mixed $idValues
     * @return object
     */
    protected function _find($idValues) {
        $data = $this->_entity->find($idValues);
        $this->_data = $data;
        return $data;
    }

    /**
     * Reads the object data corresponding to the line in treatment
     * from the data cache.
     * 
     * @return object
     */
    public function retrieveData() {
        return $this->_data;
    }

    /**
     * The main part of the auto CRUD mechanism. It does all the job requested
     * by the actionName.
     * 
     * @param \Iris\MVC\_Controller $controller the controller which calls the CRUD
     * @param type $actionName the name of the current action (with Action suffix)
     * @param mixed[] $parameters the action parameters as received by a __call method
     * @param string $scriptName the optional name of a script used by all operation
     */
    public static function DispatchAction($controller, $actionName, $parameters, $scriptName = NULL) {
        //die('sn:'.$scriptName);
        $id = count($parameters) > 0 ? $parameters[0] : NULL;
        // the action name is for instance update_bookAction => update, bookAction
        list($action, $crudName) = explode('_', $actionName);
        //@todo ajouter les opérations de navigation
        if (strpos('create.update.delete.read', $action) === FALSE) {
            throw new \Iris\Exceptions\ControllerException("Unrecognized action");
        }
        // bookAction => \models\crud\Book
        $crudName = '\\models\\crud\\' . ucFirst(str_replace('Action', '', $crudName));
        $crud = new $crudName($controller);
        $crud->_preProcess($controller, $action);
        $next = $crud->$action($id);
        // if no unique scriptName, each action has its proper script.
        if (is_null($scriptName)) {
            $scriptName = $action;
        }
        $crud->process($controller, $next, $scriptName);
    }

    /**
     * Place the localized action name in the button submit
     * 
     * @param type $action The name of the action (one of $_SubmitButtonText)
     * @param type $submitName The submit button name (by def. Submit)
     */
    protected function _setSubmitMessage($action, $submitName='Submit') {
        $submit = $this->_form->getComponent($submitName);
        if (is_null($submit)) {
            throw new \Iris\Exceptions\FormException('The form does not have a submit button');
        }
        $message = self::$_SubmitButtonText[$action];
        $submit->setValue($this->_($message, TRUE));
    }

}

