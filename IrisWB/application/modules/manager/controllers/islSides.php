<?php

namespace modules\manager\controllers;
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
 * @version $Id: $ */

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of sides
 * 
 * @author jacques
 * @license not defined
 */
class islSides extends \Iris\MVC\_Islet {

    public function leftAction() {
        $tSections = new \models\TSections();
        $tSections->where('id>', 0);
        $this->__sections = $tSections->fetchall();
    }

    public function rightAction() {
       $this->_view->dojo_Mask();
       $this->__sequence = $this->getScreenList();
    }

}
