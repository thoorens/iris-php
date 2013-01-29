<?php

namespace ILO\views\helpers;

use \Iris\views\helpers\_ViewHelper;

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
 * @version $Id: $/**
 * This helper creates an islet for development administration management
 * 
 */

/**
 * Display a ToolBar for administrative purpose at development time only
 */
class AdminToolBar extends _ViewHelper {

    protected static $_Singleton = true;

    /**
     *
     * @var boolean If true, the toolbar will have a menu for all actions 
     * (if possible)
     */
    private $_menu = \TRUE;

    /**
     * returns the HTML text for the toolbar or the reference for later use
     * 
     * @param boolean $display
     * @param int $color
     * @return mixed
     */
    public function help($display = \TRUE, $color = '#148') {
        if (is_null($display) or $display == '') {
            return $this;
        }
        return $this->render($display, $color);
    }

    /**
     * returns the HTML text for the toolbar
     * 
     * @param boolean $display
     * @param int $color
     * @return string
     */
    public function render($display = \TRUE, $color = '#148'){
        if (!\Iris\Engine\Mode::IsProduction() and $display) {
            return $this->_view->islet('control', [$color, $this->_menu], 'index', '!admin');
        }
    }
    
    /**
     * Accessor for the menu variable (if true will display a menu with all actions
     * @param boolean $menu
     */
    public function setMenu($menu) {
        $this->_menu = $menu;
    }

}

?>
