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
 * Session manager : starts it and manages session variables
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
final class Session implements \Iris\Design\iSingleton{

    private static $_Instance = null;

    /**
     * Returns the unique session, creating it
     * if necessary, as à side effect, an identity
     * is created (using data from a previous session
     * or from scratch)
     * 
     * @return Session 
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            $instance = new Session();
            self::$_Instance = $instance;
            if (isset($_SESSION['identity'])) {
                $identity = $_SESSION['identity'];
            }
            else {
                $identity = NULL;
            }
            new Identity($identity);
        }
        return self::$_Instance;
    }

    private function __construct() {
        session_start();
    }

    public function __get($name){
        return $this->getValue($name);
    }
    
    public function getValue($name, $default='') {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        else {
            if ($default === '') {
                throw new \Iris\Exceptions\SessionException("Session variable unknown : $name");
            }
            else {
                return $default;
            }
        }
    }

    public function __isset($name) {
        return isset($_SESSION[$name]);
    }

    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }

    /**
     * The static function returns always TRUE unless the client has
     * no javascript technology and a proper detection of this fact
     * has been set up through the use of {javascriptDetector} in head part
     * of the main view/layout file and the /jsTest.php in the public directory
     * root.
     * 
     * @return boolean
     */
    public static function JavascriptEnabled(){
        $session = self::GetInstance();
        return !($session->getValue('iris_nojavascript', FALSE));
    }
    
    
    public static function IsSessionActive() {
        $id = session_id();
        return strlen($id) > 0;
    }
}

?>
