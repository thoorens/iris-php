<?php

namespace modules\ajax\controllers;
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
 * Defines the layout for the Ajax and javascript pages
 */
class _ajax extends \modules\_application {

   protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('main');
        $this->__bodyColor = 'ORANGE3';
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in _ajax
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
        $this->callViewHelper('subtitle','Ajax');
    }

}
