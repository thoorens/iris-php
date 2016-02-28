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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * An implementation of a field of bits
 */
class BitField {

    private $_internalValue = 0;

    public function __construct($internalValue = \NULL) {
        if (!is_null($internalValue)) {
            $this->_internalValue = $internalValue;
        }
    }

    public function setBit($mask) {
        $this->_internalValue |= $mask;
    }

    public function reduceBit($mask) {
        $this->_internalValue &= $mask;
    }
    
    public function hasBit($mask) {
        $testedValue = $this->_internalValue & $mask;
        return $testedValue != 0;
    }

    public function hasAllBits($mask) {
        $testedValue = $this->_internalValue & $mask;
        return $testedValue == $mask;
    }

    public function unsetBit($mask) {
        $this->_internalValue &= (-1 ^ $mask);
    }

    public function reverseBit($mask) {
        $this->_internalValue ^= $mask;
    }

    public function show() {
        return sprintf('0%b', $this->_internalValue);
    }

    public static function Test() {
        echo "Initing 4<br/>";
        $field = new \Iris\System\BitField(4);
        iris_debug($field->show(), \FALSE);
        echo "Testing 1 <br/>";
        iris_debug($field->hasBit(0b1), \FALSE);
        echo "Testing 5 <br/>";
        iris_debug($field->hasBit(0b101), \FALSE);
        echo "Testing all 5 <br/>";
        iris_debug($field->hasAllBits(0b101), \FALSE);
        echo "Testing all 4 <br/>";
        iris_debug($field->hasAllBits(0b100), \FALSE);
        echo "Setting 2<br/>";
        $field->setBit(0b10);
        iris_debug($field->show(), \FALSE);
        echo "Unsetting 5<br/>";
        $field->unsetBit(0b101);
        iris_debug($field->show(), \FALSE);
        echo "Reversing 9<br/>";
        $field->reverseBit(0b1001);
        iris_debug($field->show(), \FALSE);
        echo "Reducing to 12<br/>";
        $field->reduceBit(0b1100);
        iris_debug($field->show(), \FALSE);
        \Iris\Engine\Debug::Abort('End of test');
    }

}
