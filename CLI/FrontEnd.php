<?php

namespace CLI;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

define('NOTCLI', \FALSE);

/**
 * This class offers some necessary functions for the main program
 */
class FrontEnd {
//    //private $_userDir;
//    static $ParamFileName;

    /**
     * Gets the unique instance of FrontEnd
     * @staticvar type $instance
     * @return \FrontEnd
     */
    public static function GetInstance() {
        static $instance = \NULL;
        if ($instance == \NULL) {
            $instance = new FrontEnd();
        }
        return $instance;
    }

    /**
     * The FrontEnd constructor <ul>
     * <li>detects the running OS
     * <li>determines the user directory
     * <li>reads iris.ini (or create it)
     */
    private function __construct() {
        $this->_preloadClasses();
        //$this->_detectUserDir();
// Analyses iris.ini file
// Warning this file has only two lines e.g.
// [Iris]
// PathIris = /mylibs/iris_library
        $irisParameters = $this->_readIrisParameters();
        if (count($irisParameters) < 2) {
            \Messages::Abort('ERR_BADINI');
        }
        if (trim($irisParameters[0]) != '[Iris]') {
            \Messages::Abort('ERR_BADINI');
        }
        list($para, $value) = explode('=', $irisParameters[1]);
        if (trim($para) != 'PathIris') {
            \Messages::Abort('ERR_BADINI');
        }
//        $this->_parameters = Parameters::GetInstance();
//        $this->_irisInstallationDir = str_replace('"', '', trim($value));
    }

//    public static function GetParamFileName() {
//        return self::$ParamFileName;
//    }

    /**
     * Detects the user home directory 
     */
    private function _detectUserDir() {
        static $userDir;
// Check existence of ini file
        if (PHP_OS == 'WINNT') {
// expect Vista/7/8/10  
            $userDir = winShellVar("localappdata");
            if (!file_exists($userDir)) {
// expect 2000/XP
                $userDir = winShellVar("appdata");
// impossible to locate the user parameter directory
                if (!file_exists($userDir)) {
                    $userDir = \NULL;
                }
            }
        }
        else { // Unix
            $userDir = getenv('HOME');
// impossible to locate the user parameter directory
            if (!file_exists($userDir)) {
                $userDir = \NULL;
            }
        }
        $this->_userDir = $userDir;
    }

    /**
     * Reads the content of the iris.ini file as an array. If necessary creates it 
     * if it does not exist and if the user has provided a directory containing the framework
     * 
     * @return string[]
     */
    private function _readIrisParameters() {
        $IrisPath = IRIS_LIBRARY_DIR;
        self::GetFilePath('irisdir', 'create');
        $irisIni = self::GetFilePath('iris');
        // if not parameter file, create it
        if (!file_exists($irisIni)) {
            if (!file_exists("$IrisPath/CLI/Analyser.php")) {
                \Messages::Abort('ERR_BADIRISFILE');
            }
            $data = <<<STOP
[Iris]
PathIris = $IrisPath

STOP;
            file_put_contents($irisIni, $data);
            \Messages::Abort('MSG_NEWPARAMETERFILE', $irisIni);
        }
//        self::$ParamFileName = $irisIni;
        return file($irisIni);
    }

    /**
     * Loads the required classes (by default all the classes shared by all
     * treatments
     * 
     * @param string[] $files
     */
    private function _preloadClasses() {
        //self::Loader('/CLI/Analyser.php');
        $files = [
            // CLI
            '/CLI/Messages.php',
            '/CLI/Parameters.php',
            '/CLI/Analyser.php',
            '/CLI/_Process.php',
            // Various
            '/Iris/Design/iSingleton.php',
            '/Iris/Engine/tSingleton.php',
            '/Iris/Engine/Memory.php',
            '/Iris/Engine/LogItem.php',
            '/Iris/Engine/Log.php',
            // Parsers
            '/Iris/SysConfig/_Parser.php',
            '/Iris/SysConfig/ConfigIterator.php',
            '/Iris/SysConfig/Config.php',
            '/Iris/SysConfig/IniParser.php',
            // OS
            '/Iris/OS/_OS.php',
            '/Iris/OS/Unix.php',
            '/Iris/OS/Windows.php',
            '/Iris/OS/XP.php',
            // System
            '/Iris/System/Functions.php',
        ];
        self::Loader($files);
    }

    /**
     * Loads all the file listed in the array parameter
     * 
     * @param string[] $files
     */
    public static function Loader($files) {
        if (!is_array($files)) {
            $files = [$files];
        }
        foreach ($files as $file) {
            require_once IRIS_LIBRARY_DIR . $file;
        }
    }

    /**
     * This static method permits to know the distinct parameter file names <ul>
     * <li> user : the user personal
     * <li> irisdir : the .iris directory relative to the user
     * <li> iris : the iris.ini in .iris
     * <li> project : the projects.ini in .iris
     * <li> db : the db.ini in .iris
     * </ul>
     * @param type $fileType the file type
     * @param string $command : may be 'test', 'notest', 'create' (if not exists)
     * @return type
     */
    public static function GetFilePath($fileType, $command = 'notest') {
        $os = \Iris\OS\_OS::GetInstance();
        $userIrisDirectory = $os->getUserHomeDirectory();
        switch ($fileType) {
            case 'user':
                $fileName = $userIrisDirectory;
                break;
            case 'irisdir':
                $fileName = "$userIrisDirectory/.iris";
                break;
            case 'fifo':
                $fileName = "/tmp/.iris.temp";
                break;
            default:
                $fileName = "$userIrisDirectory/.iris/$fileType.ini";
                break;
        }
        switch ($command) {
            case 'test':
                if (!file_exists($fileName)) {
                    switch ($fileType) {
                        case 'user':
                            \Messages::Abort('ERR_NOPERSONNALDIR');
                        case 'iris':
                            \Messages::Abort('ERR_BADIRISFILE');
                        case 'projects':
                            \Messages::Abort('ERR_NOPROJECTFILE');
                        case 'db':
                            \Messages::Abort('ERR_NOBDFILE');
                        default:
                            \Messages::Abort('ERR_BADFILENAME');
                    }
                }
                break;
            case 'create':
                if (!file_exists($fileName)) {
                    $os->mkDir($fileName);
                }
                break;
        }
        return $fileName;
    }

    /**
     *  Permits to test the option manager and the shift in $GLOBALS
     */
    public function testArgs() {
        //require_once IRIS_LIBRARY_DIR.'/CLI/Analyser.php';
        iris_debug($GLOBALS['argv'], \FALSE);
        $analyser = new Analyser();
        $options = $analyser->cliOptions();
        $analyser->shift($options);
        iris_debug($GLOBALS['argv'], \FALSE);
        $last = count($GLOBALS['argv']) - 1;
        echoLine($GLOBALS['argv'][$last]);
        die('OK');
    }

    public function run() {
        try {
// reads all parameters (from command line and from ini file)
//            $parameters = Parameters::GetInstance();
//            $parameters->readParamFile();
            $analyser = new Analyser();
            $analyser->analyseCmdLine();
        }
        catch (Exception $ex) {
            $this->displayException($ex, 1);
        }
// process commands
        try {
            $analyser->processLine();
        }
        catch (Exception $ex) {
            $this->displayException($ex, 2);
        }
    }

    /**
     * Displays an exception message and dies
     * 
     * @param \Exception $ex The thrown exception
     * @param int $type If is 1, specifies the exception occured while reading the parameters
     */
    public function displayException(\Exception $ex, $type) {
        echoLine("IRIS-PHP " . \Iris\System\Functions::IrisVersion() . " CLI error:");
        if ($type == 1) {
            echoLine("Exception during parameters reading");
        }
        echoLine($ex->getMessage() . "");
        \Messages::Abort($type);
    }

}
