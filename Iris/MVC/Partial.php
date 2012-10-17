<?php

namespace Iris\MVC;

use Iris\Engine as ie,
    Iris\Exceptions as ix;

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
    public function __construct($viewScriptName, $data, $key=\NULL) {
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

    protected function _viewDirectory() {
        return "scripts/";
    }

    /**
     * The magic method is modified in this class to define the alias ITEM
     * (for indexed array Loop) and 
     * @param string $name
     * @return type 
     */
    public function __get($name) {
        if ($name == 'ALLDATA') {
            return $this->_properties;
        }
        elseif ($name == 'ITEM') {
            if (count($this->_properties)) {
                return $this->_properties[$this->_currentLoopKey];
            }
            else {
                return $this->_currentLoopKey;
            }
        }
        elseif($name == "KEY"){
            return $this->_currentLoopKey;
        }
        return parent::__get($name);
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
        else {
            $type = static::$_ViewType;
            throw new \Iris\Exceptions\BadParameterException("A $type data must be an array or a view");
        }
    }

}

