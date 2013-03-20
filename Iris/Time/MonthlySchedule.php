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
class MonthlySchedule extends _Schedule {

    protected static $_Instance = NULL;
    
    /**
     * Initializes the object with a date in the range to search the first
     * and last days of the month. 
     * 
     * @param \Iris\Time\Date $date A date whose month will be considered
     * @param type $base if 1, monday will be column 1
     */
    public function init(\Iris\Time\Date $date, $base = 1) {
        // copy the date to modify it
        $dateCopy = clone $date;
        $dateCopy->setDay(1);
        $this->_firstDate = $dateCopy;
        $this->_lastDate = clone $dateCopy;
        $this->_lastDate->setDay($dateCopy->getMonthLength());
        $offset = ($dateCopy->getDayOfWeek()) - ($base);
        // In calendar 0 = Monday = 0, in Date Sunday = 0 
        if ($offset < -1) {
            $offset+=7;
        };
        $this->_offset = $offset;
        $this->_base = $base;
    }

    public function asArray() {
        $line = 1;
        $col = 0;
        $dateIndex = $this->_firstDate->subDay($this->_offset, \TRUE);
        do {
            $link = $this->_renderLinkToDay($dateIndex);
            $events = $this->_defaultCellContent;
            if (isset($this->_content["$dateIndex"])){
                $events = $this->_content["$dateIndex"];
            }
            // A first line is filled with the date of first line
            if($line == 1){
                $array[0][$col] = clone $dateIndex;
            }
            $array[$line][$col] = $this->_renderer->renderCell($events, $link, $this->_defaultCellClass);
            /* engine */
            $dateIndex->addDay(1);
            if((++$col) >6){
                $col = 0;
                $line++;
            }
            $continue = $dateIndex->until($this->_lastDate)| $col > 0;
        }
        while ($continue);
        //iris_debug($array);
        return $array;
    }
    

}