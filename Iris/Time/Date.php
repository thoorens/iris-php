<?php

namespace Iris\Time;

use Iris\Exceptions as ie;

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
 * Date provides date management and formatting
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Date extends TimeDate {

     protected function _analyseString($string1, $string2, $ampm) {
        $this->_analyseDateString($string1);
    }

    protected function _defaultFormat() {
        return $this->_internalDate->format('Y-m-d');
    }

    /*
     * --------------------------------------------------------------
     * Invalid time functions : they throws an exception except if
     * last parameter is false, in which case they return 0 and modify
     * nothing. The intention is to discourage their use
     * --------------------------------------------------------------
     */

    public function getHours($format = 'G', $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function getMinutes($safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function getSeconds($safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function addHour($hour, $clone = \FALSE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function addMinute($minute, $clone = \FALSE, $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function addSecond($second, $clone = \FALSE, $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function subHour($hour, $clone = \FALSE, $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function subMinute($minute, $clone = \FALSE, $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    public function subSecond($second, $clone = \FALSE, $safe = \TRUE) {
        return $this->_timeFunction(__FUNCTION__, $safe);
    }

    /**
     * This internal function throws an exception in safe mode when
     * a time method is used with a date instance. In no safe mode, returns 0 and do nothing.
     * 
     * @param string $type The function used
     * @param boolean $safe Safe mode or not
     * @return int Always 0 in unsafe mode
     * @throws ie\TimeDateException
     */
    private function _timeFunction($type, $safe) {
        $this->isValid(\FALSE);
        if ($safe) {
            throw new ie\TimeDateException("Time operation ($type) is not legal on date function");
        }
        return 0;
    }

}


