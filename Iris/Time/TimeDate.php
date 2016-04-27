<?php

namespace Iris\Time;

use Iris\Exceptions as ie;

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
    public function __construct($date = \NULL, $timeZone = \NULL) {
        if ($date instanceof TimeDate) {
            $this->_internalDate = clone $date->_internalDate;
            if (!is_null($timeZone))
                $this->_internalDate->setTimezone($timeZone);
        }
        elseif ($date instanceof \DateTime) {
            $this->_internalDate = clone $date;
            if (!is_null($timeZone)) {
                $this->_internalDate->setTimezone($timeZone);
            }
        }
        else {
            if (is_null($timeZone)) {
                $timeZone = new \DateTimeZone(\Iris\SysConfig\Settings::$DefaultTimezone);
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

    /**
     * Returns the japanese name of today (e.g. 2015-11-14)
     * @return string
     */
    public static function Today() {
        $today = new static();
        return $today->toString();
    }

    public function __clone() {
        $this->_internalDate = clone $this->_internalDate;
    }

    /**
     * 
     * @param type $string1
     * @param type $string2
     * @param type $ampm
     */
    protected function _analyseString($string1, $string2, $ampm) {
        $this->_analyseDateString($string1);
        $this->_analyseTimeString($string2, $ampm);
    }

    /**
     * Returns true if the provided year is a leap year
     * 
     * @param int $year
     * @return boolean
     */
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
        $mode = \Iris\SysConfig\Settings::$DateMode;
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
        \Iris\SysConfig\Settings::$DateMode = $mode;
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
                    break;
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
                case 'i':
                    $string .= $this->getMinutes($char);
                    break;
                case 's':
                    $string .= $this->getSeconds($char);
                    break;
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

    /**
     * Defines the default format used by __toString and C symbol in toString functions
     * example : 2016-01-30 17:50:10
     * 
     * @return string
     */
    protected function _defaultFormat() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('Y-m-d H:i:s');
    }

    /**
     * By default return TRUE if the internal date is initialised. With FALSE parameter,
     * will throw an exception in case of invalid date.
     * 
     * @param boolean $test
     * @return boolean
     * @throws ie\TInternalException
     */
    public function isValid($test = \TRUE) {
        if (!$test and is_null($this->_internalDate)) {
            throw new ie\TInternalException('No operation possible on invalid date');
        }
        return !is_null($this->_internalDate);
    }

    /**
     * The auto string conversion (using the default format
     * @return string
     */
    public function __toString() {
        return $this->_defaultFormat();
    }

    /**
     * 
     * @param int $day
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addDay($day, $clone = \FALSE) {
        return $this->_addInterval($day, 'P%dD', $clone);
    }

    /**
     * 
     * @param int $day
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subDay($day, $clone = \FALSE) {
        return $this->addDay(-$day, $clone);
    }

    /**
     * 
     * @param int $week
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addWeek($week, $clone = \FALSE) {
        return $this->addDay(7 * $week, $clone);
    }

    /**
     * 
     * @param int $week
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subWeek($week, $clone = \FALSE) {
        return $this->addDay(-7 * $week, $clone);
    }

    /**
     * 
     * @param int $month
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addMonth($month, $clone = \FALSE) {
        return $this->_addInterval($month, 'P%dM', $clone);
    }

    /**
     * 
     * @param int $month
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subMonth($month, $clone = \FALSE) {
        return $this->addMonth(-$month, $clone);
    }

    /**
     * 
     * @param int $year
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addYear($year, $clone = \FALSE) {
        return $this->_addInterval($year, 'P%dY', $clone);
    }

    /**
     * 
     * @param int $year
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subYear($year, $clone = \FALSE) {
        return $this->addYear(-$year, $clone);
    }

    /**
     * 
     * @param int $hour
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addHour($hour, $clone = \FALSE) {
        return $this->_addInterval($hour, 'P%dh', $clone);
    }

    /**
     * 
     * @param int $minute
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addMinute($minute, $clone = \FALSE) {
        return $this->_addInterval($minute, 'P%di', $clone);
    }

    /**
     * 
     * @param int $second
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function addSecond($second, $clone = \FALSE) {
        return $this->_addInterval($second, 'P%ds', $clone);
    }

    /**
     * 
     * @param int $hour
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subHour($hour, $clone = \FALSE) {
        return -$this->addHour(-$hour, $clone);
    }

    /**
     * 
     * @param int $minute
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subMinute($minute, $clone = \FALSE) {
        return $this->addMinute(-$minute, $clone);
    }

    /**
     * 
     * @param int $second
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return type
     */
    public function subSecond($second, $clone = \FALSE) {
        return $this->addSecond(-$second, $clone);
    }

    /**
     * 
     * @param int $int
     * @param string $format
     * @param boolean $clone If true, a copy of the object is returned with a modified value
     * @return \Iris\Time\TimeDate
     */
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

    /**
     * Setter for year, month and day in internal date/time
     * 
     * @param int $year
     * @param int $month
     * @param int $day
     * @return \Iris\Time\TimeDate for fluent interface
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

    /**
     * Setter for the day  in internal date/time 
     * 
     * @param int $day
     * @return \Iris\Time\TimeDate for fluent interface
     */
    public function setDay($day) {
        return $this->setDate(\NULL, \NULL, $day);
    }

    /**
     * Setter for the month in internal date/time 
     * 
     * @param int month
     * @return \Iris\Time\TimeDate for fluent interface
     */
    public function setMonth($month) {
        return $this->setDate(\NULL, $month, \NULL);
    }

    /**
     * Setter for the year in internal date/time 
     * 
     * @param int $year
     * @return  \Iris\Time\TimeDate for fluent interface
     */
    public function setYear($year) {
        return $this->setDate($year, \NULL, \NULL);
    }

    /**
     * Setter for the hours, minutes and seconds in internal date/time 
     * 
     * @param type $hour
     * @param type $minute
     * @param type $second
     * @return \Iris\Time\TimeDate for fluent interface
     */
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

    /**
     * Setter for the hours in internal date/time
     * 
     * @param int hour
     * @return  \Iris\Time\TimeDate for fluent interface
     */
    public function setHours($hour) {
        return $this->setTime($hour, \NULL, \NULL);
    }

    /**
     * Setter for the minutes in internal date/time 
     * 
     * @param int $minute
     * @return  \Iris\Time\TimeDate for fluent interface
     */
    public function setMinutes($minute) {
        return $this->setTime(\NULL, $minute, \NULL);
    }

    /**
     * Setter for the seconds in internal date/time 
     * 
     * @param int $seconds
     * @return  \Iris\Time\TimeDate for fluent interface
     */
    public function setSeconds($second) {
        return $this->setTime(\NULL, \NULL, $second);
    }

    /**
     * Compares the current object timedate to another, using string comparison
     * 
     * @param \Iris\Time\DateTime $date
     * return int
     */
    protected function _compare(TimeDate $date) {
        $this->isValid(\FALSE);
        $date->isValid(\FALSE);
        return strcmp($this, $date);
    }

    /**
     * Verify that the current object is after another date
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function after(TimeDate $date) {
        return $this->_compare($date) > 0;
    }

    /**
     * Verify that the current object is before another date
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function before(TimeDate$date) {
        return $this->_compare($date) < 0;
    }

    /**
     * Verify that the current object is after another date
     * or equal to it
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function since(TimeDate $date) {
        return $this->_compare($date) >= 0;
    }

    /**
     * Verify that the current object is before another date
     * or equal to it
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function until(TimeDate $date) {
        return $this->_compare($date) <= 0;
    }

    /**
     * Verify that the current object is equal to another date
     * 
     * @param \Iris\Time\TimeDate $date
     * @return boolean
     */
    public function equals(TimeDate $date) {
        return $this->_compare($date) == 0;
    }

    /**
     * Gets the day part from the internal date/time
     * 
     * @return string
     */
    public function getDay($format = 'j') {
        $this->isValid(\FALSE);
        return $this->_internalDate->format($format);
    }

    /**
     * Gets the  month part from the internal date/time
     * 
     * @return string
     */
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

    /**
     * Gets the month length part from the internal date/time
     * 
     * @return string
     */
    public function getMonthLength() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('t');
    }

    /**
     * Gets the day of week part from the internal date/time
     * 
     * @return string
     */
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

    /**
     * Gets the year part from the internal date/time
     * 
     * @return string
     */
    public function getYear($char = 'Y') {
        $this->isValid(\FALSE);
        return $this->_internalDate->format($char);
    }

    /**
     * Gets the hours part from the internal date/time
     * 
     * @return string
     */
    public function getHours($format = 'G') {
        $this->isValid(\FALSE);
        return $this->_internalDate->format($format);
    }

    /**
     * Gets the minute part from the internal date/time
     * 
     * @return string
     */
    public function getMinutes() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('i');
    }

    /**
     * Gets the second part from the internal date/time
     * 
     * @return string
     */
    public function getSeconds() {
        $this->isValid(\FALSE);
        return $this->_internalDate->format('s');
    }

    /**
     * Translates the object to a string
     * 
     * @return string
     */
    public function serialize() {
        return $this->toString();
    }

    /**
     * Creates an object from the serialized string 
     * 
     * @param string $serializedstring
     */
    public function unserialize($serializedstring) {
        list($year, $month, $day) = explode('-', $serializedstring);
        $this->_internalDate->setDate($year, $month, $day);
    }

    /**
     * Gets the date time zone
     * 
     * @return \DateTimeZone
     */
    public function getTimeZone() {
        return $this->_internalDate->getTimezone();
    }

    /**
     * Get the date/time in unix format
     * 
     * @return int
     */
    public function getUnixTime() {
        date_default_timezone_set($this->_internalDate->getTimeZone()->getName());
        return mktime(
                $this->getHours(), $this->getMinutes(), $this->getSeconds(), $this->getMonth(), $this->getDay(), $this->getYear());
    }

}
