<?php

namespace Iris\views\helpers;

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
 *
 * 
 */

/**
 * A localized formater for a date and time: eg. 5 mars - 13:15
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class SimpleTimeDate extends \Iris\views\helpers\_ViewHelper {

    protected $_singleton = TRUE;

    /**
     * Returns a localized formated date: e.g. 5 janvier 2013
     * 
     * @param \Iris\Time\TimeDate/string $dateTime
     * @return string
     */
    public function help($dateTime) {
        if(is_string($dateTime)){
            $dateTime = new \Iris\Time\TimeDate($dateTime);
        }
        return $dateTime->toString('j F - G:i');
    }

}
