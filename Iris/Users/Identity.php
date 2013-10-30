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

    use tUser;

    protected $_entity = \NULL;

    /**
     * The default time out for development (4 hours)
     */

    const DEFAULT_TIMEOUT = 14400;
    /**
     * The default time out for production (10 minutes)
     */
    const DEFAULT_PRODUCTION_TIMEOUT = 600;

    private static $_Timeout = self::DEFAULT_TIMEOUT;

    /**
     *
     * @var type 
     */
    private static $_ProductionTimeout = self::DEFAULT_PRODUCTION_TIMEOUT;

    /**
     *
     * @var type 
     */
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
     * Should be called once by session singleton (another call would be identical to GetInstance())
     * 
     * @staticvar type $created
     * @return Identiy
     */
    public static function CreateInstance($identity) {
        static $created = \FALSE;
        if (!$created) {
            self::$_Instance = new Identity($identity);
            $created = \TRUE;
        }
        return self::$_Instance;
    }

    /**
     * The private constructor
     * 
     * @param iUser $user 
     */
    private function __construct($user = NULL) {
        // no multiple instances of Identity
        if (!is_null(self::$_Instance)) {
            throw new \Iris\Exceptions\SessionException('Identity must be instanciated only once.');
        }
        // if string is provided, it is serialized data resulting from a session
        if (is_string($user)) {
            $this->unserialize($user);
            // verify timeout
            if (ie\Mode::IsDevelopment()) {
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

    /**
     * Changes the timeout duration:<ul>
     * <li> SetTimeOut(X,Y) : changes runtime to X, production to Y
     * <li> SetTimeOut(X) : changes only runtime to X
     * <li> SetTimeOut(\NULL, Y) : changes only production to Y
     * </ul>
     * 
     * @param type $duration
     * @param type $prodDuration
     */
    public static function SetTImeOut($duration, $prodDuration = NULL) {
        if (!is_null($duration)) {
            self::$_Timeout = $duration;
        }
        if (!is_null($prodDuration)) {
            self::$_ProductionTimeout = $prodDuration;
        }
    }

    /**
     * Copies all data (except itmeout) from a user to the current identity object.
     * 
     * @param iUser $user 
     */
    public function userClone(iUser $user) {
        $this->_name = $user->getName();
        $this->_mailAddress = $user->getEmailAddress();
        $this->_role = $user->getRole();
        $this->_id = $user->getId();
    }

    /**
     * Serializes the current object to a string containing field values
     * separated by &
     * @return string
     */
    public function serialize() {
        $data[0] = $this->getName();
        $data[1] = $this->getEmailAddress();
        $data[2] = $this->getRole();
        $data[3] = $this->getId();
        $data[4] = $this->_timer;
        return implode('&', $data);
    }

    /**
     * Recovers data from a string containing field values
     * separated by &
     * 
     * @param string $serialized
     */
    public function unserialize($serialized) {
        $data = explode('&', $serialized);
        $this->_name = $data[0];
        $this->_mailAddress = $data[1];
        $this->_role = $data[2];
        $this->_id = $data[3];
        $this->_timer = $data[4];
    }

    /**
     * Saves the serialized present object to session array
     */
    public function sessionSave() {
        $_SESSION['identity'] = $this->serialize();
    }

    /**
     * Clears all values of the current identity object, resets them
     * to Somedy defaults and clears the current session identity var
     */
    public function reset() {
        $this->userClone(new Somebody);
        unset($_SESSION['identity']);
    }

    /**
     * Accessor set for email address
     * 
     * @param type $mailAddress
     * @return \Iris\Users\Identity for fluent interface
     */
    public function setEmailAddress($mailAddress) {
        $this->_mailAddress = $mailAddress;
        return $this;
    }

    /**
     * Accessor set for name
     * 
     * @param string $name
     * @return \Iris\Users\Identity for fluent interface
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * Accessor set for role
     * 
     * @param string $role
     * @return \Iris\Users\Identity for fluent interface
     */
    public function setRole($role) {
        $this->_role = $role;
        return $this;
    }

    /**
     * Accessor set for id
     * 
     * @param string $id
     * @return \Iris\Users\Identity for fluent interface
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /**
     * Accessor set for timer
     * 
     * @param type $_timer
     * @return \Iris\Users\Identity for fluent interface 
     */
    public function setTimer($_timer) {
        $this->_timer = $_timer;
        return $this;
    }

    /**
     * A static syntax for playRole and hasRole_Strict
     * 
     * @param string $expectedRole
     * @param boolean $strict (by default, manages inheritance)
     * @return boolean
     */
    public static function IsA($expectedRole, $strict = FALSE) {
        if ($strict)
            return self::GetInstance()->hasRole_Strict($expectedRole);
        else
            return self::GetInstance()->playRole($expectedRole);
    }

}

