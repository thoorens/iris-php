#! /usr/bin/env php
<?php
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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * This is the main entry for the command line interpreter (CLI)
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $
 */
define('BADINI', "Your param file does not seem to be a valid one. Please check your configuration according to the manual instructions.\n");
define('IRIS_USER_PARAMFOLDER', '/.iris/');
define('IRIS_USER_INI', 'iris.ini');
define('IRIS_PROJECT_INI', 'projects.ini');
define('NOTCLI', \FALSE);



/* ============================================================================
 * C L A S S E S   A N D   F U N C T I O N S
 * ============================================================================ */

/**
 * A modified version of \Iris\Engine\Debug. The &lt;pre> tag have been 
 * removed. Static methods ErrorBox and ErroBoxDie have been removed too.
 */
class Debug {

    /**
     * Display a var_dump between <pre> tags
     * 
     * @param mixed $var : a var or text to dump
     */
    public static function Dump($var) {
        var_dump($var);
    }

    /**
     * Displays a var_dump tags and optionaly dies
     *
     * @param mixed $var A printable message or variable
     * @param string $dieMessage
     * @param int $traceLevel If called from iris_debug, trace level is 1 instead of 0
     */
    public static function DumpAndDie($var, $dieMessage = NULL) {
        if (is_null($dieMessage)) {
            $trace = debug_backtrace();
            $dieMessage = sprintf("Debugging interrupt in file \033[1m%s\033[0m  line \033[1m%s\033[0m\n", $trace[0]['file'], $trace[0]['line']);
        }
        self::Dump($var);
        self::Kill($dieMessage);
    }

//@todo move to another class
    public static function Kill($message = NULL) {
        die($message); // the only authorized die in programm
    }

    public static function showSections($configs) {
        echoLine("Sections in configs");
        foreach ($configs as $section => $content) {
            echoLine("[$section]");
        }
    }

}

/**
 * A debugging tool using Debug static methods
 * 
 * @param mixed $var
 * @param boolean $die By default the function ends the program
 * @param type $Message An optional message
 */
function iris_debug($var, $die = TRUE, $Message = NULL) {
    if ($die) {
        \Debug::DumpAndDie($var, $Message, 1);
    }
    else {
        \Debug::Dump($var);
    }
}

function echoLine($message) {
    echo $message . "\n";
}

/**
 * A not very clean way to obtain a shell var value in Windows
 * 
 * @param type $var
 * @return type
 */
function winShellVar($var) {
    return str_replace("\n", "", shell_exec("echo %$var%"));
}

/**
 * This class offers some necessary functions for the main program
 */
class FrontEnd {

    private $_userDir;
    private $_isRunningInWindow;
    private $_paramFileName;
    private $_irisInstallationDir;

    public static function GetInstance() {
        static $instance = \NULL;
        if (is_null($instance)) {
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
        $this->_osDetect();
        $this->_detectUserDir();

        // Analyses iris.ini file
        // Warning this file has only two lines e.g.
        // [Iris]
        // PathIris = /mylibs/iris_library
        $iniContent = $this->_readOrCreateIrisDotIni();
        if (count($iniContent) < 2) {
            die(BADINI);
        }
        if (trim($iniContent[0]) != '[Iris]') {
            die(BADINI);
        }
        list($para, $value) = explode('=', $iniContent[1]);
        if (trim($para) != 'PathIris') {
            die(BADINI);
        }
        $this->_irisInstallationDir = str_replace('"', '', trim($value));
    }

    public function getParamFileName() {
        return $this->_paramFileName;
    }

    /**
     * Returns the name of the directory containing the iris framework
     * 
     * @return string
     */
    public function getIrisInstallationDir() {
        return $this->_irisInstallationDir;
    }

    /**
     * Dectects if PHP has been compiled by Windows or not
     */
    private function _osDetect() {
        if (PHP_OS == 'WINNT') {
            $this->_isRunningInWindow = \TRUE;
        }
        else {
            $this->_isRunningInWindow = \FALSE;
        }
    }

    /**
     * Detects the user home directory 
     */
    private function _detectUserDir() {
        // Check existence of ini file
        if ($this->_isRunningInWindow) {
            // expect Vista/7/8  
            $this->_userDir = winShellVar("localappdata");
            if (!file_exists($this->_userDir)) {
                // expect 2000/XP
                $this->_userDir = winShellVar("appdata");
                // impossible to locate the user parameter directory
                if (!file_exists($this->_userDir)) {
                    $this->_userDir = \NULL;
                }
            }
        }
        else { // Unix
            $this->_userDir = getenv('HOME');
            // impossible to locate the user parameter directory
            if (!file_exists($this->_userDir)) {
                $this->_userDir = \NULL;
            }
        }
    }

    /**
     * Reads the content of the iris.ini file as an array. If necessary creates it 
     * if it does not exist and if the user has provided a directory containing the framework
     * 
     * @return string[]
     */
    private function _readOrCreateIrisDotIni() {
        $userDir = $this->_userDir;
        if (is_null($userDir)) {
            echoLine("iris.php is not able to find where to read or write your parameter files.");
            die("IRIS PHP CLI will not be functional on your system, sorry.\n");
        }
        $paramDir = "$userDir" . IRIS_USER_PARAMFOLDER;
        if (!file_exists($paramDir)) {
            echoLine("Creating $paramDir");
            mkdir($paramDir);
        }
        $this->_paramFileName = "$paramDir" . IRIS_USER_INI;
        // if not parameter file, create it
        if (!file_exists($this->_paramFileName)) {
            if ($GLOBALS['argc'] == 1) {
                echoLine("You must supply the path to your Iris-PHP installation to init your parameter file");
                die("before beeing able to use this program. See documentation if necessary.\n");
            }
            else {
                $this->_irisInstallationDir = $GLOBALS['argv'][1];
                if (!file_exists("$this->_irisInstallationDir/CLI/Analyser.php")) {
                    die("$this->_irisInstallationDir does not seem to be a valid Iris-PHP installation directory.\n");
                }
                $data = <<<STOP
[Iris]
PathIris = $this->_irisInstallationDir
STOP;
                file_put_contents($this->_paramFileName, $data);
                echoLine("Parameter file $this->_paramFileName has been created");
                die("Now you can use this program (iris.php --help for help)\n");
            }
        }
        return file($this->_paramFileName);
    }

    /**
     * Loads the required classes (by default all the classes shared by all
     * treatments
     * 
     * @param string[] $classes
     */
    public function preloadClasses($classes = []) {
        // by default loads some classes (with dependencies)
        if (count($classes) == 0) {
            $classes = [
                // Engine
                'Iris/Engine/Debug',
                // CLI
                'CLI/Parameters',
                'CLI/Analyser',
                'CLI/_Process',
                'CLI/_Help', 'CLI/Help/French', 'CLI/Help/English',
                // Various
                'Iris/Engine/tSingleton',
                'Iris/Engine/Memory',
                'Iris/Log',
                'Iris/Exceptions/_Exception',
                'Iris/Exceptions/CLIException',
                'Iris/Exceptions/NotSupportedException',
                // Parsers
                'Iris/SysConfig/_Parser',
                'Iris/SysConfig/ConfigIterator',
                'Iris/SysConfig/Config',
                'Iris/SysConfig/IniParser',
                // OS
                'Iris/OS/_OS',
                'Iris/OS/Unix',
                'Iris/OS/Windows',
                'Iris/OS/XP',
                // System
                'Iris/System/Functions',
            ];
        }
        foreach ($classes as $classe) {
            include_once "$this->_irisInstallationDir/$classe.php";
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
        die($type);
    }

}

/* ============================================================================
 * M A I N   P R O G R A M
 * ============================================================================ */

// Gets a unique instance of the front end controller
$frontEnd = FrontEnd::GetInstance();
// Loads the classes shared by all treatment
$frontEnd->PreloadClasses();
\Iris\OS\_OS::GetInstance();

try {

// reads all parameters (from command line and from ini file)
    $analyser = new CLI\Analyser($frontEnd->getIrisInstallationDir());
    $parameters = CLI\Parameters::GetInstance();
    $parameters->readParams($frontEnd->getParamFileName());
}
catch (Exception $ex) {
    $frontEnd->displayException($ex, 1);
}

// process commands
try {
    $analyser->processLine();
}
catch (Exception $ex) {
    $frontEnd->displayException($ex, 2);
}






    