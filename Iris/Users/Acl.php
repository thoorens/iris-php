<?php

namespace Iris\Users;

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
 * Implements a privilege management by using a Config found in Memory.
 * It can be used to know if a role has a privilege (directly or
 * by inheritance). If neither role nor acl are defined, everything is
 * permited
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */
class Acl implements \Iris\Design\iSingleton {

    /**
     * Implements a privilege management by using a Config found in Memory.
     * It can be used to know if a role has a privilege (directly or
     * by inheritance). If neither role nor acl are defined, everything is
     * permited.
     * 
     * @var Acl
     */
    private static $_Instance = NULL;
    private $_roles = array();
    private $_allowed = array();
    private $_denied = array();
    private $_noAcl = FALSE;
    public static $ParamName = 'param_acl';

    /**
     * Get or create the unique instance
     * 
     * @return Acl 
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new Acl();
        }
        return self::$_Instance;
    }

    /**
     * Private constructor for singleton
     */
    private function __construct() {
// try to find a param_acl in memory (readed in config files)
        $paramAcl = \Iris\Engine\Memory::Get(self::$ParamName);
        if (is_null($paramAcl)) {
// if not found, everything is allowed
            $this->_noAcl = TRUE;
        }
        else {
            Role::Init($paramAcl['roles']);
            // Using the effective user role
            $activeRoleName = Identity::GetInstance()->getRole();
            if (!isset($paramAcl[$activeRoleName])) {
                $user = Identity::GetInstance()->getName();
//                if (Session::IsSessionActive()) {
//                    session_destroy();
//                }
                $identity = Identity::GetInstance();
                $identity->setRole(Somebody::GetDefaultRole());
                $identity->sessionSave();
                throw new \Iris\Exceptions\InternalException("Internal error : role $activeRoleName not defined for user $user.");
            }
            // Add the privileges for his role
            $this->_addPrivileges($paramAcl, $activeRoleName);
            // Add his ancestor privileges
            $activeRole = Role::GetRole($activeRoleName);
            foreach ($activeRole->getAncestors() as $ancestor) {
                $this->_addPrivileges($paramAcl, $ancestor);
            }
        }
    }

    /**
     * Stores the privileges for a given role
     * 
     * @param array $paramAcl ACL read from config
     * @param type $roleName  the role name to be considered
     */
    private function _addPrivileges($paramAcl, $roleName) {
        foreach ($paramAcl[$roleName] as $key => $privilege) {
            // Fatal error in acl file
            $keyExploded = explode('.', $key);
            if (count($keyExploded) < 3) {
                if (\Iris\Engine\Program::IsDevelopment()) {

                    echo "Fatal error in ACL file<br>";
                    iris_debug($key);
                }
                else {
                    $keyExploded[2] = 'index';
                }
            }
            list($command, $module, $controller) = $keyExploded;
            $resource = "/$module/$controller";
            if ($command == 'allow') {
                $this->_allowed[$roleName][$resource] = $privilege == '' ? 'ALL' : $privilege;
            }
            else {
                $this->_denied[$roleName][$resource] = $privilege == '' ? 'ALL' : $privilege;
            }
        }
    }

    /**
     *
     * @param \Iris\Engine\Response $response
     * @return boolean
     */
    function hasPrivilege($resource, $action) {
// in site without ACL, evry page is visible
        if ($this->_noAcl) {
            return TRUE;
        }

        if ($resource == '//') {
            return TRUE;
        }
// error display is allowed 
        if ($resource == '/main/ERROR') {
            return TRUE;
        }
        if (\Iris\Engine\Response::GetDefaultInstance()->isInternal()) {
            return TRUE;
        }
        $activeRole = \Iris\Users\Identity::GetInstance()->getRole();
        $role = \Iris\Users\Role::GetRole($activeRole);
        $ownedRoles = $role->getAncestors();
        array_unshift($ownedRoles, $activeRole);
        foreach ($ownedRoles as $testedRole) {
// explicit prohibition
            if (isset($this->_denied[$testedRole][$resource])) {
                $denied = $this->_denied[$testedRole][$resource];
                if ($denied == 'ALL' or in_array($action, explode(',', $denied))) {
                    $roleDesc = $role->getName() . '(acting as ' . $testedRole . ')';
                    return FALSE;
                }
            }
// explicit permission
            if (isset($this->_allowed[$testedRole][$resource])) {
                $allowed = $this->_allowed[$testedRole][$resource];
                if ($allowed == 'ALL' or in_array($action, explode(',', $allowed))) {
                    $roleDesc = $role->getName() . '(acting as ' . $testedRole . ')';
                    return TRUE;
                }
            }
        }
// what is not allowed is prohibited
        return FALSE;
    }

}

?>
