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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * This helper is part of the CRUD facilites offered by Iris. It serves to
 * display icons for the different actions. The most part of its job is
 * done by \Iris\Subhelpers\CrudIconManager.
 *
 */
class CrudIcon extends _ViewHelper{

    use \Iris\Subhelpers\tSubhelperLink;

    /**
     * Defines the subhelper used by the class. One can use another subhelper
     * in a derived class by defining $_AlternateSubhelper
     */
    protected function _init() {
        $this->_subhelperName = \models\crud\CrudIconManager::GetInstance();
    }



}
