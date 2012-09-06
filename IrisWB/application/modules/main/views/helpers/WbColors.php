<?php


//define('P_RED', '#FCC');
//define('P_BLUE', '#CCF');
//define('P_GREEN', '#CFC');
//define('P_WHITE', '#FFF');
//define('P_MAGENTA', '#FCF');
//define('P_CYAN', '#CFF');
//define('P_GRAY', '#CCC');

namespace iris\views\helpers;

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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Management of colors in workbench
 * 
 */
class WbColors extends \Iris\views\helpers\_ViewHelper {

    static $_singleton = FALSE;

    public function help($color=NULL) {
        if (is_null($color)) {
            $reflection = new \Iris\Engine\Reflection($this->_view);
            $type = $reflection->getControllerType();
            if ($type == 'TRUE CONTROLLER') {
                $color = $this->_symbolicColor('GRAY'); // pale yellow
            }
            else {
                if ($type == 'ISLET') {
                    $name = 'BLUE';
                }
                else {
                    $name = 'GREEN';
                }
                if ($reflection->isInternal()) {
                    $level = 3;
                }
                elseif ($reflection->getModuleName(TRUE) == $reflection->getModuleName(FALSE)) {
                    $level = 1;
                }
                else {
                    $level = 2;
                }
                $color = $this->_symbolicColor($name, $level);
            }
        }
        else {
            $color = $this->_symbolicColor($color);
        }
        //initial space is required
        return " style=\"background-color:$color\"";
    }

    private function _symbolicColor($name, $level=1) {
        if ($name[0] == '#') {
            return $name;
        }
        switch ($name) {
            case 'WHITE':
                return '#FFFF';
            case 'GRAY':
                $color = array('#EEE', '#999', '777');
                return $color[$level - 1];
            case 'YELLOW':
                $color = array('#FFC', '#FFA', 'FF8');
                return $color[$level - 1];
            case 'BLUE':
                $color = array('#CEF', '#ACF', '#8AF');
                return $color[$level - 1];
            case 'GREEN':
                $color = array('#CFC', '#AFA', '#8FA');
                return $color[$level - 1];
            case 'RED':
                $color = array('#FCC', '#FAA', '#F88');
                return $color[$level - 1];
            case 'ORANGE':
                $color = array('#FEC','#FCA','#FA8','#F86');
                return $color[$level - 1];
            default:
                $length = strlen($name);
                $name0 = substr($name, 0, $length - 1);
                $level = substr($name, $length - 1);
                return $this->_symbolicColor($name0, $level);
        }
    }

}

