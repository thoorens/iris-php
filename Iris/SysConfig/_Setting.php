<?php

namespace Iris\SysConfig;

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
 * An abstract _Setting has 5 methods which all throw exception and an exists methods allways returning TRUE. 
 * The concrete Setting will overriden them according to their nature
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
abstract class _Setting {

    /**
     *
     * @var mixed
     */
    protected $_value;

    protected $_fullName;
    
    public function __construct(&$repository, $fullName, $initialValue) {
        $this->_fullName = $fullName;
        $repository[strtolower($fullName)] = $this;
        $this->_value = $initialValue;
    }

    // Boolean accessors

    /**
     * Will return TRUE if the setting has been enabled
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function has() {
        throw new \Iris\Exceptions\InternalException("The requested setting is not boolean");
    }

    /**
     * Enables a boolean setting
     * @throws \Iris\Exceptions\InternalException
     */
    public function enable() {
        throw new \Iris\Exceptions\InternalException("The requested setting is not boolean");
    }

    /**
     * Disables a boolean setting
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function disable() {
        throw new \Iris\Exceptions\InternalException("The requested setting is not boolean");
    }

    /**
     * Returns the value of the setting
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function get() {
        throw new \Iris\Exceptions\InternalException("The requested setting is boolean");
    }

    /**
     * Sets the new value of a setting
     * 
     * @param mixed $value
     * @throws \Iris\Exceptions\InternalException
     */
    public function set($value) {
        throw new \Iris\Exceptions\InternalException("The requested setting is boolean");
    }
    
    /**
     * The object necessarily exists
     * 
     * @return boolean
     */
    public function exists(){
        return \TRUE;
    }
    
    /**
     * Returns the name + type + value of a setting (for debugging purpose)
     * 
     * @param string $name
     * @return string
     */
    public function debug() {
        $html[] = $this->_fullName;
        $html[] = $this->_showType();
        $html[] = $this->_showValue();
        return "<td>" . implode("</td>\n<td>", $html) . "</td>\n";
    }

    /**
     * Will return the setting type according to the Setting class
     */
    protected abstract function _showType();
    
    protected function _showValue(){
        return $this->_value;
    }
    
}

