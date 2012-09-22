<?php

namespace Iris\MVC;

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
 * Subcontrollers are managed by a main controller which sends them
 * variable values for their view, orders them to render their
 * view whose content will be displayed in mainview or the layout
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Subcontroller extends _Islet {

    /**
     * A signature for system trace
     * 
     * @var string
     */
    protected static $_Type = 'SUBCONT';

    /**
     * The subcontroller registers itself in the main controller, with a unique number. 
     * This number will serve as a second parameter for the toView method in order to 
     * send variables to the view linked to the subcontroller.
     * Il will also serve as a parameter to the SubView helper in the layout.
     * 
     * @param int $num 
     * @return self (fluent interface)
     */
//    public function register($num) {
//        self::$_MainController->subcontrollers[$num] = $this;
//        return $this;
//    }
   


}

