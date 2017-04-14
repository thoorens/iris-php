<?php

namespace Iris\Users;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Abstract class to be extended by the entity containing the site user
 * settings
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _TUsers extends \Iris\DB\_Entity {

    /**
     * Gets the id field name
     * 
     * @return string
     */
    public function getIdField() {
        return \Iris\SysConfig\Settings::$UserFields['id'];
    }

    /**
     * Gets the username field name
     * 
     * @return string
     */
    public function getNameField() {
        return \Iris\SysConfig\Settings::$UserFields['UserName'];
    }

    /**
     * Gets the email field name
     * 
     * @return string
     */
    public function getEmailField() {
        return \Iris\SysConfig\Settings::$UserFields['EmailField'];
    }

    /**
     * Gets the role field name
     * 
     * @return string
     */
    public function getRoleField() {
        return\Iris\SysConfig\Settings::$UserFields['RoleField'];
    }

    /**
     *
     * @param type $name 
     * @return User
     */
    public function fetchUser($name) {
//        $object = $this->fetchRow("$this->_nameField =",$name);
//        return new User($object);
        return $this->fetchRow("$this->_nameField =", $name);
    }

    /**
     * Gets a list of known user name indexed by their id
     * 
     * @return array
     */
    public static function UserList() {
        $tusers = static::GetEntity();
        $tusers->select(array($tusers->getIdField(), $tusers->getNameField()));
        $users = $tusers->fetchAll();
        $nameField = $tusers->getNameField();
        foreach ($users as $user) {
            $list[$user->id] = $user->$nameField;
        }
        return $list;
    }

}
