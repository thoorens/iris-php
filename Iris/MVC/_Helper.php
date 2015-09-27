<?php
namespace Iris\MVC;

use \Iris\Engine as IEN;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
abstract class _Helper implements \Iris\Translation\iTranslatable {

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
     * @param mixed[] $arguments arguments for help method
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
                $completeClassName = "$library\\views\\helpers\\" . $className;
                break;
            case IEN\Loader::CONTROLLER_HELPER:
                $completeClassName = "$library\\controllers\\helpers\\" . $className;
                break;

            default:
                break;
        }
        $loader->loadHelper($completeClassName, $helperType);
        $object = $completeClassName::GetInstance();
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
