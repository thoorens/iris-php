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
 * The mode class permits to know which type of site is running. 
 * It plays an important role in security by prohibiting some
 * methode activation and data display.
 */
final class Mode {

    /**
     * This critical variable defines the way the site react in
     * case of error and manage database
     * (many values, but two main values: PRODUCTION and DEVELOPMENT).
     * 
     * This variable is read from the server settings and never changed.
     * 
     * @var string 
     */
    private static $_SiteMode = \NULL;

    /**
     *
     * @var string 
     */
    private static $_ApplicationMode = \NULL;

    /**
     * Check if the server is not in production. To be sure of the actual
     * mode, use getSiteMode
     * 
     * @param boolean $server if TRUE, evaluates mode according to server only
     * @return boolean : TRUE if not in production or reception, FALSE otherwise
     */
    public static function IsDevelopment($server = \FALSE) {
        return !self::IsProduction($server);
    }

    /**
     * Determine if the server is in production mode
     * 
     * @param boolean $server if TRUE, evaluates mode according to server only
     * @return boolean : TRUE if in production or reception, FALSE otherwise
     */
    public static function IsProduction($server = \FALSE) {
        $mode = self::GetSiteMode($server);
        if ($mode == 'production' or $mode == 'reception') {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    /**
     * Determine the "site mode" (for error treatment and parameters)
     *
     * @param boolean $server if TRUE, returns mode according to server only
     * @return string : the mode name 
     */
    public static function GetSiteMode($server = \FALSE) {
        if ($server or is_null(self::$_ApplicationMode)) {
            return self::$_SiteMode;
        }
        else {
            return self::$_ApplicationMode;
        }
    }

    /**
     *
     * @return string : the mode name
     */
    public static function GetApplicationMode() {
        return self::$_ApplicationMode;
    }

    /**
     * Initialiase the mode of the server, using EXEC_MODE et APPLICATION_ENV
     * This pseudo magic method will be executed one time after the reading of the file.
     * 
     */
    public static function __ClassInit() {
        static $once = \FALSE;
        if (!$once) {
            // EXEC_MODE may be defined as an environment variable or 
            // as a parametre in URL
            $envMode = getenv('EXEC_MODE');
            if (isset($_GET['EXEC_MODE'])) {
                $envMode = $_GET['EXEC_MODE'];
            }
            if (empty($envMode)) {
                $envMode = '';
            }
            // APPLICATION_ENV is defined in the Apache server
            $apacheMode = getenv('APPLICATION_ENV');
            switch ($apacheMode) {
                case 'development':
                    switch ($envMode) {
                        case 'DEV':
                            $mode = 'development';
                            break;
                        case 'PROD':
                            $mode = 'production';
                            break;
                        case 'TEST':
                            $mode = 'testing';
                            break;
                        case 'RECEPT':
                            $mode = 'reception';
                            break;
                        case 'WHAT':
                            print "Exec mode : <br/>";
                            print "DEV - TEST - RECEPT - PROD - WHAT";
                            \Iris\Engine\Debug::Kill('');
                            break;
                        case '':
                            $mode = $apacheMode;
                            break;
                        // User modes
                        default:
                            $mode = $envMode;
                            break;
                    }
                    break;
                case 'reception':
                    if ($envMode == '') {
                        $mode = $apacheMode;
                    }
                    else {
                        $mode = $envMode;
                    }
                    break;
                default:
                    $mode = 'production';
                    break;
            }
            self::$_SiteMode = $mode;
            $once = \TRUE;
        }
    }

    /**
     * An application mode can be defined, so site mode will be ignored
     * 
     * @param string $mode
     */
    public static function SetApplicationMode($mode) {
        self::$_ApplicationMode = $mode;
    }

    /**
     * This pseudo magic method will be run at file reading
     */
    

}

// This class is loaded by include, so we must call __classInit by hand.
Mode::__ClassInit();
