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
class Date implements \Iris\Translation\iTranslatable, \Serializable {
    //PHP 5.4 use \Iris\Translation\tTranslatable;

    const JAPAN = 1;
    const USA = 2;
    const EUROPE = 3;

    /**
     * By default, works in JAPAN mode (2012-12-01)
     * 
     * @var int 
     */
    protected static $_Mode = self::JAPAN;
    /**
     * A 13 element array for month lengths, item 0 is unused
     * 
     * @var array(int)
     */
    protected static $_MonthLength = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    /**
     * Internal representation of date
     * 
     * @var \Date 
     */
    protected $_date = NULL;

    /**
     * 
     * @param type $date
     * @param \DateTimeZone $timeZone
     * @todo change default Time Zone
     */
    public function __construct($date=NULL, $timeZone=NULL) {
        if(is_null($timeZone)){
            $timeZone = new \DateTimeZone('Europe/Brussels');
        }
        if (is_null($date)) {
            $this->_date = new \DateTime(\NULL,$timeZone);
        }
        elseif (is_string($date)) {
            if ($date !== '') {
                $this->_analyseString($date,$timeZone);
            }
        }
        if ($this->isValid()) {
            $this->_date->setTimezone($timeZone);
        }
    }

    protected function _analyseString($date,$timeZone) {
        $numbers = explode('-', $date);
        if (count($numbers) == 1) {
            $numbers = explode('/', $date);
        }
        $countNumbers = count($numbers);
        // one must provide 2 or 3 numbers
        if ($countNumbers < 2 or $countNumbers > 3) {
            return; 
        }
        // European dates have reverse day month order
        if (self::$_Mode == self::EUROPE) {
            $day = $numbers[0];
            $numbers[0] = $numbers[1];
            $numbers[1] = $day;
        }
        $today = new \DateTime(\NULL,$timeZone);
        $todayYear = $today->format('Y');
        // year missing, add it
        if ($countNumbers == 2) {
            array_unshift($numbers, $todayYear);
        }
        // non japanese mode have to put year in front
        elseif (self::$_Mode != self::JAPAN) {
            $year = array_pop($numbers);
            array_unshift($numbers, $year);
        }
        // for 2 digit year, -50 today +50 (e.g. 1962 - 2012 - 2061)
        // it evolves ! bad habit to use 2 digit year
        if ($numbers[0] < 100) {
            $year2 = ($todayYear + 50) % 100;
            $century = ((int) ($todayYear / 100)) * 100;
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
        $this->_date = new \DateTime(implode('-', $numbers),$timeZone);
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

    public static function SetMode($mode) {
        self::$_Mode = $mode;
    }

    /**
     *
     * @param type $format
     * @return type 
     * @see recognized formats : http://php.net/manual/fr/function.date.php 
     */
    public function toString($format='C') {
        $string = '';
        foreach (str_split($format) as $char) {
            switch ($char) {
                case 'F':
                case 'M':
                    $string .= $this->getMonth($char);
                    break;
                case 'D':
                case 'l':
                    $string .= $this->getDayOfWeek($char);
                    break;
                // added e.g. 2012-04-09
                case 'C':
                    $string .= $this->_date->format('Y-m-d');
                    break;
                default:
                    $string .= $this->_date->format($char);
                    break;
            }
        }
        return $string;
    }

    public function isValid($test=TRUE) {
        if (!$test and is_null($this->_date)) {
            throw new ie\InternalException('No operation possible on invalid date');
        }
        return!is_null($this->_date);
    }

    public function __toString() {
        $this->isValid(\FALSE);
        return $this->toString('Y-m-d');
    }

    public function addDay($day) {
        $this->_addInterval($day, 'P%dD');
        return $this;
    }

    public function subDay($day) {
        $this->addDay(-$day);
        return $this;
    }

    public function addWeek($week){
        $this->addDay(7*$week);
        return $this;
    }
    
    public function subWeek($week){
        $this->addDay(-7*$week);
        return $this;
    }
    public function addMonth($month) {
        $this->_addInterval($month, 'P%dM');
        return $this;
    }

    public function subMonth($month) {
        $this->addMonth(-$month);
        return $this;
    }

    public function addYear($year) {
        $this->_addInterval($year, 'P%dY');
        return $this;
    }

    public function subYear($year) {
        $this->addYear(-$year);
        return $this;
    }

    protected function _addInterval($int, $format) {
        $this->isValid(\FALSE);
        $duration = abs($int);
        $interval = sprintf($format, $duration);
        if ($int < 0) {
            $this->_date->sub(new \DateInterval($interval));
        }
        else {
            $this->_date->add(new \DateInterval($interval));
        }
    }

    public function compare(Date $date) {
        $this->isValid(\FALSE);
        $me = $this->_date->format('Ymd');
        $other = $date->toString('Ymd');
        return strcmp($me, $other);
    }

    public function after(Date $date) {
        return $this->compare($date) > 0;
    }

    public function before(Date $date) {
        return $this->compare($date) < 0;
    }

    public function getDay() {
        $this->isValid(\FALSE);
        return $this->_date->format('d');
    }

    public function getMonth($format='d') {
        $this->isValid(\FALSE);
        switch ($format) {
            case '3':
            case 'M':
            case 'F':
                $format = $format == '3' ? 'M' : $format;
                return $this->_($this->_date->format($format), TRUE);
        }
        return $this->_date->format($format);
    }

    public function getDayOfWeek($format='w') {
        $this->isValid(\FALSE);
        switch ($format) {
            case '3':
            case 'D':
            case 'l':
                $format = $format == '3' ? 'D' : $format;
                return $this->_($this->_date->format($format), TRUE);
        }
        return $this->_date->format($format);
    }

    public function getYear() {
        $this->isValid(\FALSE);
        return $this->_date->format('Y');
    }

    public function getHours() {
        $this->isValid(\FALSE);
        return $this->_date->format('G');
    }

    public function getMinutes() {
        $this->isValid(\FALSE);
        return $this->_date->format('i');
    }

    public function getSeconds() {
        $this->isValid(\FALSE);
        return $this->_date->format('s');
    }

    /* Beginning of trait code */

    /**
     * Translates a message
     * @param string $message
     * @param boolean $system
     * @return string 
     */
    public function _($message, $system=\FALSE) {
        if ($system) {
            $translator = \Iris\Translation\SystemTranslator::GetInstance();
            return $translator->translate($message);
        }
        $translator = $this->getTranslator();
        return $translator->translate($message);
    }

    /**
     *
     * @staticvar \Iris\Translation\_Translator $translator
     * @return \Iris\Translation\_Translator
     */
    public function getTranslator() {
        static $translator = NULL;
        if (is_null($translator)) {
            $translator = \Iris\Translation\_Translator::GetCurrentTranslator();
        }
        return $translator;
    }

       
    public function serialize() {
        return $this->toString();
    }

    public function unserialize($serialized) {
        list($year, $month, $day) = explode('-', $serialized);
        $this->_date->setDate($year, $month, $day);
    }

    /* end of trait code */
}

?>
