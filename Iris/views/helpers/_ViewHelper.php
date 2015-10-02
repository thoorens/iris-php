<?php
namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * A view helper is a kind of method each view can use
 */
abstract class _ViewHelper extends \Iris\MVC\_Helper {
use tViewHelperCaller;
// Par défaut les aides de vue ne sont pas des singletons
// protected static $_Singleton = FALSE;

    /**
     * A terminator for using fluent methods in {(  )} context
     * (method __toString() renders it unnecessary)
     * @var string
     */
    public $__cut = '';
    
    /**
     * The private construct may be completed in subclasses through
     * additions made in init(). ViewHelper add a second level of
     * abstract subclasses having a subclassInit().
     */
    public function __construct() {
        $this->_subclassInit();
        $this->_init();
    }

    /**
     * Each view helper must have a help method but the parameter number
     * may change. There is no overloading possibility in PHP so we cannot
     * define an abstract method here
     */
    //public abstract function help($param);
    
    /**
     * Permits to modify the constructor behavior in an abstract subclass
     */
    protected function _subclassInit() {
    }

    /**
     * View helper call mechanism
     * 
     * @param string $functionName the view helper to instanciate or retrieve
     * @param mixed[] $arguments optional arguments
     * @param \Iris\View $view the current view (a new one is created if none exists)
     * @return mixed the value returned by the view helper "help" method
     */
    public static function HelperCall($functionName, $arguments=array(), $view=\NULL) {
        try {
            if (is_null($view)) {
                $view = new \Iris\MVC\View();
            }
            if (!is_array($arguments)) {
                $arguments = array($arguments);
            }
            $object = self::GetObject($functionName, \Iris\Engine\Loader::VIEW_HELPER);
            $object->setView($view);
        }
        catch (\Exception $ex) {
            $helperName = ucfirst($functionName);
            $errorMessage = $ex->getMessage();
            $file = $ex->getFile();
            $line = $ex->getLine();
            $errorMessage .= "<br/> in file <b>$file</b> line <b>$line</b>";
            $exception = new \Iris\Exceptions\HelperException("Error while executing view helper $helperName: $errorMessage ");
            throw $exception;
        }
        return call_user_func_array(array($object, 'help'), $arguments);
    }


    /**
     * Each view helper has its view (which can be a view linked to a controller or 
     * an independent one. The purpose of this variable is a easy way for calling
     * other view helpers. The inner variables of the view have uncertain values.
     * 
     * @var \Iris\View
     */
    protected $_view;

    /**
     * Setter for the linked view
     * 
     * @param \Iris\View\_ViewHelper $view 
     * @return \Iris\views\helpers\_ViewHelper for fluent interface
     */
    public function setView($view) {
        $this->_view = $view;
        return $this;
    }

    /**
     * Getter for the linked view
     * 
     * @return \Iris\View\_ViewHelper 
     */
    public function getView() {
        return $this->_view;
    }


    /**
     * Permits to cut the fluent interface in a script context
     *  
     * @return string
     */
    public function __toString() {
        return '';
    }
    
    
}


