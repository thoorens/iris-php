<?php

namespace Iris\views\helpers;

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
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */

/**
 * A view helper is a kind of method each view can use
 */
abstract class _ViewHelper extends \Iris\MVC\_Helper {
use tViewHelperCaller;
// Par défaut les aides de vue ne sont pas des singletons
// protected static $_Singleton = FALSE;

    
    
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
     * Permits to modify the constructor behavior in an abstract subclass
     */
    protected function _subclassInit() {
    }

    /**
     * View helper call mechanism
     * 
     * @param string $functionName the view helper to instanciate or retrieve
     * @param array $arguments optional arguments
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
     */
    public function setView($view) {
        $this->_view = $view;
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
     *
     * @param string $name
     * @param type $arguments
     * @return type 
     */
    public function __call($name, $arguments) {
        throw new \Iris\Exceptions\InternalException('Obsolete syntax: use callViewHelper() instead of $this->'.$name);
        //return call_user_func_array(array($this->_view, $name), $arguments);
    }

}

?>
