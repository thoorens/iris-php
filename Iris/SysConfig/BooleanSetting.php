<?php

namespace Iris\SysConfig;

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
 * A boolean setting has enable, disable and has methods
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class BooleanSetting extends _Setting {

    // Boolean accessors

    public function has() {
        return $this->_value === \TRUE;
    }

    public function enable() {
        $this->_value = \TRUE;
    }

    public function disable() {
        $this->_value = \FALSE;
    }

    protected function _showType() {
        return "(boolean)";
    }

    protected function _showValue() {
        return $this->_value ? 'TRUE' : 'FALSE';
    }

}

