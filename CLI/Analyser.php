<?php

namespace CLI;

define('LINUX_BASE_DIR', '/opt/Iris/library/');
define('WINDOWS_BASE_DIR', 'c:/program/Iris/library/');

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
     * @var array
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
     * @var array
     */
    public static $Functions = array(
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
        'm:' => 'projectmetatdata:',
        'a:' => 'applicationdir:',
        'p:' => 'publicdir:',
        'u:' => 'url:',
        // piece of code
        'g' => 'generate',
        'C:' => 'controller:',
        'A:' => 'action:',
        'M:' => 'module:',
        // menus
        'N:' => 'menuname',
        'n:' => 'makemenu',
        // extensions
        'k:' => 'makecore:',
        'K' => 'searchcore',
        // watermaking
        'o:' => 'copyright:',
        'w:' => 'password:',
        // 
    );

    /**
     * The parameters read or analyzed.
     * 
     * @var array(string) 
     */
    private $_parameters = array();

    /**
     * the default values for the parameters
     * 
     * @var array(string) 
     */
    private $_defaults = array();

    /**
     * The choiced action for the processor.
     * 
     * @var string
     */
    private $_option;

    /**
     * The content of the projects.ini file
     * 
     * @var array(\Iris\SysConfig\Config)
     */
    private $_configs = NULL;

    /**
     * The default project name
     * 
     * @var string 
     */
    private $_defaultProject;

    /**
     * The Iris system directory name
     * 
     * @var string 
     */
    private static $_IrisSystemDir;

    /**
     * The constructor initializes some defaults vars.
     * 
     * @param string $systemDir the directory containing the framework
     */
    public function __construct($argv, $systemDir = LINUX_BASE_DIR) {
        self::$_IrisSystemDir = $systemDir;
        // by default, there is no default base dir for the project 
        $this->_defaults['ProjectDir'] = '';
        // public and application defaults
        $this->_defaults['PublicDir'] = 'public';
        $this->_defaults['ApplicationName'] = 'application';
        // default controller and action
        $this->_defaults['ProjectName'] = '';
        $this->_defaults['Locked'] = 1;
        global $argv;
        // all parameters and commands are in command line
        $this->_AnalyseCmdLine();
        $this->_defaults['CommandLine'] = $argv;
    }

    /**
     * Analyses command line options and parameters and 
     * init respective private variables
     */
    private function _AnalyseCmdLine() {
        // Read options
        $options = $this->cli_options();
        foreach ($options as $option => $value) {
            switch ($option) {
                // -------------------
                // Project functions
                // -------------------
                case 'c': case 'createproject':
                    // special requirement for createproject
                    if ($value[0] != '/') {
                        throw new \Iris\Exceptions\CLIException('The path to project must be absolue');
                    }
                // NO BREAK HERE createproject continues
                case 'd': case 'setdefaultproject':
                case 'L': case 'lockproject':
                case 'U': case 'unlockproject':
                case 'r': case 'removeproject':
                    $dir = $value;
                    // if path given, create name
                    if ($dir[0] == '/') {
                        $name = substr(str_replace('/', '_', $dir), 1);
                    }
                    // if name given, create path (not possible for creating project see above)
                    else {
                        $name = $dir;
                        $dir = "/" . str_replace('_', '/', $name);
                    }
                    $this->_parameters['ProjectName'] = $name;
                    $this->_parameters['ProjectDir'] = $dir;
                    $this->_parameters['ModuleName'] = 'main';
                    $this->_parameters['ControllerName'] = 'index';
                    $this->_parameters['ActionName'] = 'index';
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    $this->_defaultProject = $name;
                    break;

                // generates portions of project    
                case 'g': case 'generate':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    break;

                // option "interactive" in project creation : metadata are request through
                // a dialog in console
                case 'i': case 'interactive':
                    $this->_parameters['Interactive'] = TRUE;
                    break;

                case 'D': case 'doc':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    break;

                // Set public dir  (default is public)   
                case 'P': case 'publicdir':
                    $this->_parameters['PublicDir'] = $value;
                    break;

                // set application dir (default is application)
                case 'a': case 'applicationdir':
                    $this->_parameters['ApplicationName'] = $value;
                    break;

                // set url (default is mysite.local)
                case 'u': case 'url':
                    $this->_parameters['Url'] = $value;
                    break;

                // project metadata management
                case 'm': case 'projectmetadata':
                    $this->_treatMetadata($value);
                    break;


                // define module/controller/action
                case 'M':case 'module':
                    $this->_parameters['ModuleName'] = $value;
                    $this->_parameters['ControllerName'] = 'index';
                    $this->_parameters['ActionName'] = 'index';
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                case 'C': case 'controller':
                    $this->_parameters['ControllerName'] = $value;
                    $this->_parameters['ActionName'] = 'index';
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                case 'A': case 'action':
                    $this->_parameters['ActionName'] = $value;
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                
                case 'N': case 'menuname':
                    $this->_parameters['MenuName'] = $value;
                    $this->_option = $option;
                    $this->_processor = self::PARAM;
                    break;
                
                case 'n': case 'makemenu':
                    $this->_processor = self::PROJECT;
                    $this->_option = $option;
                    $this->_parameters['Items'] = $value;
                    break;


                // make core_Class
                case'k': case 'mkcore':
                    $this->_processor = self::CORECODE;
                    $this->_option = $option;
                    $this->_parameters['ClassName'] = $value;
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
                    $this->_parameters['FileName'] = $value;
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

    public function process() {
        switch ($this->_processor) {
            // Project management
            case self::PROJECT:
                require_once self::GetIrisSystemDir() . '/CLI/Project.php';
                $project = new \CLI\Project($this);
                $project->process();
                break;
            // Core class creation (for user customization)
            case self::CORECODE:
                $newCode['module'] = $this->getModuleName();
                $config = $this->loadDefaultProject();
                if ($config == NULL) {
                    throw new \Iris\Exceptions\CLIException('No active default project, please select one...');
                }
                require_once self::GetIrisSystemDir() . '/CLI/CoreMaker.php';
                $code = new \CLI\CoreMaker($this);
                $code->process();
                break;
            // Display status    
            case self::SHOW:
                require_once self::GetIrisSystemDir() . '/CLI/Project.php';
                $project = new \CLI\Project($this);
                $project->process();
                break;
            case self::CODE:
                require_once self::GetIrisSystemDir() . '/CLI/Code.php';
                $code = new \CLI\Code($this);
                $code->process();
                break;
            case self::PASSWORD:
                require_once self::GetIrisSystemDir() . '/Iris/Users/_Password.php';
                $password = \Iris\Users\_Password::EncodePassword($this->_option);
                echo $password."\n";
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

    public function readParams($paramFile) {
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        $configs = $parser->processFile($paramFile);
        $this->_configs = $configs;
        $mainConfig = $configs['Iris'];
        if (!is_null($mainConfig->DefaultProject)) {
            $this->_defaultProject = $mainConfig->DefaultProject;
        }
        return $configs;
    }

    public function getParameters() {
        return $this->_parameters;
    }

    public function writeParams($paramFile, $configs) {
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        $parser->exportFile($paramFile, $configs);
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
                throw new \Iris\Exceptions\CLIException('Invalid project metadata in -m/--projectmetada option.');
            }
            list($parameterName, $parameterValue) = $values;
            if (isset(self::$_Metadata[$parameterName])) {
                $this->_parameters[self::$_Metadata->$parameterName] = $parameterValue;
                $this->_chosenSubOptions .=$parameterName;
            }
        }
    }

    /**
     *
     * @param type $sendit
     * @return \Iris\SysConfig\Config 
     */
    public function loadDefaultProject($sendit=TRUE) {
        $configs = $this->_configs;
        $defaultProject = $configs['Iris']->DefaultProject;
        if (is_null($defaultProject) or $defaultProject == '') {
            throw new \Iris\Exceptions\CLIException
                    ("No default project. You can define one with iris.php --setdefaultproject\n" .
                    "or create a new one with iris.php --createproject.");
        }
        if (isset($configs[$defaultProject])) {
            foreach ($configs[$defaultProject] as $key => $value) {
                $this->_parameters[$key] = $value;
            }
            // no parameters in command Line
            $this->_parameters['CommandLine'] = array('iris.php');
            if ($sendit) {
                return $configs[$defaultProject];
            }
        }
        else {
            throw new \Iris\Exceptions\CLIException("The default project is broken. Analyse configuration");
        }
    }

    public function createNewConfig() {
        $config = new \Iris\SysConfig\Config($this->getProjectName());
        foreach ($this->_parameters as $key => $value) {
            if ($key != 'CommandLine') {
                $config->$key = $value;
            }
        }
        return $config;
    }

    public static function GetIrisSystemDir() {
        return self::$_IrisSystemDir;
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
    public function cli_options() {
        $shorts = '';
        foreach (self::$Functions as $short => $long) {
            $shorts.=$short;
            $longs[] = $long;
        }
        $options_array = \getopt($shorts, $longs);
        // a piece of code borrowed from http://php.net/manual/en/function.getopt.php (
        // by François Hill
        foreach ($options_array as $o => $a) {
            // Look for all occurrences of option in argv and remove if found :
            // ----------------------------------------------------------------
            // Look for occurrences of -o (simple option with no value) or -o<val> (no space in between):
            while ($k = array_search("-" . $o . $a, $GLOBALS['argv'])) {    // If found remove from argv:
                if ($k)
                    unset($GLOBALS['argv'][$k]);
            }
            // Look for remaining occurrences of -o <val> (space in between):
            while ($k = array_search("-" . $o, $GLOBALS['argv'])) {    // If found remove both option and value from argv:
                if ($k) {
                    unset($GLOBALS['argv'][$k]);
                    unset($GLOBALS['argv'][$k + 1]);
                }
            }
        }
        // Reindex :
        $GLOBALS['argv'] = array_merge($GLOBALS['argv']);
        // end of code from php.net 
        return $options_array;
    }

    /*
     * Accesseurs 
     */

    public function getProjectName() {
        return $this->_getParameter('ProjectName');
    }

    public function getProjectDir() {
        return $this->_getParameter('ProjectDir');
    }

    public function getPublicDir() {
        return $this->_getParameter('PublicDir');
    }

    public function getModuleName() {
        return $this->_getParameter('ModuleName');
    }

    public function getControllerName() {
        return $this->_getParameter('ControllerName');
    }

    public function getActionName() {
        return $this->_getParameter('ActionName');
    }

    public function getApplicationName() {
        return $this->_getParameter('ApplicationName');
    }

    public function getClasseName() {
        return $this->_getParameter('ClassName');
    }

    public function getCommandLine() {
        return $this->_getParameter('CommandLine');
    }

    private function _getParameter($name) {
        if (isset($this->_parameters[$name])) {
            return $this->_parameters[$name];
        }
        if (isset($this->_defaults[$name])) {
            $this->_parameters[$name] = $this->_defaults[$name];
            return $this->_defaults[$name];
        }
        if (!is_null($this->_configs)) {
            $configs = $this->_configs;
            if (isset($configs['Iris']) and isset($configs[$configs['Iris']->DefaultProject])) {
                $defaultProject = $configs['Iris']->DefaultProject;
                $config = $configs[$defaultProject];
                return $config->$name;
            }
        }
        throw new \Iris\Exceptions\CLIException("Parameters $name unknown");
    }

    public function getUrl() {
        if (!isset($this->_parameters['Url'])) {
            $this->_parameters['Url'] = basename($this->getProjectDir()) . '.local';
        }
        return $this->_parameters['Url'];
    }

    /**
     *
     * @return string
     * @todo Implement a true user name management
     */
    public function getAuthor() {
        if (isset($this->_parameters['Author'])) {
            return $this->_parameters['Author'];
        }
        return getenv('USER');
    }

    public function __call($name, $arguments) {
        if (substr($name, 0, 3) == 'put') {
            $key = substr($name, 3);
            $this->_parameters[$key] = $arguments[0];
        }
//        }putUserName($name){
//        $this->_parameters['Author'] = $name;
    }

    /**
     *
     * @return string
     * @todo Implement a true license management
     */
    public function getLicense() {
        if (isset($this->_parameters['License'])) {
            return $this->_parameters['License'];
        }
        return 'not defined';
    }

    public function getComment() {
        if (isset($this->_parameters['Comment'])) {
            return $this->_parameters['Comment'];
        }
        return '';
    }

    /**
     *
     * @return string
     * @todo Implement a true project name management
     */
    public function getDetailedProjectName() {
        if (isset($this->_parameters['DetailedProjectName'])) {
            return $this->_parameters['DetailedProjectName'];
        }
        return $this->getProjectName();
    }

    /**
     *
     * @return array
     */
    public function getConfigs() {
        return $this->_configs;
    }

    /**
     *
     * @param type $command 
     * @todo detect true language
     */
    private function _help($command) {
        $language = 'French';
        $helpClass = "\\CLI\\Help\\$language";
        $help = new $helpClass(self::$Functions);
        $help->display($command);
    }

    /**
     * Only for information, displays the values of the parameters
     */
    public function displayParameters() {
        foreach ($this->_parameters as $key => $value) {
            if ($key !== 'CommandLine') {
                echo "$key : $value\n";
            }
        }
    }

    /*
     * Function: Prompt user and get user input, returns value input by user.
     *          Or if return pressed returns a default if used e.g usage
     * $name = promptUser("Enter your name");
     * $serverName = promptUser("Enter your server name", "localhost");
     * Note: Returned value requires validation 
     *
     * @author : Mike Gleaves (Ric)
     * @see http://wiki.uniformserver.com/index.php/PHP_CLI:_User_Input
     * 
     * @param string $promptStr
     * @param mixe $defaultVal
     * @return string 
     */

    public function promptUser($promptStr, $defaultVal=false) {
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

//========================================= End promptUser ============
}

?>
