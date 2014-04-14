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

    const ID = 0;
    const NAME = 1;
    const ROLE = 2;
    const EMAIL = 3;
    const TIME = 4;
    
    /**
     * Unlike other iUser, somebody does not use an entity to 
     * get its member data.
     * 
     * @var \Iris\DB\_Entity
     */
    protected $_entity = \NULL;

    /**
     *
     * @param string $serialized 
     */
    public function __construct($serialized = NULL) {
        if (is_null($serialized)) {
            $this->_id = 0;
            $this->_name = Settings::GetDefaultUserName();
            $this->_role = Settings::GetDefaultRoleName();
            $this->_emailAddress = Settings::GetDefaultUserEmail();
        }
        else {
            $data = explode('&', $serialized);
            $this->_id = $data[self::ID];
            $this->_name = $data[self::NAME];
            $this->_role = $data[self::ROLE];
            $this->_mailAddress = $data[self::EMAIL];
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
        return Settings::GetDefaultUserName();
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

