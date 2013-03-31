<?php

namespace Iris\System;

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
 * An implementation of a stack with current operation.
 */
class Stack {

    private $_internalArray = [];
    private $_underflowMessage;

    public function __construct($underflowMessage, $initialValue = \NULL) {
        $this->_underflowMessage = $underflowMessage;
        if (!is_null($initialValue)) {
            $this->_internalArray = [$initialValue];
        }
    }

    public function push($element) {
        array_unshift($this->_internalArray, $element);
    }

    public function pop() {
        if (count($this->_internalArray) == 0)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return array_shift($this->_internalArray);
    }

    public function peek($level = 0) {
        if (count($this->_internalArray) < $level + 1)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return $this->_internalArray[$level];
    }

    public function isEmpty() {
        return count($this->_internalArray) == 0;
    }

}

