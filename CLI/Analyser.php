<?php

namespace CLI;

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
 *
 */

/**
 * The main entry for CLI. The command line is analysed and
 * a serie of parameters are initialized from CL options
 * and ini files in ~user/.iris (iris.ini and projects.ini)
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class Analyser {
    /**
     * Possible values for the processor
     */

    const PROJECT = 1;  // manage a project
    const CORECODE = 2; // create Core class
    const SHOW = 3;
    const PARAM = 4;
    const WORKDONE = 5;
    const CODE = 6;
    const PASSWORD = 7;
    const BASE = 8;

    private $_windows;
    private $_linux;

    /**
     * The choiced processor after analysis
     * 
     * @var int
     */
    private $_processor = \NULL;

    /**
     * An associative array with the types of metadata for a project
     * complete description
     * 
     * @var string[]
     */
    private static $_Metadata = array(
        'A' => 'Author',
        'L' => 'License',
        'N' => 'Name',
        'C' => 'Comment'
    );

    /**
     * An associative array with the different options in CLI. The 
     * index is the "short" option name and the value the "long" option.
     * Option terminating with ':' requires a parameter, with '::' has
     * an optional parameter (e.g. h::).
     * 
     * @var string[]
     */
    public static $Functions = [
        'h::' => 'help::',
        's:' => 'show:',
        't' => 'test',
        // projects
        'c:' => 'createproject:',
        'r:' => 'removeproject:',
        'i' => 'interactive',
        'D' => 'docproject',
        'L:' => 'lockproject:',
        'U:' => 'unlockproject:',
        'd:' => 'setdefaultproject',
        'm:' => 'projectmetadata:',
        'a:' => 'applicationdir:',
        'p:' => 'publicdir:',
        'l:' => 'libraryname:',
        'u:' => 'url:',
        // piece of code
        'g' => 'generate',
        'C:' => 'controller:',
        'A:' => 'action:',
        'M:' => 'module:',
        'W' => 'workbench',
        // menus
        'N:' => 'menuname',
        'n:' => 'makemenu',
        // extensions
        'k:' => 'makecore:',
        'K' => 'searchcore',
        // watermaking
        'o:' => 'copyright:',
        'w:' => 'password:',
        // database 
        'B:' => 'database:',
        'b:' => 'selectbase:',
        'I' => 'makedbini',
        'O:' => 'otherdb:',
        'e:' => 'entitygenerate:'
    ];

    /**
     * The choiced action for the processor.
     * 
     * @var string
     */
    private $_option;

    /**
     * The Iris system directory name
     * 
     * @var string 
     */
    private static $_LibraryDir;

    /**
     * The constructor initializes some defaults vars and then 
     * analyses the command line
     * 
     * @param string $libraryDir the directory containing the framework
     */
    public function __construct($libraryDir) {
        if (PHP_OS == 'WINNT') {
            $this->_linux = \FALSE;
        }
        else {
            $this->_linux = \TRUE;
        }
        $this->_windows = !$this->_linux;
        self::$_LibraryDir = $libraryDir;

        $this->_AnalyseCmdLine();
    }

    /**
     * Analyses command line options and parameters and 
     * init respective private variables
     */
    private function _AnalyseCmdLine() {
        $parameters = Parameters::GetInstance();
        // Read options
        $options = $this->cliOptions();
        foreach ($options as $option => $value) {
            switch ($option) {
                // -------------------
                // Project functions
                // -------------------
                case 'c': case 'createproject':
                    // special requirement for createproject
                    if (($this->_linux and $value[0] != '/') or ($this->_windows and $value[1] != ':')) {
                        throw new \Iris\Exceptions\CLIException('The path to project must be absolue');
                    }
                // NO BREAK HERE createproject continues
                case 'd': case 'setdefaultproject':
                case 'L': case 'lockproject':
                case 'U': case 'unlockproject':
                case 'r': case 'removeproject':
                    $dir = str_replace('\\', '/', $value);
                    if ($this->_linux) {
                        // if path given, create name
                        if ($dir[0] == '/') {
                            $name = substr(str_replace('/', '_', $dir), 1);
                        }
                        // if name given, create path (not possible for creating project see above)
                        else {
                            $name = $dir;
                            $dir = "/" . str_replace('_', '/', $name);
                        }
                    }
                    else { // windows
                        if ($dir[1] == ':') {
                            $name = str_replace('/', '_', $dir);
                            $name[1] = '-';
                        }
                        else {
                            $name = $dir;
                            $dir = str_replace('_', '/', $name);
                            $dir[1] = ':';
                        }
                    }
                    $parameters->setProjectName($name);
                    $parameters->setProjectDir($dir);
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    $this->_defaultProject = $name;
                    break;

                // generates portions of project    
                case 'g': case 'generate':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    break;

                // generates portions of work bench
                case 'W': case 'workbench':
                    $parameters->workbench = \TRUE;
                    break;

                // option "interactive" in project creation : metadata are request through
                // a dialog in console
                case 'i': case 'interactive':
                    $parameters->setInteractive(TRUE);
                    break;

                case 'D': case 'doc':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    break;

                // Set public dir  (default is public)   
                case 'p': case 'publicdir':
                    $parameters->setPublicDir($value);
                    break;

                // set application dir (default is application)
                case 'a': case 'applicationdir':
                    $parameters->setApplicationName($value);
                    break;

                // Set library folder name  (default is library)   
                case 'l': case 'libraryname':
                    $parameters->setLibraryName($value);
                    break;

                // set url (default is mysite.local)
                case 'u': case 'url':
                    $parameters->setUrl($value);
                    break;

                // project metadata management
                case 'm': case 'projectmetadata':
                    $this->_treatMetadata($value);
                    break;


                // define module/controller/action
                case 'M':case 'module':
                    $parameters->setModuleName($value);
                    $parameters->setControllerName('index');
                    $parameters->setActionName('index');
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                case 'C': case 'controller':
                    $parameters->setControllerName($value);
                    $parameters->setActionName('index');
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                case 'A': case 'action':
                    $parameters->setActionName($value);
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;

                case 'N': case 'menuname':
                    $parameters->setMenuName($value);
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;

                case 'n': case 'makemenu':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    $parameters->setItems($value);
                    break;

                // database
                case 'B': case 'database':
                    $this->_processor = self::BASE;
                    $this->_option = $option . "_" . $value;
                    break;
                case 'b': case 'selectbase':
                    $this->_processor = self::BASE;
                    $this->_option = $option;
                    $parameters->setDatabase($value);
                    break;
                case 'I': case 'makedbini':
                    $this->_processor = self::BASE;
                    $this->_option = $option;
                    break;
                case 'O': case 'otherdb':
                    $this->_processor = self::BASE;
                    $this->_option = $option;
                    break;
                case 'e': case 'entitygenerate':
                    $this->_processor = self::BASE;
                    $this->_option = $option;
                    $parameters->setEntityName($value);
                    break;
                // make core_Class
                case'k': case 'mkcore':
                    $this->_processor = self::CORECODE;
                    $this->_option = $option;
                    $parameters->setClassName($value);
                    break;

                // recreate the file config/overridden.classes
                case 'K': case 'searchcore':
                    $this->_processor = self::CORECODE;
                    $this->_option = $option;
                    break;

                // watermaking
                case 'o': case 'copyright':
                    $this->_processor = self::CODE;
                    $this->_option = $option;
                    $parameters->setFileName($value);
                    break;

                // password management
                case 'w': case 'password':
                    $this->_processor = self::PASSWORD;
                    $this->_option = $value;
                    break;
                // help screen
                case'h': case 'help':
                    $this->_help($value);
                    break;

                case 's': case 'show':
                    $this->_processor = self::SHOW;
                    $this->_option = $option . '_' . $value;
                    break;

                case 't': case 'test':
                    $this->_processor = self::SHOW;
                    $this->_option = $option;
                    break;
            }
        }
    }

    /**
     * Process the line by using all the parameters and options
     * of the command line.
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    public function process() {
        switch ($this->_processor) {
            // Project management
            case self::PROJECT:
                require_once self::GetIrisLibraryDir() . '/CLI/Project.php';
                $project = new \CLI\Project($this);
                $project->process();
                break;
            // Core class creation (for user customization)
            case self::CORECODE:
                //$newCode['module'] = $this->getModuleName();
//                $config = $this->loadDefaultProject();
//                if ($config == NULL) {
//                    throw new \Iris\Exceptions\CLIException('No active default project, please select one...');
//                }
                require_once self::GetIrisLibraryDir() . '/CLI/CoreMaker.php';
                $code = new \CLI\CoreMaker($this);
                $code->process();
                break;
            // Display status    
            case self::SHOW:
                require_once self::GetIrisLibraryDir() . '/CLI/Project.php';
                $project = new \CLI\Project($this);
                $project->process();
                break;
            case self::CODE:
                require_once self::GetIrisLibraryDir() . '/CLI/Code.php';
                $code = new \CLI\Code($this);
                $code->process();
                break;
            case self::BASE:
                require_once self::GetIrisLibraryDir() . '/CLI/Database.php';
                $base = new \CLI\Database($this);
                $base->process();
                break;
            case self::PASSWORD:
                require_once self::GetIrisLibraryDir() . '/Iris/Users/_Password.php';
                $password = \Iris\Users\_Password::EncodePassword($this->_option);
                echo $password . "\n";
                break;
            // Nothing to do
            case self::WORKDONE:
            case self::PARAM:
                break;
            // CLI not complete
            default:
                throw new \Iris\Exceptions\CLIException("The command line is incomplete or incoherent
                    See iris.php --help");
        }
    }

    public function _treatMetadata($value) {

        if (is_array($value)) {
            foreach ($value as $value1) {
                $this->_treatMetadata($value1);
            }
        }
        else {
            $values = explode('=', $value);
            if (count($values) != 2) {
                throw new \Iris\Exceptions\CLIException('Invalid project metadata in -m/ --projectmetada option.');
            }
            list($parameterName, $parameterValue) = $values;
            if (isset(self::$_Metadata[$parameterName])) {
                $this->_parameters[self::$_Metadata->$parameterName] = $parameterValue;
                $this->_chosenSubOptions .=$parameterName;
            }
        }
    }

    public static function GetIrisLibraryDir() {
        return self::$_LibraryDir;
    }

    /**
     * Accessor for the option selected for processing
     * 
     * @return string
     */
    public function getOption() {
        return $this->_option;
    }

    /**
     * suppress options from command line
     * 
     * @return array
     */
    public function cliOptions() {
        $shorts = '';
        foreach (self::$Functions as $short => $long) {
            $shorts.=$short;
            $longs[] = $long;
        }
        $optionsAarray = \getopt($shorts, $longs);
        // a piece of code adapted from http://php.net/manual/en/function.getopt.php (
        // by François Hill
        foreach ($optionsAarray as $option => $argument) {
            // François Hill does not consider long options, I substitute $dash where he has '-'
            $dash = strlen($option) == 1 ? '-' : '--'; 
            // Look for all occurrences of option in argv and remove if found :
            // ----------------------------------------------------------------
            // Look for occurrences of -o (simple option with no value) or -o<val> (no space in between):
            while ($k = array_search($dash . $option . $argument, $GLOBALS['argv'])) {    // If found remove from argv:
                if ($k) {
                    unset($GLOBALS['argv'][$k]);
                }
            }
            // Look for occurrences of -o=<val> (added in 5.3 after François Hill code publication):
            while ($k = array_search($dash . "$option=" . $argument, $GLOBALS['argv'])) {    // If found remove from argv:
                if ($k) {
                    unset($GLOBALS['argv'][$k]);
                }
            }
            // Look for remaining occurrences of -o <val> (space in between):
            while ($k = array_search($dash . $option, $GLOBALS['argv'])) {    // If found remove both option and value from argv:
                if ($k) {
                    unset($GLOBALS['argv'][$k]);
                    unset($GLOBALS['argv'][$k + 1]);
                }
            }
        }
        // Reindex :
        $GLOBALS['argv'] = array_merge($GLOBALS['argv']);
        // end of code adapted from php.net 
        return $optionsAarray;
    }

    /**
     *
     * @param type $command 
     */
    private function _help($command) {
        $language = \CLI\_Help::DetectLanguage();
        $helpClass = "\\CLI\\Help\\$language";
        $help = new $helpClass(self::$Functions);
        $help->display($command);
    }

    /*
     * Function: Prompt user and get user input, returns value input by user.
     *            Or if return pressed returns a default if used e.g usage
     * $name = promptUser("Enter your name");
     * $serverName = promptUser("Enter your server name", "localhost");
     * Note: Returned value requires validation 
     *
     * @author : Mike Gleaves (Ric)
     * @see http://wiki.uniformserver.com/index.php/PHP_CLI:_User_Input
     * 
     * @param string $promptStr the message for the user
     * @param mixed $defaultVal the default value
     * @return string the value returned (may be the default)
     * @todo This method could be static
     */

    public function promptUser($promptStr, $defaultVal = \FALSE) {
        ;

        if ($defaultVal) {                             // If a default set
            echo $promptStr . "[" . $defaultVal . "] : "; // print prompt and default
        }
        else {                                        // No default set
            echo $promptStr . ": ";                     // print prompt only
        }
        $userVal = chop(fgets(STDIN));                   // Read input. Remove CR
        if (empty($userVal)) {                            // No value. Enter was pressed
            return $defaultVal;                        // return default
        }
        else {                                        // Value entered
            return $userVal;                              // return value
        }
    }

    /**
     * 
     * @param string $promptStr the message for the user
     * @param mixed $defaultVal the default value (a boolean or string or int equivalent)
     * @param string $local localised strings synonymous to TRUE (by def in French)
     * @return boolean the value returned (may be the default)
     */
    public function promptUserLogical($promptStr, $defaultVal = 'FALSE', $local = 'ouivrai') {
        if (is_bool($defaultVal)) {
            $defaultVal = $defaultVal ? 'TRUE' : 'FALSE';
        }
        $value = $this->promptUser($promptStr, $defaultVal);
        return strpos("1\\trueyes" . $local, strtolower($value)) === \FALSE ? \FALSE : \TRUE;
    }

}


