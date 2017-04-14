<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

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
                $color = ['#EEE', '#999', '777'];
                return $color[$level - 1];
            case 'YELLOW':
                $color = ['#FFC', '#FFA', 'FF8'];
                return $color[$level - 1];
            case 'BLUE':
                $color = ['#CEF', '#ACF', '#8AF'];
                return $color[$level - 1];
            case 'GREEN':
                $color = ['#CFC', '#AFA', '#8FA'];
                return $color[$level - 1];
            case 'RED':
                $color = ['#FCC', '#FAA', '#F88'];
                return $color[$level - 1];
            case 'ORANGE':
                $color = ['#FEC','#FCA','#FA8','#F86'];
                return $color[$level - 1];
            default:
                $length = strlen($name);
                $name0 = substr($name, 0, $length - 1);
                $level = substr($name, $length - 1);
                return $this->_symbolicColor($name0, $level);
        }
    }

}

