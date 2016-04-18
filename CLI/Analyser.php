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
    const SHOW = 3; // show various settings
    const PARAMPROJECT = 4; // preparation of parameters for --generate
    const WORKDONE = 5; // presently not used
    const CODE = 6; // used in --copyright
    const PASSWORD = 7; // used in --password
    const BASE = 8; // used by database management option
//    const GLOBALPARAM = 9; // used by --oldapache option
    const INITIAL = 10; // initial value of processor
    const NEWPROJECT = 11; // used by --createproject option
    const PARAM = 12; // only used in --menuname

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
     * The choiced value for the processor.
     *
     * @var string
     */
    private $_processingOption;

    /**
     * An associative array with the types of metadata for a project
     * complete description
     *
     * @var string[]
     * @deprecated since version 2016
     */
    private static $_Metadata = [
        'A' => 'Author',
        'L' => 'License',
        'N' => 'Name',
        'C' => 'Comment'
    ];

    /**
     * A value for the language interface (put by --french and --english options
     * @var string
     */
    private static $_TempLanguage = \NULL;

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
        'l:' => 'librarydir:',
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
        // special
        'v' => 'verbose',
        '1:' => 'language:', //supposed to be used as a long form option
        '2' => 'context', //supposed to be used as a long form option
        // languages
        'E' => 'english',
        'F' => 'french',
//        'G' => 'german', // future extension
//        'S' => 'spanish',// future extension
//        '3' => 'dutch',// future extension
//        '4' => 'italian',// future extension
    ];

    /**
     * A copy  of Functions to mark all tested options
     * 
     * @var string[]
     */
    public static $Functions_Test = [
        // Help is not considered a normal option
//        'h::' => 'help::',
//        's:' => 'show:',
        't' => 'test',
        // projects
//      'c:' => 'createproject:',
//      'r:' => 'removeproject:',
        'i' => 'interactive',
        'D' => 'docproject',
//      'L:' => 'lockproject:',
//      'U:' => 'unlockproject:',
//      'd:' => 'setdefaultproject',
        'm:' => 'projectmetadata:',
//      'a:' => 'applicationdir:',
//      'p:' => 'publicdir:',
//      'l:' => 'librarydir:',
//      'u:' => 'url:',
        // piece of code
//        'g' => 'generate',
//        'C:' => 'controller:',
//        'A:' => 'action:',
//        'M:' => 'module:',
        'W' => 'workbench',
        // menus
//        'N:' => 'menuname',
//        'n:' => 'makemenu',
        // extensions
//        'k:' => 'makecore:',
//        'K' => 'searchcore',
        // watermaking
        'o:' => 'copyright:',
        'G:' => 'genericparameter',
//      'w:' => 'password:',
        // database
        'B:' => 'database:',
        'b:' => 'selectbase:',
        'I' => 'makedbini',
        'O:' => 'otherdb:',
        'e:' => 'entitygenerate:',
            // special
//      'v' => 'verbose',
//      '1:' => 'language:', //supposed to be used as a long form option
    ];

    /**
     * The unique instance of Parameters, put in Analyser __construct
     * 
     * @var Parameters
     */
    private $_parameters;

    /**
     * The constructor initializes some defaults vars and then
     * analyses the $_parameters variable
     */
    public function __construct() {
        if (PHP_OS == 'WINNT') {
            $this->_linux = \FALSE;
        }
        else {
            $this->_linux = \TRUE;
        }
        $this->_windows = !$this->_linux;
        $this->_parameters = Parameters::GetInstance();
    }

    /**
     * Analyses command line options and parameters and
     * init respective private variables
     */
    public function analyseCmdLine() {
        $this->_processor = self::INITIAL;
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
                    Parameters::GetInstance()->setVerbose(\TRUE);
                    break;
                // ------------------------------------------------------------------------------------------------------------
                // create a project
                case 'c': case 'createproject':
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_PROJECT", 'createproject');
                    }
                    // special requirement for createproject
                    if (($this->_linux and $value[0] != '/') or ( $this->_windows and $value[1] != ':')) {
                        \Messages::Abort("ERR_ABSOLUTEPATH");
                    }
                    verboseEchoLine("Creating a new project $value", $verbose);
                    list($projectName, $projectDir) = $this->manageProjectName($value);
                    $projects = $this->_parameters->getProjects();
                    if (isset($projects[$projectName])) {
                        \Messages::Abort("ERR_NAMEEXISTS", $projectName);
                    }
                    if (file_exists($projectDir)) {
                        \Messages::Abort('ERR_EXISTINGFOLDER', $projectDir);
                    }
                    $this->_parameters->addNewProject($projectName, $projectDir);
                    $this->_processor = self::NEWPROJECT;
                    $this->_processingOption = "$option!$projectName";
                    break;

                case 'd': case 'setdefaultproject':
                case 'L': case 'lockproject':
                case 'U': case 'unlockproject':
                case 'r': case 'removeproject':
                    if ($this->_processor != self::INITIAL) {
                        $longOption = $this->_longOption($option);
                        \Messages::Abort("ERR_PROJECT", $longOption);
                    }
                    list($projectName, $projectDir) = $this->manageProjectName($value);
                    $this->_processor = self::PROJECT;
                    $this->_processingOption = "$option" . "!$projectName";
                    $this->_parameters->setCurrentProjectName($projectName);
                    break;
                // generates portions of project
                case 'g': case 'generate':
                    if ($this->_processor != self::INITIAL and $this->_processor != self::PARAMPROJECT) {
                        $longOption = $this->_longOption($option);
                        \Messages::Abort("ERR_GEN", $longOption);
                    }
                    $this->_processor = self::PROJECT;
                    $this->_processingOption = $option;
                    break;

                // generates portions of work bench
                case 'W': case 'workbench':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_parameters->addParameter(Parameters::WORKBENCH, \TRUE);
                    break;

                // option "interactive" in project creation : metadata are request through
                // a dialog in console
                case 'i': case 'interactive':
                    if ($this->_processor != self::NEWPROJECT) {
                        \Messages::Abort("ERR_INTERACT");
                    }
                    $this->_parameters->addParameter(Parameters::INTERACTIVE, \TRUE);
                    break;

                case 'D': case 'doc':
                    $this->_processor = self::PROJECT;
                    $this->_processingOption = $option;
                    break;

                // Set public dir  (default is public)
                case 'p': case 'publicdir':
                    if ($this->_processor != self::NEWPROJECT) {
                        \Messages::Abort("ERR_PUBLIC");
                    }
                    $this->_parameters->addParameter(Parameters::PUBLICDIR, $value);
                    verboseEchoLine('Public dir name : ' . $value, $verbose);
                    break;

                // set application dir (default is application)
                case 'a': case 'applicationdir':
                    if ($this->_processor != self::NEWPROJECT) {
                        \Messages::Abort("ERR_APPLICATION");
                    }
                    $this->_parameters->addParameter(Parameters::APPLICATIONDIR, $value);
                    verboseEchoLine('Application dir name : ' . $value, $verbose);
                    break;

                // Set library folder name  (default is library)
                case 'l': case 'librarydir':
                    if ($this->_processor != self::NEWPROJECT) {
                        \Messages::Abort("ERR_LIBRARY");
                    }
                    $this->_parameters->addParameter(Parameters::LIBRARYDIR, $value);
                    verboseEchoLine('Library dir name : ' . $value, $verbose);
                    break;

                // set url (default is mysite.local)
                case 'u': case 'url':
                    if ($this->_processor != self::NEWPROJECT) {
                        \Messages::Abort("ERR_URL");
                    }
                    $this->_parameters->addParameter(Parameters::URL, $value);
                    verboseEchoLine('Module name : ' . $value, $verbose);
                    break;

                // project metadata management
//                case 'm': case 'projectmetadata':
//                    $this->_treatMetadata($value);
//                    break;
                // define module/controller/action
                case 'M':case 'module':
                    if ($this->_processor != self::INITIAL and $this->_processor != self::PARAMPROJECT) {
                        \Messages::Abort("ERR_BADMODULE");
                    }
                    $this->_parameters->addParameter(Parameters::MODULENAME, $value);
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    $this->_parameters->useDefault();
                    $this->_parameters->addParameter(Parameters::MODULENAME, $value);
                    verboseEchoLine('Module name : ' . $value, $verbose);
                    break;
                case 'C': case 'controller':
                    if ($this->_processor != self::INITIAL and $this->_processor != self::PARAMPROJECT) {
                        \Messages::Abort("ERR_BADCOONTROLLER");
                    }
                    $this->_parameters->addParameter(Parameters::CONTROLLERNAME, $value);
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    verboseEchoLine('Controller name : ' . $value, $verbose);
                    break;
                case 'A': case 'action':
                    if ($this->_processor != self::INITIAL and $this->_processor != self::PARAMPROJECT) {
                        \Messages::Abort("ERR_BADACTION");
                    }
                    $this->_parameters->addParameter(Parameters::ACTIONNAME, $value);
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAMPROJECT;
                    verboseEchoLine('Action name : ' . $value, $verbose);
                    break;

                case 'N': case 'menuname':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_parameters->addParameter(Parameters::MENUNAME, $value);
                    $this->_processingOption = $option;
                    $this->_processor = self::PARAM;
                    break;

                case 'n': case 'makemenu':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::PROJECT;
                    $this->_processingOption = $option;
                    $this->_parameters->addParameter(Parameters::ITEMS, $value);
                    break;

                // database
                case 'B': case 'database':
                    if (strpos('list List Show show create Create ini', $value) === \FALSE) {
                        \Messages::Abort('ERR_DBPARAM', $value);
                    }
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option . "!" . $value;
                    break;
                case 'b': case 'selectbase':
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_BADSELECTDB");
                    }
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option;
                    $this->_parameters->addParameter(Parameters::DATABASE, $value);
                    break;
                case 'I': case 'makedbini':
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_BADDBINI");
                    }
                    \Messages::Abort("ERR_NOTDEV");
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
                    \Messages::Abort("ERR_NOTDEV");
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_BADENTITY");
                    }
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::BASE;
                    $this->_processingOption = $option;
                    $this->_parameters->addParameter(Parameters::ENTITYNAME, $value);
                    break;
                // make core_Class
                case'k': case 'mkcore':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::CORECODE;
                    $this->_processingOption = $option;
                    $this->_parameters->addParameter(Parameters::CORECLASSNAME, $value);
                    break;

                // recreate the file config/overridden.classes
                case 'K': case 'searchcore':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::CORECODE;
                    $this->_processingOption = $option;
                    break;

                // watermaking
                case 'o': case 'copyright':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::CODE;
                    $this->_processingOption = $option;
                    $this->_parameters->addParameter(Parameters::FILENAME, $value);
                    break;

                // generic parameter
                case 'G': case 'genericparameter':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_parameters->addParameter(Parameters::GENERIC, $value);
                    break;

                // password management
                case 'w': case 'password':
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_PASSWORD");
                    }
                    $this->_processor = self::PASSWORD;
                    $this->_processingOption = $value;
                    break;
                // help screen : will exit in Help
                case'h': case 'help':
                    \Messages::Help($value);
                    break;
                case 'context': case '2':
                    $this->_context();
                    break;
                // show projects
                case 's': case 'show':
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_SHOW");
                    }
                    verboseEchoLine('Show the internal state', $verbose);
                    $this->_processor = self::SHOW;
                    $this->_processingOption = $option . '!' . $value;
                    break 2;

                // modify language in iris.ini
                case 'language': case '1':
                    $languages = \Messages::$Languages;
                    $possibleLanguages = implode(' ',  array_values($languages));
                    if ($this->_processor != self::INITIAL) {
                        \Messages::Abort("ERR_LANG", $possibleLanguages);
                    }
                    if (strpos($possibleLanguages, $value) === \FALSE) {
                        \Messages::Abort("ERR_LISTLANG", $possibleLanguages);
                    }
                    $parameters = Parameters::GetInstance();
                    $parameters->getProject(Parameters::IRISDEF)->Language = $value;
                    $parameters->saveProject(\TRUE);
                    \Messages::Abort("MSG_LANGUAGE", $value);
                    break;
                // modify the language during the execution of the next option
                case 'french': case 'F':
                case 'english': case 'E':
                    self::$_TempLanguage = \Messages::$Languages[$option];
                    break;
//                case 'oldapache':
//                    \Messages::Abort("ERR_NOTDEV");
//                    $this->_processingOption = $value;
//                    $this->_processor = self::GLOBALPARAM;
//                    $this->_processingOption = $option;
//                    break;

                case 't': case 'test':
                    \Messages::Abort("ERR_NOTDEV");
                    $this->_processor = self::SHOW;
                    $this->_processingOption = $option;
                    break;
            }
        }
        if ($this->_processor == self::INITIAL) {
            \Messages::Help('help');
        }
    }

    /**
     * Transforms /basedir/projectdir to basedir_projectdir
     * or         basedir_projectdir  to /basedir/projectdir
     * @param type $value
     */
    public function manageProjectName($value) {
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
        return [$name, $dir];
    }

    /**
     * Process the line by using all the parameters and options
     * of the command line identified in analyseCmd method
     */
    public function processLine() {
        switch ($this->_processor) {
            case self::NEWPROJECT:
                FrontEnd::Loader('/CLI/Project.php');
                $project = new \CLI\Project($this);
                $project->process();
                break;
            // Project management
            case self::PROJECT:
                FrontEnd::Loader('/CLI/Project.php');
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
                FrontEnd::Loader('/CLI/CoreMaker.php');
                $code = new \CLI\CoreMaker($this);
                $code->process();
                break;
            // Display status
            case self::SHOW:
                FrontEnd::Loader('/CLI/Project.php');
                $project = new \CLI\Project($this);
                $project->process();
                break;
            case self::CODE:
                FrontEnd::Loader('/CLI/Code.php');
                $code = new \CLI\Code($this);
                $code->process();
                break;
            case self::BASE:
                FrontEnd::Loader('/CLI/Database.php');
                $base = new \CLI\Database($this);
                $base->process();
                break;
            case self::PASSWORD:
                FrontEnd::Loader(['/Iris/Users/_Password.php', '/Iris/SysConfig/Settings.php']);
                $password = \Iris\Users\_Password::EncodePassword($this->_processingOption, \Iris\Users\_Password::MODE_IRIS);
                echoLine(Parameters::LINE);
                \Messages::Display("MSG_PASSWORDIRIS");
                echoLine($password);
                if (defined('PASSWORD_DEFAULT')) {
                    $password = \Iris\Users\_Password::EncodePassword($this->_processingOption, \Iris\Users\_Password::MODE_PHP54);
                    \Messages::Display("MSG_PASSWORDPHP");
                    echoLine($password);
                }
                else {
                    \Messages::Abort("ERR_PWDINCLI");
                }
                break;
            // Nothing to do
            case self::WORKDONE:
            case self::PARAM:
                break;
//            case self::GLOBALPARAM:
//                die('Global');
//                break;
            // CLI not complete
            default:
                \Messages::Abort("ERR_INCOMPLETE");
        }
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

    /**
     * Accessor for the option selected for processing
     *
     * @return string
     */
    public function getProcessingOption() {
        return $this->_processingOption;
    }

    /**
     * Returns an array with options and values presents in the command line (in $GLOBALS)
     * and suppresses them to clean the $GLOBALS array
     *
     * @return array
     */
    public function cliOptions() {
        $shorts = $longs = '';
        foreach (self::$Functions as $short => $long) {
            $shorts.=$short;
            $longs[] = $long;
        }
        $optionsArray = \getopt($shorts, $longs);
        $this->shift($optionsArray);
        return $optionsArray;
    }

    /**
     * This method, adapted from François Hill, shift all parameters recognized as 
     * options from $GLOBALS
     * 
     * @param type $optionsArray
     */
    public function shift($optionsArray) {
        // a piece of code adapted from http://php.net/manual/en/function.getopt.php (
        // by François Hill
        // 2 changes have been done
        // 
        // - add the long options with --longO  syntax
        // - add the -o=value or --longO=value added in PHP 5.3 after François Hill publication of its code    
        /*
          function shift($options_array)
          {
          foreach( $options_array as $o => $a )
          {
          // Look for all occurrences of option in argv and remove if found :
          // ----------------------------------------------------------------
          // Look for occurrences of -o (simple option with no value) or -o<val> (no space in between):
          while($k=array_search("-".$o.$a,$GLOBALS['argv']))
          {    // If found remove from argv:
          if($k)
          unset($GLOBALS['argv'][$k]);
          }
          // Look for remaining occurrences of -o <val> (space in between):
          while($k=array_search("-".$o,$GLOBALS['argv']))
          {    // If found remove both option and value from argv:
          if($k)
          {    unset($GLOBALS['argv'][$k]);
          unset($GLOBALS['argv'][$k+1]);
          }
          }
          }
          // Reindex :
          $GLOBALS['argv']=array_merge($GLOBALS['argv']);
          }

         * 
         */
        foreach ($optionsArray as $option => $argument) {
            // François Hill does not consider long options, I substitute $dash where he has '-'
            $dash = strlen($option) == 1 ? '-' : '--';
            // Look for all occurrences of option in argv and remove if found :
            // ----------------------------------------------------------------
            // Look for occurrences of -o (simple option with no value) or -o<val> (no space in between):
            while ($k = array_search($dash . $option . $argument, $GLOBALS['argv'])) {    // If found remove from argv:
                if ($k) { // If found remove from argv:
                    unset($GLOBALS['argv'][$k]);
                }
            }
            // Look for occurrences of -o=<val> (added in 5.3 after François Hill code publication):
            while ($k = array_search($dash . "$option=" . $argument, $GLOBALS['argv'])) {    // If found remove from argv:
                if ($k) {// If found remove both option and value from argv:
                    unset($GLOBALS['argv'][$k]);
                }
            }
            // Look for remaining occurrences of -o <val> (space in between):
            while ($k = array_search($dash . $option, $GLOBALS['argv'])) {    // If found remove both option and value from argv:
                if ($k) {// If found remove both option and value from argv:
                    unset($GLOBALS['argv'][$k]);
                    unset($GLOBALS['argv'][$k + 1]);
                }
            }
        }
        // Reindex :
        $GLOBALS['argv'] = array_merge($GLOBALS['argv']);
    }

    /**
     * Determines the interface language to use according
     * to the $_TempLanguage value (modified from options --french or --english
     * or to setting present in iris.ini
     * or the default value set in \Messages::DEFAULT_LANGUAGE (Fr)
     * 
     * @return string
     */
    public static function GetLanguage() {
        $language = self::$_TempLanguage;
        if ($language == \NULL) {
            $parameters = \CLI\Parameters::GetInstance();
            $iris = $parameters->getProject(\CLI\Parameters::IRISDEF);
            if ($iris->Language != '') {
                $language = $iris->Language;
            }
            else {
                $language = \Messages::DEFAULT_LANGUAGE;
            }
        }
        return $language;
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

    /**
     * Converts an option to a longOption value according to $Functions array
     * 
     * @param string $option
     * @return string
     */
    public function _longOption($option) {
        $longOption = $option;
        if (strlen($option) <= 2) {
            if (isset(\CLI\Analyser::$Functions[$option])) {
                $longOption = \CLI\Analyser::$Functions[$option];
            }
            else {
                $longOption = \CLI\Analyser::$Functions[$option . ":"];
            }
        }
        return $longOption;
    }

    /**
     * Will display all the parameter file names
     */
    private function _context() {
        \Messages::Display(Parameters::LINE);
        echoLine('Present library folder :'.IRIS_LIBRARY_DIR);
        echoLine('User folder :'.FrontEnd::GetFilePath('user', 'notest'));
        echoLine('Personal iris folder :'.FrontEnd::GetFilePath('irisdir', 'notest'));
        echoLine('Personal iris parameter file :'.FrontEnd::GetFilePath('iris', 'notest'));
        echoLine('Project file :'.FrontEnd::GetFilePath('project', 'notest'));
        echoLine('Database file :'.FrontEnd::GetFilePath('db', 'notest'));
        \Messages::Abort(Parameters::DBLINE);
    }

}
