<?php

namespace IrisInternal\workbench\controllers;

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
 * Demo class for Iris Workbench
 * It is a test class for Islets (type internal)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class islDemo extends \IrisInternal\main\controllers\_SecureIslet {

    /**
     *
     * @param string $text A message received from outside 
     */
    public function indexAction($text="Message not found") {
        // islets are not completely deaf
        // the action parameter should have been initialized in the helper Islet call 
        // and the tooltip comes from anywhere through Memory
        $this->__tooltip = $this->_fromMemory('tooltip');
        $number = 12;
        $this->__result = $this->compute($number, '*');
        $this->__text = $text;
    }

}


