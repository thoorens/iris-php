<?php

namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 *  A way to access superglobals, with security and default value
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
abstract class Superglobal {

    /**
     * Gets a value from the superglobal $_POST
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetPost($key = \NULL, $default = \NULL, $filter = \NULL) {
        return self::_GetData(\INPUT_POST, $key, $default, $filter);
    }

    /**
     * Gets a value from the superglobal $_GET
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetGet($key = \NULL, $default = \NULL, $filter = \NULL) {
        return self::_GetData(\INPUT_GET, $key, $default, $filter);
    }

    /**
     * Gets a value from the superglobal $_SERVER
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @param int $filter Special filter to treat content for security
     * @return mixed
     */
    public static function GetServer($key = \NULL, $default = \NULL, $filter = \NULL) {
        return self::_GetData(\INPUT_SERVER, $key, $default, $filter);
    }

    /**
     * Gets a value from the superglobal $_SESSION
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @param int $filter Special filter to treat content for security
     * @return mixed
     */
    public static function GetSession($key = \NULL, $default = \NULL, $filter = \NULL) {
        \Iris\Users\Session::GetInstance();
        return self::_GetData(\INPUT_SESSION, $key, $default, $filter);
    }

    /**
     * Gets a value from the superglobal $_ENV
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @param int $filter Special filter to treat content for security
     * @return mixed
     */
    public static function GetEnv($key = \NULL, $default = \NULL, $filter = \NULL) {
        return self::_GetData(\INPUT_ENV, $key, $default, $filter);
    }

    /**
     * Gets a value from the superglobal $_COOKIE
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetCookie($key = \NULL, $default = \NULL, $filter = \NULL) {
        return self::_GetData(\INPUT_COOKIE, $key, $default, $filter);
    }

    /**
     * 
     * @param type $key
     * @param type $default
     * @param int $filter Special filter to treat content for security
     * @throws \Iris\Exceptions\InternalException
     */
    public static function GetRequest($key = \NULL, $default = \NULL, $filter = \NULL) {
        throw new \Iris\Exceptions\InternalException('GetRequest is not implemented and many developpers say $_REQUEST should never be used instead of $_FET, $_POST and $_COOKIES');
    }

    /**
     * A common implementation for reading a superglobal
     * 
     * @param string $name
     * @param string $key
     * @param mixed $default
     * @return mixed 
     */
    private static function _GetData($type, $key, $default, $filter = \NULL) {
        if ($filter === \NULL) {
            $filter = \FILTER_DEFAULT;
        }
        if ($type === \INPUT_SESSION) {
            \Iris\Users\Session::GetInstance();
                    $var = $_SESSION;
        }
        else {
            $test = \filter_input(\INPUT_GET, $key, $filter);
            switch ($type) {
                case \INPUT_SERVER:
                    $var = $_SERVER;
                    break;
                case \INPUT_SESSION:
                    \Iris\Users\Session::GetInstance();
                    $var = $_SESSION;
                    break;
                case \INPUT_POST:
                    $var = $_POST;
                    break;
                case \INPUT_GET:
                    $var = $_GET;
                    break;
                case \INPUT_ENV:
                    $var = $_ENV;
                    break;
                case \INPUT_COOKIE:
                    $var = $_COOKIE;
                    break;
            }
        }
        if (is_null($key)) {
            return $var;
        }
        else {
            return isset($var[$key]) ? $var[$key] : $default;
        }
    }

}
