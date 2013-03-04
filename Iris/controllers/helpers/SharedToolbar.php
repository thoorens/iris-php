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
 * @copyright 2011-2013 Jacques THOORENS
 */


/**
 * This helper permits to share code between islet and ajax versions
 * of admin toolbar. This code is placed here because an internal
 * islet can't find a helper in the IrisInternal library. This may be
 * considered as a bug.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class SharedToolbar extends _ControllerHelper{
    
    /**
     * Shared code between islet islControl and controller Ajax for the toolbar
     * 
     * @param string $time The execution time (or a dummy value)
     * @param boolean $menu If true, the action menu is displayed
     * @param string $color Background color for toolbar
     */
    public function help($mode ,$time, $menu, $color){
        $this->__modeIcon = ''; // by default nothing
        $this->__color=$color;
        $this->__time = $time;
        $this->__reverseColor = \Iris\System\Functions::GetComplementaryColor($color);
        $this->__rtdColor = 'white';
        \Iris\Users\Session::GetInstance();
        $identity = \Iris\Users\Identity::GetInstance();
        $this->__userLabel = 'User:';
        $this->__userName = $identity->getName();
        $this->__group = $identity->getRole();
        $this->__MENU = $menu;
    }
}
?>
