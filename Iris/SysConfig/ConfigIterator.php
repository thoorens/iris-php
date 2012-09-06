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
 * A Config needs an external iterator because, it doesn't contains all
 * its values, some of them are contained in parents. The iterator
 * keep trace of all the value keys to iterate on all values
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ConfigIterator implements \Iterator {

    private $_current = 0;
    private $_size = 0;
    private $_propertyNames = array();
    private $_config;

    function __construct($config) {
        $this->_config = $config;
    }

    public function current() {
        if ($this->valid()) {
            return $this->_config->{$this->key()};
        }
    }

    public function key() {
        if ($this->valid()) {
            return $this->_propertyNames[$this->_current];
        }
    }

    public function next() {
        $this->_current++;
    }

    public function rewind() {
        $this->_current = 0;
    }

    public function valid() {
        return $this->_current < $this->_size;
    }

    public function add($propertyName) {
        $pos = array_search($propertyName, $this->_propertyNames, TRUE);
        if ($pos === FALSE) {
            $this->_propertyNames[] = $propertyName;
            $this->_size++;
        }
    }

    public function remove($propertyName) {
        $pos = array_search($propertyName, $this->_propertyNames, TRUE);
        if ($pos !== FALSE) {
            unset($this->_propertyNames[$pos]);
            $this->_size--;
            sort($this->_propertyNames);
        }
    }

}

