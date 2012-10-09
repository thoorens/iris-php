<?php

namespace Iris\Time;

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
 * This class is a subhelper for the helper Crud. 
 * Il is aimed to manage icons for a CRUD system.
 * Each main function has a special icon, which can
 * be active or not, have an action link and display an understandable 
 * tooltip. It is localized but must receive a pretranslated entity 
 * with appropriate gender mark.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Schedule extends \Iris\Subhelpers\_Subhelper {

    /**
     * The name of the session var to conserve current date
     * @var string
     */
    public static $SessionVar = 'agendate';

    /**
     * First day of the period
     * @var Date
     */
    protected $_firstDate;

    /**
     *
     * @var Date
     */
    protected $_lastDate;

    /**
     * The offset of the _date cell relative to the the first cell
     * 
     * @var int
     */
    protected $_offset;

    /**
     * A not completely filled array with data. Index is the date in Y-m-d format
     * 
     * @var array(Events)
     */
    protected $_content;

    /**
     * First day of the week (0 = sunday, 1 = monday)
     * @var int
     */
    protected $_base;

    /**
     * A cell may have a default class
     * 
     * @var string
     */
    protected $_defaultCellClass = '';

    /**
     * What to display in empty cells
     * @var 
     */
    protected $_defaultCellContent = ' ';
    protected $_linkToDay = \NULL;

    abstract public function init(Date $date, $base = 1);

    /**
     * 
     * @param Date $date
     * @param iEvent $event
     */
    public function put($date, $event) {
        if (!isset($this->_content["$date"])) {
            $this->_content["$date"] = new Events();
        }
        $this->_content["$date"]->addEvent($event, $date);
    }

    /**
     * 
     * @param type $date
     * @return Events
     */
    public function get($date) {
        if ($date instanceof \Iris\Time\TimeDate) {
            $date = $date->toString('Ymd');
        }
        if (!isset($this->_content[$date])) {
            return \NULL;
        }
        else {
            return $this->_content[$date];
        }
    }

    /**
     * Manages all the events in the range
     */
    public abstract function asArray();

    /**
     * Generates a link to a day in the range first-last
     * 
     * @param Date $date
     * @return string
     */
    protected function _renderLinkToDay($date) {
        // no link provided
        if (is_null($this->_linkToDay)) {
            $string = '';
        }
        // if outside
        elseif ($date->after($this->_lastDate) or $date->before($this->_firstDate)) {
            $string = '';
        }
        else {
            $link = "$this->_linkToDay$date";
            $format = "onClick='document.location.href=\"%s\"'";
            $format .= ' title="Afficher le %s" ';
            $string = sprintf($format, $link, $date->toString('j F'));
        }
        return $string;
    }

    /**
     * Required method to prepare arg1 of renderer: it will be an array of
     * all event set for the day in the range.
     * 
     * @param dummy $arg1
     * @return array
     */
    public function prepare($arg1) {
        return parent::prepare($this->asArray());
    }

    /**
     * Returns an array with the names of the day in the week, starting by monday
     * by default (0 is sunday). The format can be changed conforming to standard
     * code : D l w N (3 is synonym for D). The names are localized.
     * 
     * No longer used but may be useful
     * 
     * @param string $format
     * @param int $base
     * @return array
     */
    public static function GetWeekDays($format = '3', $base = 1) {
        $date = new \Iris\Time\Date('2012-09-30'); // a sunday
        $date->addDay($base);
        for ($i = 1; $i < 8; $i++) {
            $dow[] = $date->getDayOfWeek($format);
            $date->addDay(1);
        }
        return $dow;
    }

    /**
     * Permits to specify a class for all cells
     * @param type $cellClass
     */
    public function setDefaultCellClass($cellClass) {
        $this->_defaultCellClass = $cellClass;
    }

    /**
     * Permits to set a link to the day corresponding to the cell
     * 
     * @param string $linkToDay
     */
    public function setLinkToDay($linkToDay) {
        $this->_linkToDay = $linkToDay;
    }

    /**
     * Returns two cloned copy of the range dates of the schedule
     * 
     * @return array(Date)
     */
    public function getDateRange() {
        return array(clone $this->_firstDate, clone $this->_lastDate);
    }

    /**
     * 
     * @param string $type
     * @param num $offset
     * @return \Iris\Time\Date
     */
    public static function ParameterAnalysis($type, $offset) {
        if (is_null($type) or $offset == 'today') {
            $date = new \Iris\Time\Date();
        }
        else {
            $date3 = explode('-', $offset);
            if (count($date3) == 3) {
                $date = new \Iris\Time\Date($offset);
            }
            else {
                $oldDate = \Iris\Engine\Superglobal::GetSession(self::$SessionVar, (new \DateTime)->format('C'));
                $date = new \Iris\Time\Date($oldDate);
                $method = 'add' . ucfirst($type);
                $date->$method($offset);
            }
        }
        $_SESSION['agendate'] = $date->toString();
        return $date;
    }

}