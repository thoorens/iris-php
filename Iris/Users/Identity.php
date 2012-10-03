<?php

namespace Iris\Users;

use Iris\Engine as ie;

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
 * A class managing the identity of the user. This class is essentiel in the
 * system and is automatically created by the class Session.
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Identity implements iUser, \Serializable {
    const DEFAULT_TIMEOUT = 14400;
    const DEFAULT_PRODUCTION_TIMEOUT = 600;

    const NAME = 0;
    const MAIL = 1;
    const ROLE = 2;
    const ID = 3;
    const TIMER = 4;


    private $_mailAddress;
    private $_name;
    private $_role;
    private $_id;
    private $_timer;
    private static $_Timeout = self::DEFAULT_TIMEOUT;
    private static $_ProductionTimeout = self::DEFAULT_PRODUCTION_TIMEOUT;
    private static $_Instance = NULL;

    /**
     * Returns the unique instance created by the session
     * 
     * @return Identity 
     */
    public static function GetInstance() {
        // Session has created instance
        if (is_null(self::$_Instance)) {
            throw new \Iris\Exceptions\SessionException('Session must be active to access Identity');
        }
        return self::$_Instance;
    }

    /**
     * May be called once by the session object
     * 
     * @param Somebody $user 
     */
    public function __construct($user = NULL) {
        // no multiple instances of Identity
        if (!is_null(self::$_Instance)) {
            throw new \Iris\Exceptions\SessionException('Identity must be instanciated only once.');
        }
        // if string is provided, it is serialized data resulting from a session
        if (is_string($user)) {
            $this->unserialize($user);
            // verify timeout
            if (ie\Program::IsDevelopment()) {
                $timeout = ie\Memory::Get('defaultTimeout', self::DEFAULT_TIMEOUT);
            }
            else {
                $timeout = ie\Memory::Get('defaultProductionTimeout', self::DEFAULT_PRODUCTION_TIMEOUT);
            }
            if ($this->_timer + $timeout < time()) {
                $this->userClone(new Somebody());
            }
        }
        else {
            // if no user specified, create a new one, Mr Somebody, with role browse
            if (is_null($user)) {
                $user = new Somebody();
            }
            $this->userClone($user);
        }
        $this->_resetTimer();
        self::$_Instance = $this;
    }

    /**
     * An activity can reset the timer
     * 
     */
    protected function _resetTimer() {
        $this->_timer = time();
        $this->sessionSave();
    }

    protected static function SetTImeOut($duration, $prodDuration=NULL) {
        if (!is_null($duration)) {
            self::$_Timeout = $duration;
        }
        if (!is_null($prodDuration)) {
            self::$_ProductionTimeout = $prodDuration;
        }
    }

    /**
     *
     * @return string 
     */
    public function getEmailAddress() {
        return $this->_mailAddress;
    }

    /**
     *
     * @return string 
     */
    public function getName() {
        return $this->_name;
    }

    /**
     *
     * @return array
     */
    public function getRole() {
        return $this->_role;
    }

    /**
     * Test a role accepting inheritance
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function playRole($expectedRoleName){
        return $this->hasRole($expectedRoleName, FALSE);
    }
    
    /**
     * Test a role strictly
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function hasRole_Strict($expectedRoleName){
        return $this->hasRole($expectedRoleName, TRUE);
    }
    
    /**
     * Determines if the connected user has a role (strickly or by inheritance)
     * The alias playRole and hasRole_Strict methods may be used for more clarity.
     * 
     * @param string $expectedRole the role concerned by the test
     * @param boolean $strict true if no inheritance accepted
     * @return boolean
     */
    public function hasRole($expectedRoleName, $strict=TRUE) {
        $roleName = $this->getRole();
        $hasIt = $expectedRoleName == $roleName;
        if (($strict or $hasIt)) {
            return $hasIt;
        }
        $role = \Iris\Users\Role::GetRole($roleName);
        $ancetres = $role->getAncestors();
        return array_search($expectedRoleName, $ancetres) !== FALSE;
    }

    /**
     *
     * @param iUser $user 
     */
    public function userClone(iUser $user) {
        $this->_name = $user->getName();
        $this->_mailAddress = $user->getEmailAddress();
        $this->_role = $user->getRole();
        $this->_id = $user->getId();
    }

    public function serialize() {
        $data[self::NAME] = $this->getName();
        $data[self::MAIL] = $this->getEmailAddress();
        $data[self::ROLE] = $this->getRole();
        $data[self::ID] = $this->getId();
        $data[self::TIMER] = $this->_timer;
        return implode('&', $data);
    }

    public function unserialize($serialized) {
        $data = explode('&', $serialized);
        $this->_name = $data[self::NAME];
        $this->_mailAddress = $data[self::MAIL];
        $this->_role = $data[self::ROLE];
        $this->_id = $data[self::ID];
        $this->_timer = $data[self::TIMER];
    }

    public function sessionSave() {
        $_SESSION['identity'] = $this->serialize();
    }

    public function reset() {
        $this->userClone(new Somebody);
        unset($_SESSION['identity']);
    }

    public function setEmailAddress($mailAddress) {
        $this->_mailAddress = $mailAddress;
        return $this;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function setRole($role) {
        $this->_role = $role;
        return $this;
    }

    public function setId($id){
        $this->_id = $id;
        return $this;
    }
    
    public function setTimer($_timer) {
        $this->_timer = $_timer;
    }

    public function getId() {
        return $this->_id;
    }

    public static function IsA($expectedRole,$strict=FALSE){
        if($strict)
            return self::GetInstance()->hasRole_Strict ($expectedRole);
        else
            return self::GetInstance ()->playRole ($expectedRole);
    }
}

?>
