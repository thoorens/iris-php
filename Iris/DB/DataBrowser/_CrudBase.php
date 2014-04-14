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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * This class provides some methods for _Crud and Login
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
abstract class _CrudBase implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    // Return codes
    const RC_DISPLAY = 0;
    const RC_END = 1;
    const RC_GOON = 2;

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
    protected $_continuationURL;

    /**
     * URL where to continue in case of irrecoverable error (not a simple
     * bad filled field)
     * 
     * @var string URL de la page d'erreur 
     */
    protected $_errorURL = "/error";

    /**
     * URL where to continue in case the action has succeeded
     * 
     * @var string
     */
    protected $_endURL = '/';
    
    /**
     * Callback function are store here
     * 
     * @var function[]
     */
    protected $_callBack = [];

    public function __construct($param = \NULL) {
        $response = \Iris\Engine\Response::GetDefaultInstance();
        $module = $response->getModuleName();
        $controller = $response->getControllerName();
        $action = $response->getActionName();
        $this->_continuationURL = "/$module/$controller/$action";
        $this->_endURL = $this->_continuationURL;
    }

    /*
     * ------------------------------------------------------------------
     * Accessors
     * ------------------------------------------------------------------
     */

    /**
     * Set the entity corresponding to the CRUD
     * 
     * @param t\Iris\DB\_Entity $entity 
     * @return _CrudBase for fluent entity
     */
    public function setEntity($entity) {
        $this->_entity = $entity;
        return $this;
    }

    /**
     * Set the form to be used by CRUD
     * 
     * @param \Iris\Forms\_Form $form 
     * @return _CrudBase for fluent entity
     */
    public function setForm($form = NULL) {
        $this->_form = $form;
        return $this;
    }

    /**
     * Get the form used by CRUD
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
     * 
     * @param type $continuationURL
     * @return \Iris\DB\DataBrowser\_CrudBase for fluent interface
     */
    public function setContinuationURL($continuationURL) {
        $this->_continuationURL = $continuationURL;
        return $this;
    }

    /**
     * 
     * @param type $errorURL
     * @return \Iris\DB\DataBrowser\_CrudBase for fluent interface
     */
    public function setErrorURL($errorURL) {
        $this->_errorURL = $errorURL;
        return $this;
    }

    /**
     * 
     * @param type $endURL
     * @return \Iris\DB\DataBrowser\_CrudBase for fluent interface
     */
    public function setEndURL($endURL) {
        $this->_endURL = $endURL;
        return $this;
    }

    /**
     * 
     * @param int $mode one of the four in CRUD
     * @param mixed $id id of current line in RUD/ or category id for create
     */
    protected function _initObjects($mode, $id = \NULL) {
        $form = $this->getForm();
        $form->setAction($this->_continuationURL . "/$id");
        $this->_formPrepare();
    }

    
    /**
     * The form has to be modified before display
     */
    protected function _formPrepare() {
        
    }

    /**
     * Tries to identificate callback definition
     * @param type $name
     * @param type $arguments
     */
    public function __call($name, $arguments) {
        if(strpos($name, 'define') === 0){
            $functionName = strtolower(substr($name, 6));
            $this->_callBack[$functionName] = $arguments[0];
        }
        else{
            throw new \Iris\Exceptions\CrudException("A non existant « $name » function has been called.");
        }
    }

}
