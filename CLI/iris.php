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
define('CLI', 'CLI');
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
        echo "<pre>\n";
        var_dump($var);

        echo "</pre>\n";
    }

    /**
     * Display a var_dump between <pre> tags and die
     * 
     * @param mixed $var : a var or text to dump
     */
    public static function DumpAndDie($var, $dieMessage = NULL) {
        if (is_null($dieMessage)) {
            echo "<hr\>\n";
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

function getParamFile($userDir) {

    global $argc;
    global $argv;
    if(is_null($userDir)){
        echo "Iris PHP is not able to find where where to read or write your parameter files\.n";
        die ('IRIS PHP CLI will not be functional on your system, sorry.');
    }
    $paramDir = "$userDir/.iris";
    if (!file_exists($paramDir)) {
        echo "Creating $paramDir\n";
        mkdir($paramDir);
    }
    $paramFile = "$paramDir/iris.ini";
    // if not parameter file, create it
    if (!file_exists($paramFile)) {
        if ($argc == 1) {
            echo "You must supply the path to your Iris-PHP installation to init your parameter file\n";
            die("before beeing able to use this program. See documentation if necessary.\n");
        }
        else {
            $pathIris = $argv[1];
            if (!file_exists("$pathIris/CLI/Analyser.php")) {
                die("$pathIris does not seem to be a valid Iris-PHP installation directory.\n");
            }
            $data = <<<STOP
[Iris]
PathIris = $pathIris
STOP;
            file_put_contents($paramFile, $data);
            echo "Parameter file $paramFile has been created\n";
            die("Now you can use this program (iris.php --help for help)\n");
        }
    }
    return [$paramFile, file($paramFile)];
}

/* End of functions
 * =============================================================================
 */

// in which OS has PHP been compiled ?
if (PHP_OS == 'WINNT') {
    $windows = \TRUE;
}
else {
    $windows = \FALSE;
}

// Check existence of ini file
if ($windows) {
    // expect Vista/7/8  
    $userDir = shellvar("localappdata");
    if (!file_exists($userDir)) {
        // expect 2000/XP
        $userDir = shellvar("appdata");
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

list($paramFile, $iniFile) = getParamFile($userDir);
$paramDir = dirname($paramFile);

// Analyse INI file
if (count($iniFile) < 2) {
    die(BADINI);
}
if (trim($iniFile[0]) != '[Iris]') {
    die(BADINI);
}
list($para, $value) = explode('=', $iniFile[1]);
if (trim($para) != 'PathIris') {
    die(BADINI);
}
$pathIris = str_replace('"', '', trim($value));

// load some classes (with dependences)
$cli = CLI;
$classes = [
    $cli . '/Analyser', $cli . '/_Process',
    $cli . '/_Help', $cli . '/Help/French',
    'Iris/Engine/tSingleton', 'Iris/Engine/Memory', 'Iris/Log', 'Iris/Exceptions/_Exception', 'Iris/Exceptions/CLIException',
    'Iris/SysConfig/_Parser', 'Iris/SysConfig/ConfigIterator', 'Iris/SysConfig/Config', 'Iris/SysConfig/IniParser',
    'Iris/OS/_OS', 'Iris/OS/Unix', 'Iris/OS/Windows','Iris/OS/XP',
    'Iris/Exceptions/NotSupportedException',
    ];
foreach ($classes as $classe) {
    //echo "LOADING $pathIris/$classe\n";
    include_once "$pathIris/$classe.php";
}

\Iris\OS\_OS::GetInstance();

try {

// read command line
    global $argv;
    $analyser = new CLI\Analyser($argv, $pathIris);

// read INI file more cleverly
    $analyser->readParams($paramFile);
}
catch (Exception $ex) {
    echo "Exception during parameters reading\n";
    echo $ex->getMessage() . "\n";
    die(1);
}
// process commands
try {
    if (file_exists($paramDir . "/projects.ini")) {
        $analyser->readParams($paramDir . "/projects.ini");
    }
    $analyser->process();
}
catch (Exception $ex) {
    echo "IRIS-PHP " . IRISVERSION . " CLI error:\n";
    echo $ex->getMessage() . "\n";
    die(2);
}
// update INI file
//$analyser->writeParams($paramDir . "/projects.ini");






    