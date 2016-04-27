<?php
namespace Iris\Time;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Date provides date management and formatting
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Time extends TimeDate {

    /**
     * Defines the default format used by __toString and C symbol in toString functions
     * example : 17:50:10
     * 
     * @return string
     */
    protected function _defaultFormat() {
        $this->isValid(\FALSE);
        return $this->toString('H:i:s');
    }

    protected function _analyseString($string1, $ampm, $string3) {
        $this->_analyseTimeString($string1, $ampm);
    }
    
    

    public function isValid($test = TRUE) {
        if (!$test and is_null($this->_internalDate)) {
            throw new \Iris\Exceptions\InternalException('No operation possible on invalid object of class '.__CLASS__);
        }
        return !is_null($this->_internalDate);
    }

    /*
     * --------------------------------------------------------------
     * Invalid date functions 
     * --------------------------------------------------------------
     */

    public function addDay($day, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function subDay($day, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function addWeek($week, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function subWeek($week, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function addMonth($month, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function subMonth($month, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function addYear($year, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function subYear($year, $clone = \FALSE, $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function getDay($format = 'j', $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function getMonth($format = 'n', $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function getMonthLength($safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function getDayOfWeek($format = 'w', $safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    public function getYear($safe = \TRUE) {
        return $this->_invalidDateFunction(__FUNCTION__, $safe);
    }

    /**
     * This internal function throws an exception in safe mode when
     * a data method is used with a time instance. In no safe mode, returns 0.
     * 
     * @param string $type The function used
     * @param boolean $safe Safe mode or not
     * @return int Always 0 in unsafe mode
     * @throws \Iris\Exceptions\TimeDateException
     */
    private function _invalidDateFunction($type, $safe) {
        $this->isValid(\FALSE);
        if ($safe) {
            throw new \Iris\Exceptions\TimeDateException("Date operation ($type) is not legal on time function");
        }
        return 0;
    }

}


