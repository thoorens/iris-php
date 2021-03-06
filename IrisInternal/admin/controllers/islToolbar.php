<?php

namespace IrisInternal\admin\controllers;
;

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
 * This islet is used to display the admin toolbar without Ajax
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class islToolbar extends \Iris\MVC\_Islet {

    /**
     * Non ajax version of admin toolbar
     * 
     * @param string $color Background color
     * @param boolean $menu if true, display the action menu
     */
    public function indexAction($color, $menu = \FALSE) {
        die('islet Toolbar');
        // the code is shared by the ajax version (see Iris\controllers\helpers)
        $this->__color=$color;
        $this->__time = "n/a";
        $this->__MENU = $menu;
        $this->setViewScriptName('toolbar');
    }

}


