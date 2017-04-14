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
 * Session manager : starts it and manages session variables
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
final class Session implements \Iris\Design\iSingleton{

    private static $_Instance = \NULL;

    /**
     * Returns the unique session, creating it
     * if necessary, as à side effect, an identity
     * is created (using data from a previous session
     * or from scratch)
     * 
     * @return Session 
     */
    public static function GetInstance() {
        if (self::$_Instance === \NULL) {
            $instance = new Session();
            self::$_Instance = $instance;
            $identity = $instance->getValue('identity', \NULL);
//            if (isset($_SESSION['identity'])) {
//                $identity = $_SESSION['identity'];
//            }
//            else {
//                $identity = NULL;
//            }
            Identity::CreateInstance($identity);
            Cookie::LoadUnsentCookies();
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


