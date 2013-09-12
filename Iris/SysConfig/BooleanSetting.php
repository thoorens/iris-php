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
 * @copyright 2011-2013 Jacques THOORENS
 */


/**
 * A boolean setting has enable, disable and has methods
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class BooleanSetting extends _Setting {

    protected static $_Type = 'boolean';
    
    // Boolean accessors

    /**
     * Will return TRUE if the setting has been enabled
     */
    public function has() {
        return $this->_value === \TRUE;
    }

    /**
     * Enables a boolean setting  if it is not locked
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function enable() {
        if($this->_locked){
            $this->SettingError("The setting $this->_fullName has been locked.");
        }
        $this->_value = \TRUE;
    }

   /**
     * Disables a boolean setting if it is not locked
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function disable() {
        if($this->_locked){
            $this->SettingError("The setting $this->_fullName has been locked.");
        }
        $this->_value = \FALSE;
    }

    /**
     * Returns the required boolean value as a readable string, not a number
     * 
     * @return boolean
     */
    protected function _showValue() {
        return $this->_value ? 'TRUE' : 'FALSE';
    }

}

