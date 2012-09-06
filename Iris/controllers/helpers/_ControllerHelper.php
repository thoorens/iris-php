<?php

namespace Iris\controllers\helpers;

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
 *
 */

/**
 * A controller helper is a sort of method usable by all controllers.
 * The main difference with the a proper method is that it has no access
 * to non public methods and variables.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _ControllerHelper extends \Iris\MVC\_Helper {//*/
    


    // By default all controller helper are singleton
    protected static $_Singleton = TRUE;

    private $_controller;

    /**
     * A convenient way of calling a controller helper by its name
     * 
     * @param string $functionName the controller helper name (as a method name)
     * @param array $arguments the ar
     * @param \Iris\MVC\_Controller $controller : the calling controller 
     * @return type 
     */
    public static function HelperCall($functionName,$arguments, $controller) {
        $object = parent::GetObject($functionName, $arguments, \Iris\Engine\Loader::CONTROLLER_HELPER);
        $object->_controller = $controller;
        return call_user_func_array(array($object, 'help'), $arguments);
    }

    /**
     * The controller helper can access to public methods of its calling controller
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed 
     * @todo test or delete
     */
    public function __call($name, $arguments) {
        throw new \Iris\Exceptions\InternalException('This feature has not been tested anymore');
        return call_user_func_array(array($this->_controller, $name), $arguments);
    }

    /**
     * The controller helper can get the value of a public variable of its calling controller
     * 
     * @param string $name
     * @return mixed
     * @todo test or delete
     */
    public function __get($name) {
        throw new \Iris\Exceptions\InternalException('This feature has not been tested anymore');
        return $this->_controller->$name;
    }

    /**
     * The controller helper can modify the value of a public variable of its calling controller
     * 
     * @param string $name
     * @param mixed $value 
     * @todo test or delete
     */
    public function __set($name, $value) {
        throw new \Iris\Exceptions\InternalException('This feature has not been tested anymore');
        $this->_controller->$name = $value;
    }

}


