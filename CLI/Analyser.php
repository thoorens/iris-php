<?php

namespace CLI;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
    const PARAMPROJECT = 4;
    const WORKDONE = 5;
    const CODE = 6;
    const PASSWORD = 7;
    const BASE = 8;
    const GLOBALPARAM = 9;
    const NOOPTION = 10;
    const NEWPROJECT = 11;

    /**
     * The current OS is windows
     * @var boolean
     */
    private $_windows;

    /**
     * The current OS is Linux
     * @var boolean
     */
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
    private static $_Metadata = [
        'A' => 'Author',
        'L' => 'License',
        'N' => 'Name',
        'C' => 'Comment'
    ];

    /**
     * An associative array with the different options in CLI. The
     * index is the "short" option name and the value the "long" option.
     * Option terminating with ':' requires a parameter
     *
     * @var string[]
     */
    public static $Functions = [
        // Help is not considered a normal option
        'h::' => 'help::',
        's:' => 'show:',
        '1:' => 'language:',
        't' => 'test',
        // projects
        'c:' => 'createproject:',
        'r:' => 'removeproject:',
        'f' => 'forceproject',
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
        'G:' => 'genericparameter',
        'w:' => 'password:',
        // database
        'B:' => 'database:',
        'b:' => 'selectbase:',
        'I' => 'makedbini',
        'O:' => 'otherdb:',
        'e:' => 'entitygenerate:',
        'v' => 'verbose',
    ];

    /**
     * The choiced action for the processor.
     *
     * @var string
     */
    private $_processingOption;
    private $_newParameters = [];

    /**
     * The Iris system directory name
     *
     * @var string
     */
    private static $_LibraryDir;

    /**
     * The iris program directory name
     * 
     * @var string
     */
    private static $_ProgramDir;

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
        $this->_processor = self::NOOPTION;
        $parameters = Parameters::GetInstance();
        // Read options
        $options = $this->cliOptions();
        $verbose = \FALSE;
        foreach ($options as $option => $value) {
            switch ($option) {
                // -------------------
                // Project functions
                // -------------------
                case 'v' : case 'verbose':
                    $verbose = \TRUE;
                    verboseEchoLine(USERDIR, $verbose);
                    break;
                case 'd': case 'setdefaultproject':
                case 'L': case 'lockproject':
                case 'U': case 'unlockproject':
                case 'r': case 'removeproject':
//                    if (strlen($option) == 1)
//                        $option = isset(self::$Functions[$option]) ? self::$Functions[$option] : self::$Functions[$option . ':'];
//                    verboseEchoLine("Main option will be $option with $value", $verbose);
                    $this->_processingOption = $option;
                    $this->_processor = self::PROJECT;
                    break;

                // ------------------------------------------------------------------------------------------------------------
                // create a project
                case 'c': case 'createproject':
                    // special requirement for createproject
                    if (($this->_linux and $value[0] != '/') or ( $this->_windows and $value[1] != ':')) {
                        throw new \Iris\Exceptions\CLIException('The path to project must be absolue');
                    }
                    verboseEchoLine("Creating a new project $value", $verbose);
                    $this->_manageProjectName($value);
                    $this->_processor = self::NEWPROJECT;
                    break;
                // Set public dir  (default is public)
                case 'p': case 'publicdir':
                    if ($this->_processor != self::NEWPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--public may only come after can complete --createproject option");
                    }
                    $newParameters[Parameters::PUBLICDIR] = $value;
                    verboseEchoLine('Public dir name : ' . $value, $verbose);
                    break;

                // set application dir (default is application)
                case 'a': case 'applicationdir':
                    if ($this->_processor != self::NEWPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--applicationdir may only come after --createproject option");
                    }
                    $newParameters[Parameters::APPLICATIONDIR] = $value;
                    verboseEchoLine('Application dir name : ' . $value, $verbose);
                    break;

                // Set library folder name  (default is library)
                case 'l': case 'libraryname':
                    if ($this->_processor != self::NEWPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--libraryname may only come after --createproject option");
                    }
                    $newParameters[Parameters::LIBRARYDIR] = $value;
                    verboseEchoLine('Library dir name : ' . $value, $verbose);
                    break;

                // set url (default is mysite.local)
                case 'u': case 'url':
                    if ($this->_processor != self::NEWPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--url may only come after  can complete --createproject option");
                    }
                    $newParameters[Parameters::URL] = $value;
                    verboseEchoLine('Module name : ' . $value, $verbose);
                    break;

                case 'f': case 'forceproject':
                    die("Still not developped\n");
                    break;

                // ------------------------------------------------------------------------------------------------------------
                // managing an existing project
                case 'M':case 'module':
                    if ($this->_processor != self::NOOPTION and $this->_processor != self::PARAMPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--module or -M  is not allowed in this context");
                    }
                    $this->_newParameters[Parameters::MODULENAME] = $value;
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    verboseEchoLine('Module name : ' . $value, $verbose);
                    break;
                case 'C': case 'controller':
                    if ($this->_processor != self::NOOPTION and $this->_processor != self::PARAMPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--controller or -C is not allowed in this context");
                    }
                    $this->_newParameters[Parameters::CONTROLLERNAME] = $value;
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    verboseEchoLine('Controller name : ' . $value, $verbose);
                    break;
                case 'A': case 'action':
                    if ($this->_processor != self::NOOPTION and $this->_processor != self::PARAMPROJECT) {
                        throw new \Iris\Exceptions\CLIException("--action or -A is not allowed in this context");
                    }
                    $this->_newParameters[Parameters::ACTIONNAME] = $value;
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    verboseEchoLine('Action name : ' . $value, $verbose);
                    break;
                // generates portions of project
                case 'g': case 'generate':
                    $this->_processor = self::PROJECT;
                    $this->_processingOption = $option;
                    break;
                // generates portions of work bench
                case 'W': case 'workbench':
                    die("Still not developped\n");
                    //$parameters->workbench = \TRUE;
                    break;

                // option "interactive" in project creation : metadata are request through
                // a dialog in console
//                case 'i': case 'interactive':
//                    die("Still not developped\n");
//                    \Debug::Kill($option);
//                    //$parameters->setInteractive(TRUE);
//                    break;
//                case 'D': case 'doc':
//                    die("Still not developped\n");
//                    $this->_processor = self::PROJECT;
//                    $this->_processingOption = $option;
//                    break;
                // project metadata management
//                case 'm': case 'projectmetadata':
//                    $this->_treatMetadata($value);
//                    break;
                // define module/controller/action
//                case 'N': case 'menuname':
//                    die("Still not developped\n");
//                    $parameters->setMenuName($value);
//                    $this->_processingOption = $option;
//                    $this->_processor = self::PARAM;
//                    break;
//
//                case 'n': case 'makemenu':
//                    die("Still not developped\n");
//                    $this->_processor = self::PROJECT;
//                    $this->_processingOption = $option;
//                    $parameters->setItems($value);
//                    break;
                // database
                case 'B': case 'database':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--database or -B is not allowed in this context");
                    }
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option . "_" . $value;
                    break;
                case 'b': case 'selectbase':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--selectbase or -b is not allowed in this context");
                    }
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option;
                    $parameters->setDatabase($value);
                    break;
                case 'I': case 'makedbini':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--makedbini or -I is not allowed in this context");
                    }
                    die("Still not developped\n");
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option;
                    break;
//                case 'O': case 'otherdb':
//                    if ($this->_processor != self::NOOPTION) {
//                        throw new \Iris\Exceptions\CLIException("--otherdb or -O is not allowed in this context");
//                    }
//                    die("Still not developped\n");
//                    $this->_processor = self::BASE;
//                    $this->_processingOption = $option;
//                    break;
                case 'e': case 'entitygenerate':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--entitygenerate or -e is not allowed in this context");
                    }
                    die("Still not developped\n");
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option;
                    $parameters->setEntityName($value);
                    break;

                // make core_Class
                case'k': case 'mkcore':
                    die("Still not developped\n");
                    $this->_processor = self::CORECODE;
                    $this->_processingOption = $option;
                    $parameters->setClassName($value);
                    break;

                // recreate the file config/overridden.classes
                case 'K': case 'searchcore':
                    die("Still not developped\n");
                    $this->_processor = self::CORECODE;
                    $this->_processingOption = $option;
                    break;

                // watermaking
//                case 'o': case 'copyright':
//                    die("Still not developped\n");
//                    $this->_processor = self::CODE;
//                    $this->_processingOption = $option;
//                    $parameters->setFileName($value);
//                    break;
                // generic parameter
                case 'G': case 'genericparameter':
                    die("Still not developped\n");
                    $parameters->setGeneric($value);
                    break;

                // password management
                case 'w': case 'password':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--password must be a unique option followed by its test value");
                    }
                    $this->_processor = self::PASSWORD;
                    $this->_processingOption = $value;
                    break;

                // help screen
                case'h': case 'help':
                    $this->_help($value);
                    break;

                // show projects
                case 's': case 'show':
                    if ($this->_processor != self::NOOPTION) {
                        throw new \Iris\Exceptions\CLIException("--show must be a unique option followed by its test value");
                    }
                    verboseEchoLine('Show the internal state', $verbose);
                    $this->_processor = self::SHOW;
                    $this->_processingOption = $option . '_' . $value;
                    break;

                // define used language
                case 'language':
                    die("Still not developped\n");
                    $this->_processingOption = $value;
                    $this->_processor = self::GLOBALPARAM;
                    $this->_processingOption = $option;
                    break;
                // 
                case 'oldapache':
                    die("Still not developped\n");
                    $this->_processingOption = $value;
                    $this->_processor = self::GLOBALPARAM;
                    $this->_processingOption = $option;
                    break;

                case 't': case 'test':
                    die("Still not developped\n");
                    $this->_processor = self::SHOW;
                    $this->_processingOption = $option;
                    break;
            }
        }
        if ($this->_processor == self::NOOPTION) {
            die("End of analyse: placer l'aide \n");
        }
    }

    /**
     * Transforms /basedir/projectdir to basedir_projectdir
     * or         basedir_projectdir  to /basedir/projectdi
     * @param type $value
     */
    private function _manageProjectName($value) {
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
        $this->_newParameters['projectName'] = $name;
        $this->_newParameters['projectDir'] = $dir;
        $this->_defaultProject = $name;
    }

    /**
     * Process the line by using all the parameters and options
     * of the command line.
     *
     * @throws \Iris\Exceptions\CLIException
     */
    public function processLine() {
        switch ($this->_processor) {
            // Project management
            case self::PROJECT:
                self::Loader('/CLI/Project.php');
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
                self::Loader('/CLI/CoreMaker.php');
                $code = new \CLI\CoreMaker($this);
                $code->process();
                break;
            // Display status
            case self::SHOW:
                self::Loader('/CLI/Project.php');
                $project = new \CLI\Project($this);
                $project->process();
                break;
            case self::CODE:
                self::Loader('/CLI/Code.php');
                $code = new \CLI\Code($this);
                $code->process();
                break;
            case self::BASE:
                self::Loader('/CLI/Database.php');
                $base = new \CLI\Database($this);
                $base->process();
                break;
            case self::PASSWORD:
                $this->_displayPasswordHash();
                break;
            // Nothing to do
            case self::WORKDONE:
                break;
            case self::GLOBALPARAM:
                \Debug::Kill('Global');
                break;
            // CLI not complete
            case self::PARAMPROJECT:
                $currentProject = Parameters::GetInstance()->getCurrentProject();
                iris_debug($currentProject);
            default:
                throw new \Iris\Exceptions\CLIException("The command line is incomplete or incoherent
                    See iris.php --help");
        }
    }

    /**
     * Displays 2 password hashes : <ul>
     * <li> a PHP 5.5 encoded password (simulated if necessary) begiining by '$'
     * <li> an internal IRIS PHP encoded password
     * </ul>
     * 
     */
    private function _displayPasswordHash() {
        self::Loader(['/Iris/Users/_Password.php', '/Iris/SysConfig/Settings.php']);
        echoLine('');
        $password = \Iris\Users\_Password::EncodePassword($this->_processingOption, \Iris\Users\_Password::MODE_IRIS);
        echoLine('Hashed password (internal algorithm): ');
        echoLine($password);
        if (defined('PASSWORD_DEFAULT')) {
            $password = \Iris\Users\_Password::EncodePassword($this->_processingOption, \Iris\Users\_Password::MODE_PHP54);
            echoLine('Hashed password (PHP 5.5 algorithm or emulation): ');
            echoLine($password);
        }
        else {
            echoLine('Your system is unable to generate PHP 5.5 password hash.');
            echoLine('Use the internal /!admin/password URL to generate this type of hashes.');
        }
        echoLine('');
    }

//    public function _treatMetadata($value) {
//
//        if (is_array($value)) {
//            foreach ($value as $value1) {
//                $this->_treatMetadata($value1);
//            }
//        }
//        else {
//            $values = explode('=', $value);
//            if (count($values) != 2) {
//                throw new \Iris\Exceptions\CLIException('Invalid project metadata in -m/ --projectmetada option.');
//            }
//            list($parameterName, $parameterValue) = $values;
//            if (isset(self::$_Metadata[$parameterName])) {
//                $this->_parameters[self::$_Metadata->$parameterName] = $parameterValue;
//                $this->_chosenSubOptions .=$parameterName;
//            }
//        }
//    }

    public static function GetIrisLibraryDir() {
        return self::$_LibraryDir;
    }

    public static function SetIrisLibraryDir($dir) {
        self::$_LibraryDir = $dir;
    }

    public static function GetProgramDir() {
        return self::$_ProgramDir;
    }

    /**
     * Loads all the file listed in the array parameter
     * 
     * @param string[] $files
     */
    public static function Loader($files) {
        if (is_array($files)) {
            foreach ($files as $file) {
                require_once self::$_LibraryDir . $file;
            }
        }
        else {
            require_once self::$_LibraryDir . $files;
        }
    }

    /**
     * Accessor for the option selected for processing
     *
     * @return string
     */
    public function getProcessingOption() {
        return $this->_processingOption;
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

    /**
     * Function: Prompt user and get user input, returns value input by user.
     *            Or if return pressed returns a default if used e.g usage
     * $name = promptUser("Enter your name");
     * $serverName = promptUser("Enter your server name", "localhost");
     * Note: Returned value requires validation
     *
     * @author : Mike Gleaves (Ric) (adapted: introduction of initial value)
     * @see http://wiki.uniformserver.com/index.php/PHP_CLI:_User_Input
     *
     * @param string $promptStr the message for the user
     * @param mixed $defaultVal the default value
     * @param mixedtype $initialVal
     * @return string the value returned (may be the default)
     * @todo This method could be static
     */
    public static function PromptUser($promptStr, $defaultVal = \FALSE, $initialVal = '') {
        if ($defaultVal) {                             // If a default set
            if (!empty($defaultVal)) {
                $defaultVal = $initialVal;
            }
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
     * The same as promptUser but manages boolean values
     *
     * @param string $promptStr the message for the user
     * @param mixed $defaultVal the default value (a boolean or string or int equivalent)
     * @param string $local localised strings synonymous to TRUE (by def in French)
     * @return boolean the value returned (may be the default)
     */
    public static function PromptUserLogical($promptStr, $defaultVal = 'FALSE', $local = 'ouivrai') {
        if (is_bool($defaultVal)) {
            $defaultVal = $defaultVal ? 'TRUE' : 'FALSE';
        }
        $value = strtolower(self::PromptUser($promptStr, \TRUE, $defaultVal));
        return strpos("1\\trueyes" . $local, strtolower($value)) === \FALSE ? \FALSE : \TRUE;
    }

}
