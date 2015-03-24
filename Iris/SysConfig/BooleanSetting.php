<?php

namespace Iris\SysConfig;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
    public function enable($lock = \FALSE) {
        if ($this->_locked) {
            $this->SettingError("The setting $this->_fullName has been locked.");
        }
        $this->_value = \TRUE;
        $this->_locked = $lock;
    }

    /**
     * Disables a boolean setting if it is not locked
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function disable($lock = \FALSE) {
        if ($this->_locked) {
            $this->SettingError("The setting $this->_fullName has been locked.");
        }
        $this->_value = \FALSE;
        $this->_locked = $lock;
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

