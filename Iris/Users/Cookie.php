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
 * Implements a cookie. It will be sent if possible, otherwise, it will be 
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

    /**
     * The value associated to the cookie
     * @var string
     */
    private $_value = '';

    /**
     *
     * @var type 
     */
    private $_expire = 0;
    
    /**
     *
     * @var type 
     */
    private $_path = '/';
    
    /**
     *
     * @var type 
     */
    private $_domain = \NULL;
    
    /**
     *
     * @var type 
     */
    private $_secure = \FALSE;
    
    /**
     *
     * @var type 
     */
    private $_httponly = \TRUE;

    /**
     * The index for the session array containing unsent cookies
     */
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
     * A set accessor for the cookie value
     * 
     * @param type $value
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setValue($value) {
        $this->_value = $value;
        $this->_markDirty();
        return $this;
    }

    /**
     * A set accezsor for the cookie expiration status
     * 
     * @param int $expire
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setExpire($expire) {
        $this->_expire = $expire;
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     * @param type $delay
     * @param type $unity
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setDuration($delay, $unity = 'month') {
        $endTime = new \Iris\Time\TimeDate();
        switch ($unity) {
            case 'hour':
                $endTime->addHour($delay);
                break;
            case 'day':
                $endTime->addDay($delay);
                break;
            case 'year':
                $endTime->addYear($delay);
                break;
            case 'month':
            default:
                $endTime->addMonth($delay);
                break;
        }
        $this->_expire = $endTime->getUnixTime();
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     * @param type $path
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setPath($path) {
        $this->_path = $path;
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     * @param type $domain
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setDomain($domain) {
        $this->_domain = $domain;
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     * @param type $secure
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setSecure($secure) {
        $this->_secure = $secure;
        return $this;
    }

    /**
     * 
     * @param type $httponly
     * @return \Iris\Users\Cookie for fluent interface
     */
    public function setHttponly($httponly) {
        $this->httponly = $httponly;
        $this->_markDirty();
        return $this;
    }

    /**
     * 
     */
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

    /**
     * 
     */
    public function sessionStore() {
        $this->_status->setBit(self::STATE_DELAYED);
        $this->_status->unsetBit(self::STATE_SENT);
        $string = serialize($this);
        $_SESSION[self::SESSION_VARNAME][$this->_name] = $string;
    }

    /**
     * 
     */
    private function _markDirty() {
        $this->_status->setBit(self::STATE_DIRTY);
    }

    /**
     * Tries to send the cookie
     */
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

    /**
     * 
     */
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
