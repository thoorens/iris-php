<?php
namespace Iris\Errors;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * This class groups all the error parameters
 * 
 */
class Settings  {

    const TYPE_STANDARD = 1;
    const TYPE_PRIVILEGE = 2;
    const TYPE_FATAL = 3;

    protected static $_GroupName = 'error';

    /**
     * Determines if the handler keep the emited text
     */

    const KEEP = 1;

    /**
     * Determines if the handler uses a log file
     */
    const LOG = 2;

    /**
     * Determines if the handler sends emails
     */
    const MAIL = 4;

    /**
     * Determines if the handler hangs on error (only for test)
     */
    const HANG = 8;

    /**
     * Determines if a final fatal error must be display
     */
    const FATAL = 16;

    /**
     * If true production simulation is activated in error management
     */
    const PRODSIM = 32;

    /**
     * Test if the echoed text must be kept and not erased before 
     * displaying the error messages
     * @var boolean 
     */
    public static $Keep = \FALSE;
    
    /**
     * Disables by default the use of a log file
     * @var boolean
     */
    public static $Log = \FALSE;
    
    /**
     * Sets a dault name for the logfile
     * @var string
     */
    public static $LogFile =  \NULL;
    
    /**
     * By default forbits the handler from sending an email to the administrator
     * @var boolean
     */
    public static $Mail = \FALSE;
    
    /**
     * If true disables the error hang mechanism
     * @var boolean
     */
    public static $Hang = \FALSE;
    
    /**
     * In case of fatal error, a message has to be fired in development
     * @var boolean 
     */
    public static $Fatal = \FALSE;
    
    /**
     * Production mode simulation is disabled by default
     * @var boolean
     */
    public static $ProdSim = \FALSE;
    
    /**
     * Execute Development error in production (for desperate debugging purpose)
     * @var boolean
     */
    protected static $_ExplicitErrorMessages = \FALSE;
    
    /**
     * Default error controller (begins with /)
     * @var sting
     */
    public static $Controller = '/Error';
    
    /**
     * Title for error screen
     * @var string
     */
    public static $Title = 'Iris - Error';
    
    /**
     * The stack level to display
     * @var int 
     */
    public static $StackLevel = \NULL;
    
    /**
     * Default values
     */
//    protected function _init() {
//        $this->_forceSettings();
//    }

    public static function __ClassInit() {
        self::$LogFile = IRIS_ROOT_PATH . '/' . '/log/error.log';
    }
    
    /**
     * Gets from URL optional ERROR parameters and
     * modifies the settings accordingly
     */
    private function _forceSettings() {
        /* @var $data \Iris\SysConfig\_Setting[] */
        $data = \Iris\SysConfig\_Setting::GetGroup(self::$_GroupName);
        if (isset($_GET['ERROR'])) {
            $errorValue = $_GET['ERROR'];
            if (is_numeric($errorValue)) {
                if ($errorValue & self::KEEP)
                    $data['keep']->enable();
                if ($errorValue & self::LOG)
                    $data['log']->enable();
                if ($errorValue & self::MAIL)
                    $data['mail']->enable();
                if ($errorValue & self::HANG)
                    $data['hang']->enable();
                if ($errorValue & self::PRODSIM)
                    $data['productionsimulation']->enable();
            }
            else {
                if ($errorValue == 'NOTFATAL') {
                    $data['fatal']->disable();
                }
                else {
                    $data[strtolower($errorValue)]->enable();
                }
            }
        }
        if (isset($_GET['ERRORSTACK'])) {
            $data['stacklevel']->set($_GET['ERRORSTACK']);
        }
    }

    /* ---------------------------------------------------------------------------- */

    /**
     * Returns the internal flags (for debugging purpose)
     * 
     * @return int
     */
    public static function GetErrorflags() {
        $flags = 0;
        if (self::$Keep)
            $flags += self::KEEP;
        if (self::$Log)
            $flags += self::LOG;
        if (self::$Mail)
            $flags += self::MAIL;
        if (self::$Hang)
            $flags += self::HANG;
        if (self::$Fatal)
            $flags += self::FATAL;
        if (self::$ProdSim)
            $flags += self::PRODSIM;
        return $flags;
    }

    /**
     * Switches to development error display mode until the specified time
     * 
     * @param string $dateLimit the date in YYYY-MM-DD format
     * @param string $timeLimit the time limit in HH:MM:SS format
     */
    public static function ShowErrorOnProd($dateLimit, $timeLimit = '23:59:59'){
        $now = time();
        list($hours, $minutes, $seconds) = explode(':',"$timeLimit:0:0");
        list($year, $month, $day) = explode('-',$dateLimit);
        $date = new \Iris\Time\Date;
        date_default_timezone_set($date->getTimeZone()->getName());
        $limit = mktime($hours, $minutes, $seconds, $month, $day, $year);
        if($now < $limit){
            \Iris\Errors\Settings::$_ExplicitErrorMessages = \TRUE;
        }
        
    }
    
    /**
     * Verifies that a development error display is allowed
     * 
     * @return boolean
     */
    public static function ShowExplicitErrors() {
        return self::$_ExplicitErrorMessages;
    }


}
