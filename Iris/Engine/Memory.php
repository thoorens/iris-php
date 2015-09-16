<?php
namespace Iris\Engine;
use \Iris\Exceptions as ie;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class offers a way to conserve values during the
 * execution of the script. It conserves the data after
 * an error or exception and plays an important role
 * in error display.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Memory{ // implements \Iris\Design\iSingleton 

    /**
     * An associative array to store the value
     * 
     * @var mixed[]
     */
    protected $_variables = array();

    /**
     * The unique instance of the class
     * 
     * @var Memory
     */
    private static $_Instance = NULL;

    /**
     * Reduce access to the constructor. Each subclass has its own instance.
     */
    protected function __construct() {
        
    }

    /**
     * Returns the unique instance
     * 
     * @return Memory 
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new Memory();
        }
        return static::$_Instance;
    }

    /**
     * Static version of the magic setter
     * 
     * @param string $name Name of the value to store
     * @param mixed $value Value to store
     */
    public static function Set($name, $value) {
        self::GetInstance()->__set($name, $value);
    }

    /**
     * Static version of the magic getter, but never fails because of
     * the default value.
     * 
     * @param string $name Name of the value to recall
     * @param mixed $default Default value if the variable does not exist
     * @return mixed  
     */
    public static function Get($name, $default=NULL) {
        $instance = self::GetInstance();
        if ($instance->__isset($name)) {
            return $instance->$name;
        }
        return $default;
    }

    /**
     * Magic setter
     * 
     * @param type $name Name of the value to recall
     * @return type 
     * @throw MemoryException
     */
    public function __get($name) {
        if ($this->__isset($name)) {
            return $this->_variables[$name];
        }
        throw new ie\MemoryException("Variable $name doesn't exist in memory");
    }

    /**
     * Magic isset
     * 
     * @param string $name Name of the value to test
     * @return boolean
     */
    public function __isset($name) {
        return isset($this->_variables[$name]);
    }

    /**
     *
     * @param type $name Name of the value to store
     * @param type $value Value to store
     */
    public function __set($name, $value) {
        $this->_variables[$name] = $value;
    }

    /**
     * Magic unsetter
     * 
     * @param type $name Name of the value to unset
     * @throw MemoryException
     */
    public function __unset($name) {
        if ($this->__isset($name)) {
            unset($this->_variables[$name]);
        }
        else {
            throw new ie\MemoryException("Variable $name doesn't exist in memory");
        }
    }

    /**
     * Takes a snapshot of M/C/A + parameters
     */
    public static function SystemTrace() {
        $response = Response::GetDefaultInstance();
        $trace["MODULE"] = $response->getModuleName();
        $trace["CONTROLLER"] = $response->getControllerName();
        $trace["ACTION"] = $response->getActionName();
        $trace["PARAMETERS"] = $response->getParameters();
        self::Set('system_trace', $trace);
    }

}

