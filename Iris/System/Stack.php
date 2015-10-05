<?php

namespace Iris\System;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An implementation of a stack with current operation.
 */
class Stack {

    /**
     *
     * @var type 
     */
    private $_internalArray = [];
    
    /**
     * A message to be used in an exception, in case of an underflow in the management of the stack
     * 
     * @var string 
     */
    private $_underflowMessage;

    /**
     * Creates a new stack with a underflow message and an optional initial value
     * 
     * @param type $underflowMessage
     * @param type $initialValue
     */
    public function __construct($underflowMessage, $initialValue = \NULL) {
        $this->_underflowMessage = $underflowMessage;
        if (!is_null($initialValue)) {
            $this->_internalArray = [$initialValue];
        }
    }

    /**
     * 
     * @param type $element
     */
    public function push($element) {
        array_unshift($this->_internalArray, $element);
    }

    /**
     * 
     * @return type
     * @throws \Iris\Exceptions\InternalException
     */
    public function pop() {
        if (count($this->_internalArray) == 0)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return array_shift($this->_internalArray);
    }

    /**
     * 
     * @param type $level
     * @return type
     * @throws \Iris\Exceptions\InternalException
     */
    public function peek($level = 0) {
        if (count($this->_internalArray) < $level + 1)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return $this->_internalArray[$level];
    }

    /**
     * 
     * @return type
     */
    public function isEmpty() {
        return count($this->_internalArray) == 0;
    }

}

