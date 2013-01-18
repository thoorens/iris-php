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
 * @copyright 2012 Jacques THOORENS
 */

/**
 * This is the main entry for the command line interpreter (CLI)
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $
 */
define('IRISVERSION', '0.9 - beta');
define('BADINI', "Your param file does not seem to be a valid one. Please check your configuration according to the manual instructions.\n");

/**
 * To prevent a loading of Loader which contains \Iris\Engine\Debug
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
     * Display a var_dump between <pre> tags and die
     * 
     * @param mixed $var : a var or text to dump
     */
    public static function DumpAndDie($var, $dieMessage = NULL) {
        if (is_null($dieMessage)) {
            $trace = debug_backtrace();
            $dieMessage = sprintf('Debugging interrupt in file <b> %s </b> line %s', $trace[0]['file'], $trace[0]['line']);
        }
        self::Dump($var);
        self::Kill($dieMessage);
    }

//@todo move to another class
    public static function Kill($message = NULL) {
        die($message); // the only authorized die in programm
    }

    public static function showSections($configs) {
        echo "Sections in configs\n";
        foreach ($configs as $section => $content) {
            echo "[$section]\n";
        }
    }

}

// end if class Debug

/* =============================================================
 * FUNCTIONS
 */

/**
 * 
 * @param type $var
 * @param type $die
 * @param type $Message
 */
function iris_debug($var, $die = TRUE, $Message = NULL) {
    if ($die) {
        \Debug::DumpAndDie($var, $Message, 1);
    }
    else {
        \Debug::Dump($var);
    }
}

/**
 * 
 * @param type $var
 * @return type
 */
function shellvar($var) {
    return str_replace("\n", "", shell_exec("echo %$var%"));
}

class FrontEnd {

    private $_userDir;
    private $_windows;
    private $_paramFileName;
    private $_irisInstallationDir;

    public function __construct() {
        $this->_osDetect();
        $this->_detectUserDir();
        $this->readIni();
    }

    public function readIni() {
        // Analyses iris.ini file
        // Warning this file has only two lines e.g.
        // [Iris]
        // PathIris = /mylibs/iris_library
        $iniContent = $this->getIrisIniContent();
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
        return $this->_irisInstallationDir;
    }

    public function getParamFileName() {
        return $this->_paramFileName;
    }

    public function getIrisInstallationDir() {
        return $this->_irisInstallationDir;
    }

    /**
     * Dectects if PHP has been compiled by Windows or not
     */
    private function _osDetect() {
        if (PHP_OS == 'WINNT') {
            $this->_windows = \TRUE;
        }
        else {
            $this->_windows = \FALSE;
        }
    }

    /**
     * Detects the user home directory 
     */
    private function _detectUserDir() {
        // Check existence of ini file
        if ($this->_windows) {
            // expect Vista/7/8  
            $this->_userDir = shellvar("localappdata");
            if (!file_exists($this->_userDir)) {
                // expect 2000/XP
                $this->_userDir = shellvar("appdata");
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
     * Reads the content of the iris.ini file
     * @return array(string)
     */
    public function getIrisIniContent() {
        $userDir = $this->_userDir;
        if (is_null($userDir)) {
            echo "iris.php is not able to find where to read or write your parameter files.\n";
            die("IRIS PHP CLI will not be functional on your system, sorry.\n");
        }
        $paramDir = "$userDir/.iris";
        if (!file_exists($paramDir)) {
            echo "Creating $paramDir\n";
            mkdir($paramDir);
        }
        $this->_paramFileName = "$paramDir/iris.ini";
        // if not parameter file, create it
        if (!file_exists($this->_paramFileName)) {
            if ($GLOBALS['argc'] == 1) {
                echo "You must supply the path to your Iris-PHP installation to init your parameter file\n";
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
                echo "Parameter file $this->_paramFileName has been created\n";
                die("Now you can use this program (iris.php --help for help)\n");
            }
        }
        return file($this->_paramFileName);
    }

    public function preloadClasses($classes = array()) {
        // loads some classes (with dependences)
        if (count($classes) == 0) {
            $classes = [
                // CLI
                'CLI/Parameters',
                'CLI/Analyser',
                'CLI/_Process',
                'CLI/_Help', 'CLI/Help/French',
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
            ];
        }
        foreach ($classes as $classe) {
            include_once "$this->_irisInstallationDir/$classe.php";
        }
    }

    public function displayException(\Exception $ex, $type) {
        echo "IRIS-PHP " . IRISVERSION . " CLI error:\n";
        if ($type == 1) {
            echo "Exception during parameters reading\n";
        }
        echo $ex->getMessage() . "\n";
        die($type);
    }

}

$frontEnd = new FrontEnd();
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
//    if (file_exists($homeDotIris . "/projects.ini")) {
//        $analyser->readProjects($homeDotIris);
//    }
    $analyser->process();
}
catch (Exception $ex) {
    $frontEnd->displayException($ex, 2);
}






    