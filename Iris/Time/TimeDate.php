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
class TimeDate implements \Serializable, \Iris\Translation\iTranslatable {

    use \Iris\Translation\tTranslatable;

    /**
     * A sample date in Japan mode : 2013-08-28
     */

    const JAPAN = 1;
    /**
     * A sample date in USA mode : 08-28-2013
     */
    const USA = 2;
    /**
     * A sample date in Europe mode : 28-08-2013
     */
    const EUROPE = 3;

    /**
     * A 13 element array for month lengths, item 0 is unused
     * 
     * @var int[]
     */
    protected static $_MonthLength = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    /**
     * Internal representation of date
     * 
     * @var \DateTime 
     */
    protected $_internalDate = NULL;

    /**
     * 
     * @param type $date
     * @param \DateTimeZone $timeZone
     * @todo change default Time Zone
     */
    public function __construct($date = NULL, $timeZone = NULL) {
        if ($date instanceof TimeDate) {
            $this->_internalDate = clone $date->_internalDate();
            if (!is_null($timeZone))
                $this->_internalDate->setTimezone($timeZone);
        }
        elseif ($date instanceof \DateTime) {
            $this->_internalDate = clone $date;
            if (!is_null($timeZone))
                $this->_internalDate->setTimezone($timeZone);
        }
        else {
            if (is_null($timeZone)) {
                $timeZone = new \DateTimeZone(\Iris\SysConfig\Settings::GetDefaultTimeZone());
            }
            $this->_internalDate = new \DateTime(\NULL, $timeZone);
            if (!is_null($date) and is_string($date)) {
                if ($date !== '') {
                    list($comp1, $comp2, $ampm) = $this->_stringSplitter($date);
                    $this->_analyseString($comp1, $comp2, $ampm);
                }
            }
        }
    }

    public function __clone() {
        $this->_internalDate = clone $this->_internalDate;
    }

    /**
     * 
     * @param type $date
     * @param type $timeZone
     * @throws \Iris\Exceptions\NotSupportedException
     */
    protected function _analyseString($string1, $string2, $ampm) {
        $this->_analyseDateString($string1);
        $this->_analyseTimeString($string2, $ampm);
    }

    public static function LeapYear($year) {
        if ($year % 400 == 0) {
            return TRUE;
        }
        if ($year % 100 == 0) {
            return FALSE;
        }
        if ($year % 4 == 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * Explode a time/date string in three parts separated by space or _ (in URL)
     * 
     * @param string $string
     * @return array
     */
    private function _stringSplitter($string) {
        if (strpos($string, '_') === FALSE) {
            $components = explode(' ', $string . '  ');
        }
        else {
            $components = explode('_', $string . '__');
        }
        return $components;
    }

    /**
     * Scans a string to extract date parameters
     * (according to timeZone)
     * 
     * @param mixed $date
     * @param string $timeZone
     * @return \NULL
     */
    protected function _analyseDateString($date) {
        $numbers = explode('-', $date);
        if (count($numbers) == 1) {
            $numbers = explode('/', $date);
        }
        $countNumbers = count($numbers);
        // one must provide 2 or 3 numbers
        if ($countNumbers < 2 or $countNumbers > 3) {
            return;
        }
        $mode = \Iris\SysConfig\Settings::GetDateMode();
        // European dates have reverse day month order
        if ($mode == self::EUROPE or $mode == 'europe') {
            $day = $numbers[0];
            $numbers[0] = $numbers[1];
            $numbers[1] = $day;
        }
        // not today toyear !
        $toyear = $this->_internalDate->format('Y');
        // year missing, add it
        if ($countNumbers == 2) {
            array_unshift($numbers, $toyear);
        }
        // non japanese mode have to put year in front
        elseif ($mode != self::JAPAN and $mode != 'japan') {
            $year = array_pop($numbers);
            array_unshift($numbers, $year);
        }
        // for 2 digit year, -50 today +50 (e.g. 1962 - 2012 - 2061)
        // it evolves ! bad habit to use 2 digit year
        if ($numbers[0] < 100) {
            $year2 = ($toyear + 50) % 100;
            $century = ((int) ($toyear / 100)) * 100;
            if ($numbers[0] >= $year2) {
                $century -= 100;
            }
            $numbers[0] += $century;
        }
        list($newYear, $newMonth, $newDay) = $numbers;
        $months = array_values(self::$_MonthLength);
        if (self::LeapYear($newYear)) {
            $months[2] +=1;
        }
        if ($newMonth > 12 or $newMonth < 1 or $newDay < 1 or $newDay > $months[(int) $newMonth]) {
            return;
        }
        $this->_internalDate->setDate($numbers[0], $numbers[1], $numbers[2]);
    }

    protected function _analyseTimeString($string, $ampm) {
        $ampm = strtoupper($ampm);
        if ($ampm != '' and $ampm != 'AM' and $ampm != 'PM') {
            $hour = $minute = $second = 0;
        }
        else {
            $numbers = explode(':', $string);
            $countNumbers = count($numbers);
            // one must provide 2 or 3 numbers
            if ($countNumbers < 2 or $countNumbers > 3) {
                $hour = $minute = $second = 0;
            }
            else {
                $hour = (int) (($ampm == 'PM' ? 12 : 0) + $numbers[0]);
                $minute = (int) $numbers[1];
                $second = (count($numbers) == 3) ? (int) $numbers[2] : 0;
                if ($hour < 0 or $hour > 24 or $minute < 0 or $minute > 59 or $second < 0 or $second > 59) {
                    $hour = $minute = $second = 0;
                }
            }
        }
        $this->_internalDate->setTime($hour, $minute, $second);
    }

    /**
     * A way to change the date mode 
     * 
     * @param int $mode
     * @deprecated since version 1.0 (use Settings instead)
     */
    public static function SetMode($mode) {
        \Iris\SysConfig\Settings::SetDateMode($mode);
    }

    /**
     * This function is a way to format a time/date value. It may throw exception
     * in case of invalid function on subtype (e.g. minute on Date).
     * 
     * @param type $format
     * @return type 
     * @see recognized formats : http://php.net/manual/fr/function.date.php 
     */
    public function toString($format = 'C', $safe = \TRUE) {
        $string = '';
        foreach (str_split($format) as $char) {
            switch ($char) {
                case '3':
                    throw new \Iris\Exceptions\TimeDateException('Format 3 is not permited in toString');
                // Year
                case 'Y': case 'y':
                    $string .= $this->getYear($char, $safe);
                    break;
                // Month
                case 'F': case 'M':
                case 'm': case 'n':
                    $string .= $this->getMonth($char, $safe);
                    break;
                case 't':
                    $string .= $this->getMonthLength($safe);
                // day of week
                case 'D': case 'l':
                    $string .= $this->getDayOfWeek($char, $safe);
                    break;
                // day
                case 'd': case 'j':
                    $string.= $this->getDay($char, $safe);
                    break;
                // hour
                case 'G': case 'g':
                case 'H': case 'h':
                    $string .= $this->getHours($char);
                    break;
                // minute
                // second
                case '':
                // added e.g. 2012-04-09
                case 'C':
                    $string .= $this->_defaultFormat();
                    break;
                default:
                    $string .= $this->_internalDate->format($char);
                    break;
            }
        }
        return $string;
    }

    protected function _defaultFormat() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('Y-m-d H:i:s');
    }

    /**
     * By default return TRUE if the internal date is initialised. With FALSE parameter,
     * will throw an exception in case of invalid date.
     * 
     * @param type $test
     * @return type
     * @throws ie\TInternalException
     */
    public function isValid($test = TRUE) {
        if (!$test and is_null($this->_internalDate)) {
            throw new ie\TInternalException('No operation possible on invalid date');
        }
        return !is_null($this->_internalDate);
    }

    public function __toString() {
        return $this->_defaultFormat();
    }

    public function addDay($day, $clone = \FALSE) {
        return $this->_addInterval($day, 'P%dD', $clone);
    }

    public function subDay($day, $clone = \FALSE) {
        return $this->addDay(-$day, $clone);
    }

    public function addWeek($week, $clone = \FALSE) {
        return $this->addDay(7 * $week, $clone);
    }

    public function subWeek($week, $clone = \FALSE) {
        return $this->addDay(-7 * $week, $clone);
    }

    public function addMonth($month, $clone = \FALSE) {
        return $this->_addInterval($month, 'P%dM', $clone);
    }

    public function subMonth($month, $clone = \FALSE) {
        return $this->addMonth(-$month, $clone);
    }

    public function addYear($year, $clone = \FALSE) {
        return $this->_addInterval($year, 'P%dY', $clone);
    }

    public function subYear($year, $clone = \FALSE) {
        return $this->addYear(-$year, $clone);
    }

    public function addHour($hour, $clone = \FALSE) {
        return $this->_addInterval($hour, 'P%dh', $clone);
    }

    public function addMinute($minute, $clone = \FALSE) {
        return $this->_addInterval($minute, 'P%di', $clone);
    }

    public function addSecond($second, $clone = \FALSE) {
        return $this->_addInterval($second, 'P%ds', $clone);
    }

    public function subHour($hour, $clone = \FALSE) {
        return -$this->addHour(-$hour, $clone);
    }

    public function subMinute($minute, $clone = \FALSE) {
        return $this->addMinute(-$minute, $clone);
    }

    public function subSecond($second, $clone = \FALSE) {
        return $this->addSecond(-$second, $clone);
    }

    protected function _addInterval($int, $format, $clone = \FALSE) {
        $this->isValid(\FALSE);
        $duration = abs($int);
        $interval = sprintf($format, $duration);
        if ($clone) {
            $object = clone $this;
        }
        else {
            $object = $this;
        }
        if ($int < 0) {
            $object->_internalDate->sub(new \DateInterval($interval));
        }
        else {
            $object->_internalDate->add(new \DateInterval($interval));
        }
        return $object;
    }

    /* ----------------------------------------------
     * Setters
     * ----------------------------------------------
     */

    public function setDate($year, $month, $day) {
        if (\is_null($year)) {
            $year = $this->getYear();
        }
        if (\is_null($month)) {
            $month = $this->getMonth();
        }
        if (\is_null($day)) {
            $day = $this->getDay();
        }
        $this->_internalDate->setDate($year, $month, $day);
        return $this;
    }

    public function setDay($day) {
        return $this->setDate(\NULL, \NULL, $day);
    }

    public function setMonth($month) {
        return $this->setDate(\NULL, $month, \NULL);
    }

    public function setYear($year) {
        return $this->setDate($year, \NULL, \NULL);
    }

    public function setTime($hour, $minute, $second) {
        if (\is_null($hour)) {
            $hour = $this->getHours();
        }
        if (\is_null($minute)) {
            $minute = $this->getMinutes();
        }
        if (\is_null($second)) {
            $second = $this->getSeconds();
        }
        $this->_internalDate->setTime($hour, $minute, $second);
        return $this;
    }

    public function setHours($hour) {
        return $this->setTime($hour, \NULL, \NULL);
    }

    public function setMinutes($minute) {
        return $this->setTime(\NULL, $minute, \NULL);
    }

    public function setSeconds($second) {
        return $this->setTime(\NULL, \NULL, $second);
    }

    /**
     * 
     * @param \Iris\Time\DateTime $date
     * return int
     */
    protected function _compare(TimeDate $date) {
        return strcmp($this, $date);
    }

    /**
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function after(TimeDate $date) {
        return $this->_compare($date) > 0;
    }

    /**
     *
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function before(TimeDate$date) {
        return $this->_compare($date) < 0;
    }

    /**
     *
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function since(TimeDate $date) {
        return $this->_compare($date) >= 0;
    }

    /**
     *
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function until(TimeDate $date) {
        return $this->_compare($date) <= 0;
    }

    /**
     *
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function equals(TimeDate $date) {
        return $this->_compare($date) == 0;
    }

    /**
     * 
     * @return static
     */
    public function getDay($format = 'j') {
        $this->isValid(\FALSE);
        return $this->_internalDate->format($format);
    }

    public function getMonth($format = 'n') {
        $this->isValid(\FALSE);
        switch ($format) {
            case '3':
            case 'M':
            case 'F':
                $format = $format == '3' ? 'M' : $format;
                return $this->_($this->_internalDate->format($format), TRUE);
        }
        return $this->_internalDate->format($format);
    }

    public function getMonthLength() {
        return $this->_internalDate->format('t');
    }

    public function getDayOfWeek($format = 'w') {
        $this->isValid(\FALSE);
        switch ($format) {
            case '3':
            case 'D':
            case 'l':
                $format = $format == '3' ? 'D' : $format;
                return $this->_($this->_internalDate->format($format), TRUE);
        }
        return $this->_internalDate->format($format);
    }

    public function getYear() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('Y');
    }

    public function getHours($format = 'G') {
        $this->isValid(\FALSE);
        return $this->_internalDate->format($format);
    }

    public function getMinutes() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('i');
    }

    public function getSeconds() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('s');
    }

    public function serialize() {
        return $this->toString();
    }

    public function unserialize($serialized) {
        list($year, $month, $day) = explode('-', $serialized);
        $this->_internalDate->setDate($year, $month, $day);
    }

}

