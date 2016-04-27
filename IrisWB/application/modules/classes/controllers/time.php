<?php

namespace modules\classes\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of time
 * 
 * @author 
 * @license 
 */
class time extends _classes {

    /**
     * All the symbols used day formating. 
     * The key is the symbol
     * Each item has two values: <ul>
     *    <li> a description of the conversion
     *    <li> a boolean TRUE to indicate an iris-php special symbol
     * @var array
     */
    protected $_daysFormats = [
        'd' => ['Day of the month, 2 digits with leading zeros  01 to 31', \FALSE],
        'j' => ['Day of th month, without leading zeros, 1 to 31', \FALSE],
        'D' => ['A textual representation of a day, three letters Mon through Sun', \FALSE],
        'l' => ["A full textual representation of the day of the week 	Sunday through Saturday (lowercase 'L')", \FALSE],
        'N' => ['ISO-8601 numeric representation of the day of the week 1 (for Monday) through 7 (for Sunday)', \FALSE],
        'S' => ["English ordinal suffix for the day of the month, 2 characters	st, nd, rd or th. Works well with j", \FALSE],
        'w' => ['Numeric representation of the day of the week 0 (for Sunday) through 6 (for Saturday)', \FALSE],
        'z' => ["The day of the year (starting from 0) 0 through 365", \FALSE],
        'W' => ['ISO-8601 week number of year, weeks starting on Monday	Example: 42 (the 42nd week in the year)']];

    /**
     * All the symbols used in month formating
     * @var array
     * 
     * Same presentation as in $_days
     */
    protected $_monthsFormats = [
        'F' => ['A full textual representation of a month, such as January or March 	January through December', \FALSE],
        'm' => ['Numeric representation of a month, with leading zeros 	01 through 12', \FALSE],
        'M' => ['A short textual representation of a month, three letters Jan through Dec', \FALSE],
        'n' => ['Numeric representation of a month, without leading zeros 1 through 12', \FALSE],
        't' => ['Number of days in the given month 28 through 31', \FALSE]
    ];
    protected $_yearsFormats = [
        'L' => ["Whether it's a leap year 	1 if it is a leap year, 0 otherwise.", \FALSE],
        'Y' => ['A full numeric representation of a year, 4 digits Examples: 1999 or 2003', \FALSE],
        'y' => ['A two digit representation of a year 	Examples: 99 or 03]', \FALSE],
        'o' => ['ISO-8601 year number. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead. Examples: 1999 or 2003', \FALSE]
    ];
    protected $_timeFormats = [
        'a' => ['Lowercase Ante meridiem and Post meridiem 	am or pm', \FALSE],
        'A' => ['Uppercase Ante meridiem and Post meridiem 	AM or PM', \FALSE],
        'g' => ['12-hour format of an hour without leading zeros 	1 through 12', \FALSE],
        'G' => ['24-hour format of an hour without leading zeros 	0 through 23', \FALSE],
        'h' =>[ '12-hour format of an hour with leading zeros 	01 through 12', \FALSE],
        'H' =>[ '24-hour format of an hour with leading zeros 	00 through 23', \FALSE],
        'i' =>[ 'Minutes with leading zeros 	00 to 59', \FALSE],
        's' =>[ 'Seconds, with leading zeros 	00 through 59', \FALSE],
        'B' => ['Swatch Internet time 	000 through 999', \FALSE],
        'u' => ['Microseconds (added in PHP 5.2.2). Note that date() will always generate 000000 since it takes an integer parameter, whereas DateTime::format() does support microseconds. 	Example: 654321', \FALSE]
    ];

    /**
     * In all the action of the controller, the french language is imposed, as a proof that the translation process
     * is functioning
     */
    protected function _init() {
        \Iris\SysConfig\Settings::$DefaultLanguage = 'fr';
    }

    public function constructAction() {
        $this->__date = new \Iris\Time\Date();
        $this->__date2 = new \Iris\Time\Date('2015/12/31');
        $this->__time = new \Iris\Time\Time();
        $this->__time2 = new \Iris\Time\Time('7:10:20');
        $this->__datetime = new \Iris\Time\TimeDate();
        $this->__datetime2 = new \Iris\Time\TimeDate('2015/12/31 7:10:20');
    }

    public function daysAction() {
        $this->__dayFormats = $this->_daysFormats;
        $this->__date = new \Iris\Time\Date('2015/01/02');
    }

    public function monthsAction() {
        $this->__monthFormats = $this->_monthsFormats;
        $this->__date = new \Iris\Time\Date('2015/01/02');
    }

    public function yearsAction() {
        $this->__yearFormats = $this->_yearsFormats;
        $this->__date = new \Iris\Time\Date('2015/01/02');
    }

    public function dateextAction() {
        $baseDate = new \Iris\Time\Date('2016/02/28');
        $this->__baseDate = $baseDate;
        $otherDate = new \Iris\Time\Date('2016/04/10');
        $this->__otherDate = $otherDate;
        $this->__equals = $baseDate->equals($otherDate) ? "***TRUE***" : "***FALSE***";
        $this->__before = $baseDate->before($otherDate) ? "***TRUE***" : "***FALSE***";
        $this->__after = $baseDate->after($otherDate) ? "****FALSE****" : "***FALSE***";
        $this->__since = $baseDate->since($baseDate) ? "***TRUE***" : "***FALSE***";
        $this->__since2 = $baseDate->since($otherDate) ? "***TRUE***" : "***FALSE***";
        $this->__until = $baseDate->until($baseDate) ? "***TRUE***" : "***FALSE***";
        $this->__until2 = $baseDate->until($baseDate) ? "***TRUE***" : "***FALSE***";
    }

    public function timeAction() {
        $this->__time = new \Iris\Time\Time('13:10:05');
        $this->__times = $this->_timeFormats;
    }

}
