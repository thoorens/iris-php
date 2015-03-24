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
 * A standard setting has get and set methods
  @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class StandardSetting extends _Setting {

    protected static $_Type = 'standard';

    /**
     * Returns the value of the setting
     *  
     * @return mixed
     */
    public function get() {
        return $this->_value;
    }

    /**
     * Sets the new value of a setting if it is not locked
     * 
     * @param mixed $value
     */
    public function set($value, $lock = \FALSE) {
        if ($this->_locked) {
            $this->SettingError("The setting $this->_fullName has been locked.");
        }
        $this->_value = $value;
        $this->_locked = $lock;
    }

}

