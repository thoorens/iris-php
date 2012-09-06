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
 */

/**
 * A pseudo helper to manage messages for demo site
 *
 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
class Welcome extends _ViewHelper {


    /**
     * Returns a text to display in a demo site, according to parameter. When<ul>
     * <li> NULL : a localized welcome title and a tab with module, controller and action names
     * <li> 1 : only the welcome message
     * <li> 2 : only the tab
     * <li> otherwise : a fatal error message and the tab 
     * 
     * @param mixed $message
     * @return string 
     */
    public function help($message=NULL) {
        if (is_null($message)) {
            return $this->_title() . $this->_info();
        }
        elseif (is_numeric($message)) {
            switch ($message) {
                case 1:
                    return $this->_title();
                case 2:
                    return $this->_info();
                default:
                    return $this->_info($message);
            }
        }
        else {
            $text = "<h1>Fatal error</h1>";
            $text .= $message;
            return $text . $this->_info();
        }
    }

    /**
     * Returns a localized version of a welcome message
     * @return string
     */
    private function _title() {
        $welcome = $this->_('Welcome to IRIS-PHP framework', TRUE);
        return "<h1>$welcome</h1>";
    }

    /**
     * Returns a tab containing the module/controller/action names
     * in different format indicated by the width parameter. <ul>
     * <li> 0 : a table with no limit
     * <li> -1 : a paragraph with mca in line
     * <li> >7 : a table whose width is this parameter
     * 
     * @param int $width Various meaning
     * @return string 
     */
    private function _info($width=0) {
        $response = $this->getView()->getResponse();
        $type = $response->makedController->getType();
        $module = $response->getModuleName();
        $module = $module == '' ? '&lt;default&gt;' : $module;
        if ($response->isInternal()) {
            $module .= '<i>(int)</i>';
        }
        $controller = $response->getControllerName();
        $action = $response->getActionName();
        if ($width == 0) {
            $text = <<< TAB
 <table border="1">
    <tbody>
        <tr>
            <td><b>Module</b></td>
            <td><i>$module</i></td>
        </tr>
        <tr>
            <td><b>Controller</b></td>
            <td><i>$controller ($type)</i></td>
        </tr>
        <tr>
            <td><b>Action</b></td>
            <td><i>$action</i></td>
        </tr>
    </tbody>
</table>
     
        
TAB;
        }
        elseif ($width == -1) {
            $text = <<< LINE
            <p class="welcome_cont">
        <b>M</b>: $module - <b>C</b>: $controller ($type) - <b>A</b>: $action 
            </p>
LINE;
        }
        else {
            $text = <<< TABLINE
   <table border="1" width="$width%">
       <tr>
            <td>$module</td>
            <td>$controller ($type) </td>
            <td>$action</td>    
       </tr>
   </table>
TABLINE;
        }
        return $text;
    }

}

