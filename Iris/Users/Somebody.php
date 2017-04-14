<?php

namespace Iris\Users;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */


/**
 * A dummy user having no right. Can also serve to recrate a user
 * serialized in a session variable.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Somebody {

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
            $this->_name = \Iris\SysConfig\Settings::$DefaultUserName;
            $this->_role = \Iris\SysConfig\Settings::$DefaultRoleName;
            $this->_emailAddress = \Iris\SysConfig\Settings::$DefaultUserMail;
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
        \Iris\SysConfig\Settings::$DefaultUserName =$name;
    }

    /**
     * 
     * @return type
     * @deprecated since version 1.0 (use Settings)
     */
    public static function GetDefaultName() {
        return \Iris\SysConfig\Settings::$DefaultUserName;
    }

    /**
     * 
     * @param string $name
     * @deprecated since version 1.0 (use Settings)
     */
    public static function SetDefaultRole($name) {
        \Iris\SysConfig\Settings::$DefaultRoleName =$name;
    }

    /**
     * 
     * @return string
     * @deprecated since version 1.0 (use Settings)
     */
    public static function GetDefaultRole() {
        return \Iris\SysConfig\Settings::$DefaultRoleName;
    }

    public function __get($propName) {
        return "$propName of " . $this->getName();
    }

}

