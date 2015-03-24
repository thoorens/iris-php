<?php

namespace Iris\SysConfig;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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

