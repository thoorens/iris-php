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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */

/**
 * Can call a view helper
 */
trait tViewHelperCaller{
    
    /**
     * Permits to call a view helper (some of which are simple interfaces
     * to javascript or Ajax code)
     * 
     * @param string $helperName
     * @param mixed $args (optional and 
     */
    public function callViewHelper($helperName){
        // gets optional parameters
        $args = func_get_args();
        array_shift($args);
        return \Iris\views\helpers\_ViewHelper::HelperCall($helperName, $args);
    }
    
}