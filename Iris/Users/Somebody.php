<?php

namespace Iris\Users;
use \Iris\SysConfig\Settings;

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
 * A dummy user having no right. Can also serve to recrate a user
 * serialized in a session variable.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Somebody implements iUser {
    use tUser;
    
    
    protected $_emailAddress = 'info@irisphp.org';

    /**
     *
     * @param string $serialized 
     */
    public function __construct($serialized = NULL) {
        $this->_name = Settings::GetDefaultUserName();
        $this->_role = Settings::GetDefaultRoleName();
        if (!is_null($serialized)) {
            $data = explode('&', $serialized);
            $this->_name = $data[0];
            $this->_mailAddress = $data[1];
            $this->_role = $data[2];
            $this->_id = $data[3];
        }
    }

    
    /**
     * 
     * @param string $name
     * @deprecated since version 1.0 (use Settings)
     */
    public static function SetDefaultName($name) {
        Settings::SetDefaultUserName($name);
    }

    /**
     * 
     * @return type
     * @deprecated since version 1.0 (use Settings)
     */
    public static function GetDefaultName() {
        Settings::GetDefaultUserName();
    }

    /**
     * 
     * @param string $name
     * @deprecated since version 1.0 (use Settings)
     */
    public static function SetDefaultRole($name) {
        Settings::SetDefaultRoleName($name);
    }

    /**
     * 
     * @return string
     * @deprecated since version 1.0 (use Settings)
     */
    public static function GetDefaultRole() {
        return Settings::GetDefaultRoleName();
    }

    public function __get($propName) {
        return "$propName of " . $this->getName();
    }



}

