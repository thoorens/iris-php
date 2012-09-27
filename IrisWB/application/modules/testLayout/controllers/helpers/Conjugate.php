<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\controllers\helpers;

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
 * 
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Conjugate extends _ControllerHelper{
    private $_accents = [
        'a' => 'á',
        'e' => 'é',
        'i' => 'í',
        'o' => 'ó',
        'u' => 'ú',
    ];
    
    /**
     * Conjugates a spanish verb using a method of the calling controller.
     * 
     * This example is only for DEMONSTRATION PURPOSE because it makes no senses to 
     * put the variables and the method in the controller. Il could be more interestin
     * to have various helpers accessing the same controller method or sharing some
     * controller variable.
     * 
     * @param string $verb
     * @param int $number
     * @return string
     */
    public function help($verb,$number){
        switch($number){
            case 1;
                $radix = $this->dropLast($verb,2);
                break;
            case 5;
                $radix = $this->dropLast($verb,2);
                $radix.= $this->_accents[$verb[strlen($verb)-2]];
                break;
            default:
                $radix = $this->dropLast($verb);
                break;
        }
        // the helper can modify a public variable in the calling controller
        $this->title = "Spanish verbs";
        // the helper can read a public variable in the calling controller
        return $radix.$this->endings[$number];
    }
}
?>
