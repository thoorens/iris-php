<?php

namespace Iris\MVC;

use \Iris\Engine as IEN;

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
 * An abstract helper is the superclass for controller helpers
 * and view helpers
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Helper{
    use \Iris\Translation\tSystemTranslatable;



    /**
     *
     * @var AbstractActionHelper; 
     */

    protected static $_Instances = array();

    /**
     *
     * @var boolean
     */
    protected static $_Singleton = FALSE;

    /**
     * This abstract function is required, but its signature may vary from
     * implementation, so we can't define it
     */
    //public abstract function help();

    /**
     * Call to the help method of an helper (this method must be overriden
     * according to the different types of helpers)
     * 
     * @param string $functionName Class name of helper
     * @param array $arguments arguments for help method
     * @param mixed $view the view/controller possibly containing the helper
     * @return mixed 
     */
    public static function HelperCall($functionName, $arguments, $caller) {
        throw new \Iris\Exceptions\NotSupportedException("Function HelperCall need to be overridden");
    }

    /**
     * The private construct may be completed in subclasses through
     * additions made in init()
     */
    protected function __construct() {
        $this->_init();
    }

    /**
     * Permits to modify the constructor behavior
     */
    protected function _init() {
        
    }

    /**
     *
     * @param string $functionName
     * @param array $arguments
     * @param string $helperType
     * @return \Iris\MVC\_Helper 
     */
    protected static function GetObject($functionName, $helperType) {
        list($prefixe, $class) = explode('_', $functionName . '_');
        if ($prefixe != $functionName) {
            $library = ucfirst($prefixe);
            $className = ucfirst($class);
        }
        else {
            $library = 'Iris';
            $className = ucfirst($functionName);
        }
        $loader = IEN\Loader::GetInstance();
        switch ($helperType) {
            case IEN\Loader::VIEW_HELPER:
                $object = "$library\\views\\helpers\\" . $className;
                break;
            case IEN\Loader::CONTROLLER_HELPER:
                $object = "$library\\controllers\\helpers\\" . $className;
                break;

            default:
                break;
        }
        $loader->loadHelper($object, $helperType);
        $object = $object::GetInstance();
        return $object;
    }

    /**
     *
     * @return static 
     */
    public static function GetInstance() {
        // late binding class name
        $className = get_called_class();
        if (!static::$_Singleton) {
            // Pas singleton : nouvelle instance
            $instance = new $className();
        }
        else {
            // singleton : gets existing instance or creates one
            if (!isset(self::$_Instances[$className])) {
                self::$_Instances[$className] = new $className();
            }
            $instance = self::$_Instances[$className];
        }
        return $instance;
    }

    
}