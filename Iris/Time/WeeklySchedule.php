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
class WeeklySchedule extends _Schedule {

    protected static $_Instance = NULL;

    /**
     * Initializes the object with a date in the range to search the first
     * and last days of the week. 
     * 
     * @param \Iris\Time\Date $date A date whose week will be considered
     * @param type $base if 1, monday will be column 1
     */
    public function init(\Iris\Time\Date $date, $base = 1) {
        // copy the date to modify it
        $dateCopy = clone $date;
        $offset = $base - $dateCopy->getDayOfWeek();
        $this->_offset = $offset;
        $this->_base = $base;
        $this->_firstDate = $dateCopy->addDay($offset);
        $this->_lastDate = clone $dateCopy;
        $this->_lastDate->addDay(6);
    }

    public function asArray() {
        list($from, $to) = $this->getDateRange();
        for ($day = $from; $day->until($to); $day->addDay(1)) {
            $link = $this->_renderLinkToDay($day);
            if (isset($this->_content["$day"])) {
                $data = $this->_content["$day"];
            }
            else {
                $data = new Events();
                $data->setEmpty($day, $this->_defaultCellContent);
            }
            $array[] = $data;
        }
        return $array;
    }

   
}