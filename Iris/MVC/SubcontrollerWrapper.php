<?php

namespace Iris\MVC;

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
 * The SubcontrollerWrapper permits a late binding to the true subcontroller
 * in main controller dispatch. The main controller can set/reset parameters
 * for the subcontroller, change its url and manage the view independantly.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class SubcontrollerWrapper extends _Islet {

    /**
     * The controller in which the subcontroller is defined
     * 
     * @var _Controller
     */
    private $_mainController;
    
    /**
     * the mod/cont/act triplet as a string
     * 
     * @var string 
     */
    private $_modContAct = '';

    /**
     * A signature for system trace (should not be used)
     * 
     * @var string
     */
    protected static $_Type = 'WRAPPER';

    /**
     * The constructor is lighter than its ancestor, because this is a fake controller, a wrapper.
     * 
     * @param \Iris\MVC\_Controller $mainController the controller hosting the subcontroller
     * @param type $number the number associated to the subview
     */
    public function __construct(_Controller $mainController, $number) {
        $this->_mainController = $mainController;
        $mainController->subcontrollers[$number] = $this;
        $this->_view = new View();
    }

    /**
     * Accessor for the pseudo URL of the future subcontroller
     * 
     * @param string $modContAct
     */
    public function setModContAct($modContAct) {
        $this->_modContAct = $modContAct;
    }

    /**
     * Instanciates a subcontroller knowing its name (action and module names are
     * 'index' and 'main' by default if not otherwise defined)
     *   
     * @param string $controllerName 
     * @param type $actionName
     * @param type $module
     * @return _SubController
     */
    public function makeSubcontroller() {
        list($moduleName, $controllerName, $actionName) = explode('/', $this->_modContAct);
        if (strpos($controllerName, '/') !== FALSE) {
            list($moduleName, $controllerName) = explode('/', $controllerName);
        }
        $response = \Iris\Engine\Response::GetOtherInstance($controllerName, $actionName, $moduleName);
        /* @var $subcontroller _Subcontroller */
        $subcontroller = $response->makeController();
        $subcontroller->_view = $this->_view;
        $subcontroller->_init(); // no application nor module init
        $subcontroller->_parameters = $this->_parameters;
        return $subcontroller;
    }

}

