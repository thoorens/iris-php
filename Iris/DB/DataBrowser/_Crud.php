<?php

namespace Iris\DB\DataBrowser;

use Iris\DB\_Entity;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class provides the main methods for managing entities through
 * simple forms. If necessary the form and even the entity may be 
 * self generated. For more precise tuning a series of callback can be
 * defined in a subclass.
 * 
 *  * This class provides some common methods for <ul>
 *    <li>DB\DataBrowser\Upload 
 *    <li>Iris\Documents\ExtendedUpload
 *    <li>Users\Login
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
abstract class _Crud { //implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    // Error codes
    const ERR_NOT_FOUND = 11;
    const ERR_INTEGRITY = 12;
    const ERR_SAVE = 13;
    const ERR_DUPLICATE = 14;
    const ERR_INVALID = 15;
    const ERR_INCOMPLETE = 16;
    // Return codes
    const RC_DISPLAY = 0;
    const RC_END = 1;
    const RC_GOON = 2;
    // Function mode
    /**
     * A neutral mode: no modification will be made
     */
    const NOOPERATION_MODE = 1;

    /**
     * The type of action requested (one of CRUD)
     * 
     * @var int 
     */
    protected $_mode;

    /**
     * Create (or insert) a line
     */
    const CREATE_MODE = 2;

    /**
     * Read (or select) a line is alias for nooperation
     */
    const READ_MODE = self::NOOPERATION_MODE;

    /**
     * Update a line
     */
    const UPDATE_MODE = 3;

    /**
     * Delete a line
     */
    const DELETE_MODE = 4;

    /**
     * Default crud directory
     */
    const CRUD_DIRECTORY = '\\models\\crud\\';

    /**
     * The entity behind the object
     * 
     * @var \Iris\DB\Entity  
     */
    private $_crudEntity = \NULL;
    protected static $_EntityManager = \NULL;
    protected static $_EntityList = [];

    /**
     * The object currently edited/displayed
     * 
     * @var \Iris\DB\Object
     */
    protected $_currentObject;

    /**
     * The name of the form variable in the view (in auto mode with DispatchAction)
     * 
     * @var string
     */
    protected $_formName = 'form';

    /**
     * The return code is exposed here to permit a change in any callback
     * Use it carefully.
     * 
     * @var string
     */
    protected $_returnCode;

    /**
     * The form used to managed the entity
     * 
     * @var \Iris\Forms\_Form 
     */
    protected $_form = NULL;

    /**
     * The continuation treatment URL will be used after a first submit
     * with data no completely satisfying.
     * 
     * @var string
     */
    private $_continuationURL;

    /**
     * URL where to continue in case of irrecoverable error (not a simple
     * bad filled field)
     * 
     * @var string URL de la page d'erreur 
     */
    private $_errorURL = "/error";

    /**
     * URL where to continue in case the action has succeeded
     * 
     * @var string
     */
    private $_endURL;

    /**
     * Callback function are store here
     * 
     * @var function[]
     */
    protected $_callBack = [];

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
     * The parameter may be used in some subclasses
     * 
     * @param mixed $param 
     */
    public function __construct($param = \NULL) {
        $this->findCrudEntity();
        $url = \Iris\Engine\Response::GetDefaultInstance()->getURL();
        $this->setContinuationURL($url);
        $this->setEndURL($url);
    }

    /*
     * ------------------------------------------------------------------
     * Accessors
     * ------------------------------------------------------------------
     */

    /**
     * Set the entity corresponding to the CRUD
     * 
     * @param mixed $entity 
     * @return _Crud for fluent entity
     */
    public function setCrudEntity($entity) {
        $this->_crudEntity = $entity;
        return $this;
    }

    /**
     * Get the entity corresponding to the CRUD. If the property is not
     * defined, findCrudEntity is called
     * 
     * @return \Iris\DB\_Entity
     */
    public function getCrudEntity() {
        if (is_null($this->_crudEntity)) {
            $this->findCrudEntity();
        }
        return $this->_crudEntity;
    }

    /**
     * this method tries to evaluate the entity linked to the crud:<ul>
     * <li>if an object has been attached, keeps it
     * <li>if a NULL is found, uses the name of the crud to search an entity in the models
     * <li>if a string has been placed; uses it as the entity name in the models
     * </ul>
     */
    private function findCrudEntity() {
        if (!is_object($this->_crudEntity)) {
            if (is_null(static::$_EntityManager)) {
                static::$_EntityManager = \Iris\DB\_EntityManager::EMByNumber(); //GetInstance();
            }
            if (is_null($this->_crudEntity)) {
                $class = str_replace('\\', '/', get_called_class());
                $class = 'models\\T' . basename($class);
                $class = 'models\TUsers';
            }
            else {
                $class = 'models\\T' . ucfirst($this->_crudEntity);
            }
            $this->_crudEntity = $class::getEntity(static::$_EntityManager);
        }
    }

    /**
     * Set the form to be used by CRUD
     * 
     * @param \Iris\Forms\_Form $form 
     * @return _Crud for fluent entity
     */
    public function setForm($form = NULL) {
        $this->_form = $form;
        return $this;
    }

    /**
     * Get the form used by CRUD. If not specified, creates an autoform
     * 
     * @return \Iris\Forms\_Form
     */
    public function getForm() {
        if (is_null($this->_form)) {
            $this->_form = $this->_makeDefaultForm();
        }
        return $this->_form;
    }

    /**
     * A direct form renderer to ignore form from controller
     * 
     * @return string
     */
    public function formRender() {
        return $this->getForm()->render();
    }

    /**
     * A default form MAY be provided in certain subclasses
     * 
     * @return \Iris\Forms\_Form
     */
    protected function _makeDefaultForm() {
        throw new \Iris\Exceptions\CrudException('No default form has been defined');
    }

    /**
     * Accessor set for URL to go in case the form is not conveniently filled
     * 
     * @param string $continuationURL
     * @return \Iris\DB\DataBrowser\_Crud for fluent interface
     */
    public function setContinuationURL($continuationURL) {
        $this->_continuationURL = $continuationURL;
        return $this;
    }

    /**
     * Accessor get for URL to go in case the form is not conveniently filled
     * 
     * @return string
     */
    public function getContinuationURL() {
        return $this->_continuationURL;
    }

    /**
     * Accessor set for URL to go in case treatment error
     * 
     * @param string $errorURL
     * @return \Iris\DB\DataBrowser\_Crud for fluent interface
     */
    public function setErrorURL($errorURL) {
        $this->_errorURL = $errorURL;
        return $this;
    }

    /**
     * Accessor get for URL to go in case treatment error
     * 
     * @return string
     */
    public function getErrorURL() {
        return $this->_errorURL;
    }

    /**
     * Accessor set for the normal end of treatment URL
     * 
     * @param type $endURL
     * @return \Iris\DB\DataBrowser\_Crud for fluent interface
     */
    public function setEndURL($endURL) {
        $this->_endURL = $endURL;
        return $this;
    }

    /**
     * Accessor get for the normal end of treatment URL
     * 
     * @return string
     */
    public function getEndURL() {
        return $this->_endURL;
    }

    /**
     * Tries to identificate callback definition
     * 
     * @param type $name
     * @param type $arguments
     */
    public function __call($name, $arguments) {
        if (strpos($name, 'define') === 0) {
            $functionName = strtolower(substr($name, 6));
            $this->_callBack[$functionName] = $arguments[0];
        }
        else {
            throw new \Iris\Exceptions\CrudException("A non existant « $name » function has been called.");
        }
    }

    /**
     * Creates a new object using a form (until validation is OK)
     *  
     * @param type $type
     * @param type $data
     * @return mixed
     */
    public function create($type = \NULL, $data = \NULL) {
        $this->forceAutoForm('create');
        try {
            $this->_initObjects(self::CREATE_MODE, $type);
            if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
                $formData = $this->_form->getDataFromPost();
                if ($this->_form->isValid($formData) and $this->_postValidate(self::CREATE_MODE, $formData)) {
                    return $this->_createAndSave($formData);
                }
            }
            $this->_preDisplay($type, $data); //by Ref
            return self::RC_DISPLAY;
        }
        catch (_Exception $exc) {
            \Iris\Engine\Debug::Abort("ERREUR");
        }
    }

    /**
     * Creates a new object and saves it in the database 
     * 
     * @param string[] $formData The data from the form
     * @return mixed
     */
    protected function _createAndSave($formData) {
        $model = $this->getCrudEntity()->createRow($formData);
        if (!is_null($this->_preCreate($formData, $model))) {
            try {
                $done = $model->save();
                if (!$done) {
                    return self::ERR_DUPLICATE;
                }
                if (!is_null($this->_postCreate($model))) {
                    return self::RC_END;
                }
            }
            catch (_Exception $exc) {
                return self::ERR_DUPLICATE;
            }
        }
        return \NULL;
    }

    /**
     * Displays an object before deleting it
     * 
     * @param mixed[] $idValues The primary key to access the object
     * @return type
     */
    public function delete($idValues) {
        $object = $this->getCurrentObject($idValues);
        $this->forceAutoForm('delete');
        $this->_initObjects(self::DELETE_MODE, $idValues);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            if (is_null($object)) {
                return self::ERR_NOT_FOUND;
            }
            // some special treatments do not go further
            // for instance no database
            if ($this->_preDelete($object) == self::RC_END) {
                return self::RC_END;
            }
            try {
                $done = $object->delete();
                if ($done) {
                    $this->_postDelete($object);
                    return self::RC_END;
                }
                else {
                    return self::ERR_INTEGRITY;
                }
            }
            catch (_Exception $exc) {
                return self::ERR_INTEGRITY;
            }
        }
        //$object = $this->getCurrentObject($idValues);
        if (is_null($object)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($object->asArray());


        return $this->_preDisplay(\NULL, $object); //data by ref
    }

    public function update($idValues) {
        $this->forceAutoForm('update');
        $object = $this->getCurrentObject($idValues);
        $this->_initObjects(self::UPDATE_MODE, $idValues);
        //iris_debug($this->_currentObject);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            if ($this->_form->isValid($formData)) {
                if (!$this->_postValidate(self::UPDATE_MODE, $formData)) {
                    return self::ERR_INVALID;
                }
                $object->replaceData($formData);
                $this->_preUpdate($formData, $object);
                try {
                    $done = $object->save();
                    if ($done) {
                        $this->_postUpdate($object);
                        return self::RC_END;
                    }
                    else {
                        return self::ERR_SAVE;
                    }
                }
                catch (_Exception $exc) {
                    return self::ERR_SAVE;
                }
            }
            return self::RC_DISPLAY;
        }

        if (is_null($object)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($object->asArray());
        return $this->_preDisplay($idValues, $object); //by ref
    }

    /**
     *
     * @param type $conditions
     * @return type 
     */
    public function read($idValues) {
        $this->forceAutoForm('read');
        $this->_initObjects(self::READ_MODE, $idValues);
        $object = $this->getCurrentObject($idValues);
        if (is_null($object)) {
            return self::ERR_NOT_FOUND;
        }
        $this->_form->fill($object->asArray());
        return $this->_preDisplay($idValues, $object); //by ref
    }

    public function goFirst() {
        $entity = $this->getCrudEntity();
        $id = $entity->getId(_Entity::FIRST);
        $this->read($id);
    }

    public function goNext($idValues) {
        $entity = $this->getCrudEntity();
        $id = $entity->getId(_Entity::NEXT, $idValues);
        $this->read($id);
    }

    public function goPrevious($idValues) {
        $entity = $this->getCrudEntity();
        $id = $entity->getId(_Entity::PREVIOUS, $idValues);
        $this->read($id);
    }

    public function goLast() {
        $entity = $this->getCrudEntity();
        $id = $entity->getId(_Entity::LAST);
        $this->read($id);
    }

    /**
     * Forces an autoform if necessary
     * 
     * @param string $submitValue
     */
    public function forceAutoForm($submitValue) {
        if (is_null($this->_form)) {
            $autoForm = new \Iris\Forms\AutoForm($this->getCrudEntity());
            $this->_form = $autoForm->prepare();
        }
        $message = self::$_SubmitButtonText[$submitValue];
        $localizedMessage = $this->_($message, TRUE);
        $this->_form->setSubmitMessage($localizedMessage);
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
            case self::RC_DISPLAY:
                if ($this->_mode == self::NOOPERATION_MODE) {
                    $controller->__($this->_formName, $this->getForm()->display());
                }
                else {
                    $controller->__($this->_formName, $this->getForm()->render());
                }
                $controller->setViewScriptName($scriptName);
                break;
            // The treatement has come to an end
            case self::RC_END:
                // a leading / means a complete rerouting
                if ($this->_endURL[0] == '/') {
                    $controller->reroute($this->_endURL);
                }
                // without leading /, a local action is taken
                else {
                    $controller->reroute(\Iris\Engine\Response::GetDefaultInstance()->getURL($this->_endURL));
                }
                break;
            // all other codes are errors    
            default:
                $controller->redirect("$this->_errorURL/$code");
                break;
        }
    }

    /**
     * 
     * @param int $mode one of the four in CRUD
     * @param mixed $id id of current line in RUD/ or category id for create
     */
    protected function _initObjects($mode, $id = NULL) {

        $this->_mode = $mode;
        if ($mode == self::DELETE_MODE) {
            $this->_form->makeReadOnly();
        }
        if ($mode == self::READ_MODE) {
            $this->_form->makeReadOnly();
        }
        $form = $this->getForm();
        if (is_array($id)) {
            $id = implode('/', $id);
        }
        $form->setAction($this->getContinuationURL() . "/$id");
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
     * to be inserted in the database.
     * If a NULL is returned, the process of creation will be cancelled
     * 
     * @param mixed[] $formData data form the form
     * @param \Iris\DB\Object $object the future object to be inserted
     */
    protected function _preCreate($formData, &$object) {
        return \TRUE;
    }

    /**
     * After the object being inserted in the database, maybe other 
     * things have to be done
     * If a NULL is returned, the process of creation will be cancelled
     * 
     * @param \Iris\DB\Object $object 
     */
    protected function _postCreate($object) {
        return \TRUE;
    }

    /**
     * An action to be taken before the deletion. Can stop
     * the process by returning self::END.
     * 
     * @param type $object
     * @return type What to do next (normally go on)
     */
    protected function _preDelete(&$object) {
        return self::RC_GOON;
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
    protected function _preDisplay($param, &$data) {
        return self::RC_DISPLAY;
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
    protected function _postValidate($mode, &$formData) {
        return true;
    }

    /**
     * A masked method to read the data (they are cached for later use)
     *  
     * @param mixed $idValues
     * @return object
     */
    protected function _find($idValues) {
        $data = $this->getCrudEntity()->find($idValues, \TRUE);
        $this->_currentObject = $data;
        return $data;
    }

    /**
     * Reads the object data corresponding to the line in treatment
     * from the data cache.
     * 
     * @return object
     */
    public function getCurrentObject($idValues = \NULL) {
        $object = $this->_currentObject;
        if (is_null($object)) {
            $object = $this->getCrudEntity()->find($idValues, \TRUE);
            $this->_currentObject = $object;
            return $object;
        }
        return $object;
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
    public static function DispatchAction($controller, $actionName, $parameters, $scriptName = \NULL, $crudDirectory = self::CRUD_DIRECTORY) {
        $id = count($parameters) > 0 ? $parameters : \NULL;
        // the action name is for instance update_BookAction => update, BookAction
        list($action, $crudName) = explode('_', $actionName);
        //iris_debug($crudDirectory);
        //@todo add navigation operator
        if (strpos('create.update.delete.read', $action) === \FALSE) {
            throw new \Iris\Exceptions\ControllerException("Unrecognized action");
        }
        // BookAction => \models\crud\Book
        $crudName = $crudDirectory .  ucFirst(str_replace('Action', '', $crudName));
        //iris_debug($crudName);
        $crud = new $crudName([$controller]);

        $crud->_preProcess($controller, $action);
        $next = $crud->$action($id);
        // if no unique scriptName, each action has its proper script.
        if (is_null($scriptName)) {
            $scriptName = $action;
        }
        $crud->process($controller, $next, $scriptName);
    }

}
