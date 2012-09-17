<?php

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
 * @version $Id: $ */

namespace Iris\Engine;

/**
 * A PathArrayh is an ArrayObject with special methods
 * 
 *
 */
class PathArray extends \ArrayObject {

    /**
     * Add an element to the beginning of the array
     * 
     * @param string $element
     */
    public function prepend($element) {
        $old = $this->getArrayCopy();
        array_unshift($old, $element);
        $this->exchangeArray($old);
    }

    /**
     * Delete an element from the end of the array
     * 
     * @return string
     */
    public function pop() {
        $old = $this->getArrayCopy();
        $last = array_pop($old);
        $this->exchangeArray($old);
        return $last;
    }

    /**
     * Insert an element before the last two ones in the array
     * 
     * @param string $element
     */
    public function insertSecondLast($element) {
        $last = $this->pop();
        $last0 = $this->pop();
        $this->append($element);
        $this->append($last0);
        $this->append($last);
    }

    /**
     * Insert an element after the first present in the array
     * 
     * @param string $element
     */
    public function insertSecond($element) {
        $old = $this->getArrayCopy();
        $first = array_shift($old);
        array_unshift($old, $element);
        array_unshift($old, $first);
        $this->exchangeArray($old);
    }

}
