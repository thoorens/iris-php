<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
 */

/**
 * Description of ShowDate
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class ShowDate extends \Iris\views\helpers\_ViewHelper {

    private static $_Date;

    
    
    protected function _init() {
        $this->_date = new \Iris\Time\Date();
    }

    /**
     * 
     * @param type $format
     * @param \Iris\Time\Date $date
     * @return \iris\views\helpers\ShowDate (or string)
     */
    public function help($format, $date = \NULL) {
        if ($format===\NULL) {
            return $this;
        }
        if(is_null($date)){
            $date = self::$_Date;
        }
        if($format=="#DEF#"){
            $formatedDate = $date->toString();
            $format = '';
        }
        else{
            $formatedDate = $date->toString($format);
        }
        return <<<FIN
    <th>
        $format
    </th>
    <td>
        $formatedDate
    </td>
FIN;
    }

    public function setDate($date) {
        self::$_Date = $date;
    }

}

?>
