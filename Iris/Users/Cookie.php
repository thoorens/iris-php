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
 * Implements a cookie. It will be send if possible, otherwise, it will be 
 * added to the session for later use.
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */
class Cookie {

    /**
     * The name of the cookie (mandatory)
     * 
     * @var string
     */
    private $_name;
    private $_value = '';
    private $_expire = 0;
    private $_path = '/';
    private $_domain = \NULL;
    private $_secure = \FALSE;
    private $_httponly = \TRUE;

    const SESSION_VARNAME = 'UNSENT_COOKIES';

    /**
     * A brand new cookie, in preparation
     */
    const STATE_NEW = 0b1;

    /**
     * The cookie has been sent
     */
    const STATE_SENT = 0b10;

    /**
     * The cookie has been received from the client
     */
    const STATE_RECEIVED = 0b100;

    /**
     * The cookie could not be sent. It has been added in Session manager for
     * later use.
     */
    const STATE_DELAYED = 0b1000;

    /**
     * The cookie has been modified since last save
     */
    const STATE_DIRTY = 0b10000;

    /**
     *
     * @var \Iris\System\BitField
     */
    private $_status;

    /**
     * The constructor requires a name for the cookie and can add a value
     * 
     * @param string $name The cookie name
     * @param mixed $value The cookie value
     */
    public function __construct($name, $value = \NULL, $new = \TRUE) {
        $this->_status = new \Iris\System\BitField(0);
        $this->_name = $name;
        if (!is_null($value)) {
            $this->setValue($value);
        }
        if ($new) {
            $this->_status->setBit(self::STATE_NEW);
        }
    }

    /**
     * 
     * @param type $value
     * @return \Iris\Users\Cookie
     */
    public function setValue($value) {
        $this->_value = $value;
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     * @param int $expire
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setExpire($expire) {
        $this->_expire = $expire;
        $this->_markDirty();
        return $this;
    }

    public function setDuration($delay, $unity = 'month') {
        $end = new \Iris\Time\TimeDate();
        switch ($unity) {
            case 'hour':
                $end->addHour($delay);
                break;
            case 'day':
                $end->addDay($delay);
                break;
            case 'year':
                $end->addYear($delay);
                break;
            case 'month':
            default:
                $end->addMonth($delay);
                break;
        }
        $this->_expire = $end->getUnixTime();
        $this->_markDirty();
        return $this;
    }

    public function setPath($path) {
        $this->_path = $path;
        $this->_markDirty();
        return $this;
    }

    public function setDomain($domain) {
        $this->_domain = $domain;
        $this->_markDirty();
        return $this;
    }

    public function setSecure($secure) {
        $this->_secure = $secure;
        return $this;
    }

    public function setHttponly($httponly) {
        $this->httponly = $httponly;
        $this->_markDirty();
        return $this;
    }

    public function send() {
        $sent = setcookie($this->_name, $this->_value, $this->_expire, $this->_path, $this->_domain, $this->_secure, $this->_httponly);
        if ($sent) {
            $this->_status->setBit(self::STATE_SENT);
            $this->_status->unsetBit(self::STATE_DELAYED);
        }
        else {
            $this->sessionStore();
        }
    }

    public function sessionStore() {
        $this->_status->setBit(self::STATE_DELAYED);
        $this->_status->unsetBit(self::STATE_SENT);
        $string = serialize($this);
        $_SESSION[self::SESSION_VARNAME][$this->_name] = $string;
    }

    private function _markDirty() {
        $this->_status->setBit(self::STATE_DIRTY);
    }

    public function save() {
        if ($this->_status->hasBit(self::STATE_DIRTY)) {
            if ($this->_status->hasBit(self::STATE_DELAYED)) {
                $this->store();
            }
            else {
                $this->send();
            }
            $this->_status->unsetBit(self::STATE_DIRTY);
        }
    }

    public static function LoadUnsentCookies() {
        $unsentCookies = \Iris\Engine\Superglobal::GetSession(self::SESSION_VARNAME, []);
        $_SESSION[self::SESSION_VARNAME] = [];
        foreach ($unsentCookies as $cookie) {
            /* @var $newCookie Cookie */
            $newCookie = unserialize($cookie);
            $newCookie->save();
        }
    }

}
