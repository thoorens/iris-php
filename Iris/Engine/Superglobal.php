<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
    public static function GetPost($key = NULL, $default = NULL) {
        return self::_GetData('POST', $key, $default);
    }

    /**
     * Gets a value from the superglobal $_GET
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetGet($key = NULL, $default = NULL) {
        return self::_GetData('GET', $key, $default);
    }

    /**
     * Gets a value from the superglobal $_SERVER
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetServer($key = NULL, $default = NULL) {
        return self::_GetData('SERVER', $key, $default);
    }

    /**
     * Gets a value from the superglobal $_SESSION
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetSession($key = NULL, $default = NULL) {
        \Iris\Users\Session::GetInstance();
        return self::_GetData('SESSION', $key, $default);
    }

    /**
     * Gets a value from the superglobal $_ENV
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetEnv($key = NULL, $default = NULL) {
        return self::_GetData('ENV', $key, $default);
    }

    /**
     * Gets a value from the superglobal $_COOKIE
     * If the variable does not exist, a default value is returned
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function GetCookie($key = NULL, $default = NULL) {
        return self::_GetData('COOKIE', $key, $default);
    }

    /**
     * A common implementation for reading a superglobal
     * 
     * @param string $name
     * @param string $key
     * @param mixed $default
     * @return mixed 
     */
    private static function _GetData($name, $key, $default) {
        switch ($name) {
            case 'SERVER':
                $var = $_SERVER;
                break;
            case 'SESSION':
                \Iris\Users\Session::GetInstance();
                $var = $_SESSION;
                break;
            case 'POST':
                $var = $_POST;
                break;
            case 'GET':
                $var = $_GET;
                break;
            case 'ENV':
                $var = $_ENV;
                break;
            case 'COOKIE':
                $var = $_COOKIE;
                break;
        }
        if (is_null($key)) {
            return $var;
        }
        else {
            return isset($var[$key]) ? $var[$key] : $default;
        }
    }

}
