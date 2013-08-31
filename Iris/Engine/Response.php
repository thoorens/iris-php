<?php

namespace Iris\Engine;

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
 * Offers a context for a controller: with information from the
 * router it creates it and provides usefull names and values. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Response {

    /**
     *
     * @var boolean
     */
    private $_internal;

    /**
     *
     * @var string/array
     */
    private $_moduleName;

    /**
     *
     * @var string
     */
    private $_controllerName;

    /**
     *
     * @var string
     */
    private $_actionName;

    /**
     *
     * @var array
     */
    private $_parameters;

    /**
     * The controller maked by makeController
     * 
     * @var _BaseController
     */
    public $makedController = NULL;

    /**
     * Not a singleton: a main instance (which can be changed)
     * and each subcontroller has another one
     * 
     * @var Response; 
     */
    private static $_DefaultInstance = NULL;
    
    
    private static $_Counter = 0;

    /**
     *
     * @param string $controller Controller name
     * @param string $action Action name (default 'index')
     * @param string $module Module name (default 'main')
     * @param  $parameters 
     * @param type $internal 
     */
    public function __construct($controller, $action='index', $module='main', $parameters = array(), $internal=FALSE) {
        if(++ self::$_Counter == 1){
            self::$_DefaultInstance = $this;
        }
        $this->_moduleName = $module;
        $this->_controllerName = $controller;
        $this->_actionName = $action;
        $this->_internal = $internal;
        $this->_parameters = $parameters;
    }

    public function isInternal() {
        return $this->_internal;
    }

    /**
     * Accessor to module name
     * 
     * @return string
     */
    public function getModuleName() {
        return $this->_moduleName;
    }

    /**
     * Access to controller name
     * 
     * @return string
     */
    public function getControllerName() {
        return $this->_controllerName;
    }

    /**
     * Access to action name
     * 
     * @return string
     */
    public function getActionName() {
        return $this->_actionName;
    }

    public function setAction($action) {
        $this->_actionName = $action;
    }

    /**
     * The parameters in the URL (after action)
     * They can be used more simplily through arguments
     * by an action.
     * 
     * @return array(string) 
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /**
     * Replaces the parameters of the action
     * (e.g. in case of redirect)
     * 
     * @param array(string) $actionPara 
     */
    public function setParameters($actionPara) {
        $this->_parameters = $actionPara;
    }

    /**
     * Creates a controller and initialise its action name
     * from the URL 
     * @return _Controller
     * @throw \Iris\Exceptions\ResponseException (indirectly)
     */
    public function makeController() {
        if (is_array($this->_moduleName)) {
            $module1 = $this->_moduleName[0];
            $module2 = $this->_moduleName[1];
        }
        else {
            $module1 = $this->_moduleName;
            $module2 = NULL;
        }

        list($controllerClass, $actionName) = $this->_getRoute($module1);
        $loader = Loader::GetInstance();
        if ($loader->loadClass($controllerClass, FALSE)) {
            $this->_moduleName = $module1;
        }
        elseif (is_null($module2)) {
            $this->_noControllerFound($controllerClass);
        }
        else {
            list($controllerClass, $actionName) = $this->_getRoute($module2);
            //$controllerClass = "modules\\" . $controllerClass;
            if ($loader->loadClass($controllerClass, FALSE)) {
                $this->_moduleName = $module2;
            }
            else {
                $this->_noControllerFound($controllerClass);
            }
        }
        $controller = new $controllerClass($this, $actionName);
        $this->makedController = $controller;
        return $controller;
    }

    private function _noControllerFound($controllerClass) {
        $exception = new \Iris\Exceptions\ResponseException("$controllerClass : this controler was not found");
        $exception->setComment('controllerError');
        throw $exception;
    }

    /**
     * Makes the path for the controller using a module name 
     * 
     * @param string $moduleName
     * @return array(string,string) controller and action names 
     */
    private function _getRoute($moduleName) {
        if ($this->_internal) {
            $route[0] = "IrisInternal\\$moduleName\\controllers\\" . $this->getControllerName();
        }
        elseif($moduleName[0]=='#'){
            $internalLibrary = substr($moduleName, 1);
            $route[0] = "$internalLibrary\\controllers\\" . $this->getControllerName();
        }
        else {
            $route[0] = "modules\\$moduleName\\controllers\\" . $this->getControllerName();
        }
        $route[1] = $this->getActionName();
        return $route;
    }

    public function __toString() {
         $url[] = $this->isInternal() ? '!' : '';
        $url[] = $this->getModuleName();
        $url[] = $this->getControllerName();
        $url[] = $this->getActionName();
        return implode('/',$url);
    }
    
    /**
     * Returns the main Response object
     * 
     * @return Response 
     * @todo in islet or subcontroller IT MUST BE DISTINCT
     */
    public static function GetDefaultInstance() {
        return self::$_DefaultInstance;
    }

    /**
     * Creates another Response object (to manage a subcontroller or an islet)
     * If the module is not explicitely named, two are provided: the module of
     * the main view and the corresponding main (a second chance search will
     * be done in case it does not exist in same module)
     * 
     * @param string $controllerName
     * @param string $actionName
     * @return Response 
     */
    public static function GetOtherInstance($controllerName, $actionName, $moduleName = NULL) {
        // if no module, take the main/!main module 
        if (is_null($moduleName) or $moduleName=='') {
            $main = self::$_DefaultInstance;
            $internal = $main->_internal;
            if ($internal) {
                $moduleName = array($main->_moduleName, '!main');
            }
            else {
                $moduleName = array($main->_moduleName, 'main');
            }
        }
        else {
            $internal = FALSE;
            // if module name begins by '!', search an internal module
            if ($moduleName[0] == '!') {
                $moduleName = substr($moduleName, 1);
                $internal = TRUE;
            }
        }
        return new Response($controllerName, $actionName, $moduleName, array(), $internal);
    }

    public function setDefault() {
        self::$_DefaultInstance = $this;
    }

}

?>
