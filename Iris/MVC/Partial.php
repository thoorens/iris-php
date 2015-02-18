<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A view which manages a small part of the page. It can be reused
 * or used in a loop 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

/**
 * A view which manages a small part of the page. It can be reused
 * or used in a loop
 * 
 */
class Partial extends View {

    /**
     * Type of view
     * 
     * @var string
     */
    protected static $_ViewType = 'partial';
    private $_currentLoopKey = \NULL;

    /**
     *
     * @param type $viewScriptName 
     * @param type $data 
     * @param string $key (in case of loop, this special param is the loop key)
     */
    public function __construct($viewScriptName, $data, $key = \NULL) {
        //iris_debug($key);
        $this->_viewScriptName = $viewScriptName;
        $this->_response = \Iris\Engine\Response::GetDefaultInstance();
        $this->_transmit($data);
        // in case of a loop an index is provided
        if (!is_null($key)) {
            $this->_currentLoopKey = $key;
        }
    }

    /*
     * Returns the name of the directory where to find the script
     * corresponding to the partial
     * 
     * @return string
     */

    public function viewDirectory() {
        return "scripts/";
    }

    /**
     * The magic method is modified in this class to define the alias ITEM
     * (for indexed array Loop) and 
     * @param string $name
     * @return type 
     */
    public function __get($name) {
        switch ($name) {
            // Special markers for partials called by loop
            case 'ALLDATA':
                return $this->_properties;
            case 'ITEM':
                if (is_array($this->_properties)) {
                    return $this->_properties[$this->_currentLoopKey];
                }
                elseif(is_object($this->_properties)){
                    $currentLoopKey =$this->_currentLoopKey;
                    return $this->_properties->$currentLoopKey;
                }
                else {
                    return $this->_currentLoopKey;
                }
            case 'KEY':
                return $this->_currentLoopKey;

            // Special markers for debugging loops    
            case 'D_ALLDATA':
                iris_debug($this->_properties);
            case 'D_ITEM':
                if (count($this->_properties)) {
                    iris_debug($this->_properties[$this->_currentLoopKey]);
                }
                else {
                    iris_debug($this->_currentLoopKey);
                }
            case 'D_KEY':
                iris_debug($this->_currentLoopKey);

            // use of normal variables    
            default:
                return parent::__get($name);
        }
    }

    /**
     * Recuperates the data from constructor and stores them in _properties
     * 
     * @param mixed $data The data to be displayed by the view 
     */
    protected function _transmit($data) {
        // in case of a view, treats its properties
        if ($data instanceof \Iris\MVC\View) {
            $this->_properties = $data->_properties;
        }
        // otherwise, data must be an array
        elseif (is_array($data)) {
            $this->_properties = $data;
        }
        elseif(is_object($data)){
            $this->_properties = $data;
        }
        else {
            $type = static::$_ViewType;
            throw new \Iris\Exceptions\BadParameterException("A $type data must be an array or a view");
        }
    }

}
