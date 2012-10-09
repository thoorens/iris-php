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
class Time extends TimeDate {

    protected function _defaultFormat() {
        $this->isValid(\FALSE);
        return $this->toString('H:i:s');
    }

    protected function _analyseString($string1, $ampm, $string3) {
        $this->_analyseTimeString($string1, $ampm);
    }
    
    

    public function isValid($test = TRUE) {
        if (!$test and is_null($this->_internalDate)) {
            throw new ie\InternalException('No operation possible on invalid object of class '.__CLASS__);
        }
        return !is_null($this->_internalDate);
    }

    /*
     * --------------------------------------------------------------
     * Invalid date functions 
     * --------------------------------------------------------------
     */

    public function addDay($day, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function subDay($day, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function addWeek($week, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function subWeek($week, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function addMonth($month, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function subMonth($month, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function addYear($year, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function subYear($year, $clone = \FALSE, $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function getDay($format = 'j', $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function getMonth($format = 'n', $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function getMonthLength($safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function getDayOfWeek($format = 'w', $safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    public function getYear($safe = \TRUE) {
        return $this->_dateFunction(__FUNCTION__, $safe);
    }

    /**
     * This internal function throws an exception in safe mode when
     * a data method is used with a time instance. In no safe mode, returns 0.
     * 
     * @param string $type The function used
     * @param boolean $safe Safe mode or not
     * @return int Always 0 in unsafe mode
     * @throws ie\TimeDateException
     */
    private function _dateFunction($type, $safe) {
        $this->isValid(\FALSE);
        if ($safe) {
            throw new ie\TimeDateException("Date operation ($type) is not legal on time function");
        }
        return 0;
    }

}

?>
