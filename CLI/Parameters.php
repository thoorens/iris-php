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
 * The main data repository for CLI
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 *
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class Parameters {

    // project
    /**
     * One parameter of the project
     */
    const PROJECTNAME = 'ProjectName';
    const PROJECTDIR = 'ProjectDir';
    // project structure
    /**
     * Project library folder name
     */
    const LIBRARYDIR = 'LibraryDir'; // project library name
    /**
     * Project library folder name
     */
    const APPLICATIONDIR = "ApplicationDir";

    /**
     * Project application folder name
     */
    const PUBLICDIR = 'PublicDir';

    /**
     * Project public folder name
     */
    const URL = 'Url';
    // code details
    /**
     * The module name parameter
     */
    const MODULENAME = 'ModuleName';

    /**
     * The controller name parameter
     */
    const CONTROLLERNAME = 'ControllerName';

    /**
     * The action name parameter
     */
    const ACTIONNAME = 'ActionName';
    const WORKBENCH = "Workbench";
    const INTERACTIVE = "Interactive";
    const ITEMS = 'Items';
    const DATABASE = 'Database';
    const ENTITYNAME = 'EntityName';
    const CORECLASSNAME = 'CoreClassName';
    const FILENAME = 'FileName';
    const MENUNAME = 'MenuName';
    const IRISDEF = 'Iris';
    const FRAGMENTNAME = 'FragmentName';

    /**
     * Default value for the project database (default none)
     */
    const DB_NONE = "==NONE==";
    // display
    const LINE = '------------------------------------------------------------------------------------';
    const DBLINE = "====================================================================================";

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
     * The parameters read in command line.
     *
     * @var string[]
     */
    private static $_Parameters = [];

    /**
     * The content of the projects.ini file as an array of Config
     *
     * @var \Iris\SysConfig\Config[]
     */
    private $_projects = [];

    /**
     * If TRUE indicates that the project file has to be saved
     * @var boolean
     */
    private $_dirty = \FALSE;

    /**
     * A parameter to permit verbose display
     * @var boolean
     */
    private $_verbose = \FALSE;

    /**
     * The default project Name
     * @var boolean
     */
    private $_currentProjectName = '';

    /**
     * Gets the unique instance of Parameters class
     * 
     * @staticvar type $instance
     * @return Parameters
     */
    public static function GetInstance() {
        static $instance = \NULL;
        if ($instance == \NULL) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * A classical constructor for singleton.
     * It reads or creates the default project file
     *
     */
    private function __construct() {
        $this->_readParamFile();
    }

    /**
     * Reads an ini file and analyses its content in a array of configs
     * If the file does not exist, creates a config array with an initial 'Iris' element with <ul>
     * <li> the iris library path
     * <li> no default project
     * <li> the default language (by default French)
     * </ul>
     */
    private function _readParamFile() {
        //$this->_verbose = \TRUE;
        $paramFile = FrontEnd::GetFilePath('projects', 'notest');
        if (!file_exists($paramFile)) {
            verboseEchoLine("create an unnamed unique project", $this->_verbose);
            $config0 = new \Iris\SysConfig\Config(Parameters::IRISDEF);
            $config0->PathIris = IRIS_LIBRARY_DIR;
            $config0->defaultProject = '';
            $config0->language = \Messages::DEFAULT_LANGUAGE;
            $this->setCurrentProjectName('');
            $this->_projects = [Parameters::IRISDEF => $config0];
            $this->_dirty = \TRUE;
        }
        else {
            $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
            $this->_projects = $parser->processFile($paramFile);
            $this->setCurrentProjectName($this->_projects[Parameters::IRISDEF]->DefaultProject);
            $this->_dirty = \FALSE;
        }
    }

    /**
     * Only for information, displays the values of the parameters
     * 
     * @param Config $project
     */
    public function displayParameters($project) {
        foreach ($project as $key => $value) {
            if ($key !== 'CommandLine') {
                echo "$key : $value\n";
            }
        }
    }

    /**
     * Each time a parameter is modified, a dirty mark becomes true
     */
    public function markDirty() {
        $this->_dirty = \TRUE;
    }

    /**
     * A quick way to access a internal parameter value (for special purposes)
     * If it does not exist, return false
     *
     * @param string $name
     * @return mixed
     * @deprecated since version number
     */
    public function __get($name) {
        die("__get$name");
        return $this->_getValue($name, \FALSE);
    }

    /**
     * A quick way to define a internal parameter
     *
     * @param string $name
     * @param mixed $value
     * @deprecated since version number
     */
    public function __set($name, $value) {
        die("__set$name");
        $this->_parameters[$name] = $value;
    }

    /**
     * (Re)creates a ini file with the content of an array of configs
     *
     * @param type $paramFile the ini file to create/overwrite
     * @param \Iris\SysConfig\Config[] $configs An array of configs
     */
    public function writeParams($paramFile, $configs) {
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        $parser->exportFile($paramFile, $configs);
    }

//    /**
//     * Tests the existence and loads the projects from the ini files. If there
//     * is a default read it. If parameter is TRUE and no project exists, throws
//     * an exception.
//     *
//     * @param boolean $error
//     */
//    public function requireProjects($error = \TRUE) {
//        return;
//        if (is_null($this->_projects)) {
//            die('OOOOO');
//            $iniFile = self::GetProjectIniName();
//            if (\TRUE) {
//                \Messages::Abort("ERR_NOPROJECTFILE);
//            }
//            $configs = $this->readParamFile();
//            $this->_projects = $configs;
//            $mainConfig = $configs[Parameters::IRISDEF];
//            $defaultProject = $mainConfig->DefaultProject;
//            if (!is_null($defaultProject) and $defaultProject != '') {
//                $this->_activeProject = $configs[$defaultProject];
//            }
//        }
//    }
//    public static function GetProjectIniName() {
//        $userDir = \Iris\OS\_OS::GetInstance()->getUserHomeDirectory();
//        $iniFile = "$userDir" . "IRIS_USER_PARAMFOLDER;
//        if (!file_exists($iniFile)) {
//            \Messages::Abort("ERR_NOPROJECTFILE);
//        }
//        return $iniFile;
//    }

    /**
     * Tries to read the ini file and to find a default project, otherwise
     * fails.
     */
    public function requireDefaultProject() {
        $this->getDefaultProjectName();
    }

//    public function createNewConfig() {
//        $config = new \Iris\SysConfig\Config($this->getProjectName());
////        $config->ProjectName = $this->getProjectName();
////        $config->ProjectDir = $this->getProjectDir();
////        $config->PublicDir = $this->getPublicDir();
////        $config->ApplicationName = $this->getApplicationName();
////        $config->Url = $this->getUrl();
////        $config->Database = $this->getDatabase();
////        $config->ModuleName = $this->getModuleName();
////        $config->ControllerName = $this->getControllerName();
////        $config->ActionName = $this->getActionName();
////        $config->Locked = $this->getLocked();
////        $this->setSpecial($config, 'License');
////        $this->setSpecial($config, 'Author');
////        $this->setSpecial($config, 'DetailedProjectName');
////        $this->setSpecial($config, 'Comment');
//        return $config;
//    }

    /**
     * 
     * @param type $config
     * @param type $key
     * @deprecated since version number
     */
    private function setSpecial($config, $key) {
        if (isset($this->_parameters[$key])) {
            $getter = "get$key";
            $config->$key = $this->$getter();
        }
    }

    /*
     * Accessors
     */

    /**
     * Accessor get for the project name
     * @return string
     */
    public function getProjectName() {
        return $this->_getValue(self::PROJECTNAME);
    }

    /**
     * 
     * @return type
     */
    public static function GetParameters() {
        return self::$_Parameters;
    }

    /**
     * Accessor get for the project directory
     * @return string
     */
    public function getProjectDir() {
        return $this->_getValue(self::PROJECTDIR);
    }

    /**
     * Accessor get for the public directory name
     * @return string
     */
    public function getPublicDir() {
        return $this->_getValue(self::PUBLICDIR, 'public');
    }

    /**
     * Accessor get for the public directory name
     * @return string
     */
    public function getLibraryDir() {
        return $this->_getValue(self::LIBRARYDIR, 'library');
    }

    /**
     * Accessor get for the current module name
     * @return string
     */
    public function getModuleName() {
        return $this->_getValue(self::MODULENAME, 'main');
    }

    /**
     * Accessor get for the current controller name
     * @return string 
     */
    public function getControllerName() {
        return $this->_getValue(self::CONTROLLERNAME, 'index');
    }

    /**
     * Accessor get for the current action name
     * @return string
     */
    public function getActionName() {
        return $this->_getValue(self::ACTIONNAME, 'index');
    }

    /**
     * Accessor get for the current menu name
     * @return string
     */
    public function getMenuName() {
        return $this->_getValue(self::MENUNAME);
    }

    /**
     * Accessor get for the current entity name
     * @return string
     */
    public function getEntityName() {
        return $this->_getValue(self::ENTITYNAME, 'tests');
    }

    /**
     * Accessor get for the application name
     * @return string
     */
    public function getApplicationName() {
        $value = $this->_getValue(self::APPLICATIONDIR, 'application');
        //iris_debug($value);
        return $value;
    }

    /**
     * Accessor get for the current database name
     * @return string
     */
    public function getDatabase($old = \FALSE) {
        return $this->_getValue(self::DATABASE, self::DB_NONE, $old);
    }

//    public function getLocked() {
//        return $this->_getParameter('Locked', 1);
//    }

    /**
     * Accessor get for the defaut class name
     * @return string
     */
    public function getClasseName() {
        return $this->_getValue('ClassName');
    }

    /**
     * Accessor get for parameters in command line
     * @return string
     */
    public function getCommandLine() {
        $last = count($GLOBALS['argv']) - 1;
        if (isset($GLOBALS['argv'][$last])) {
            return $GLOBALS['argv'][$last];
        }
        else {
            return '';
        }
    }

    /**
     * Reads a value for a parameter, if possible in command line options
     * otherwise in the current project (if it exists)
     * The third parameter permits to access the project value, even if a parameter
     * has been set.
     *
     * @param string $name The name of the parameter
     * @param mixed $default An optional default value
     * @param boolean $projectValue If true, ignore the set parameter
     * @return mixed The value as knowm by the system
     */
    private function _getValue($name, $default = \NULL) {
        $value = $default;
        $projectName = $this->getDefaultProjectName();
        if (!isset($this->_projects[$projectName])) {
            die("NO DEF in _getValue : $name");
        }
        else {
            $defaultProject = $this->getProject();
            $foundValue = $defaultProject->$name;
            if ($foundValue == "") {
                $value = $default;
            }
            else {
                $value = $foundValue;
            }
        }
        return $value;
    }

    public function setValue($paramName, $value) {
        $defaultProject = $this->getProject();
        $defaultProject->$paramName = $value;
        $this->_dirty = \TRUE;
    }

    /**
     * Returns the local URL (by default the name of the base dir + .local
     *
     * @return string
     */
    public function getUrl() {
        return $this->_getValue('Url', basename($this->getProjectDir()) . '.local', \TRUE);
    }

    /**
     * Accessor get for the code fragment to generate
     * @return string
     */
    public function getFragmentName() {
        return $this->_getValue('FragmentName', 'fragment_name');
    }

    /**
     * 
     * @return string
     */
    public function getFileName() {
        return $this->_getValue('EntityName', 'tests');
    }

    /**
     * Returns a generic parameter (option -G)
     * 
     * @return string
     */
    public function getGeneric() {
        if (isset($this->_parameters['Generic'])) {
            return $this->_parameters['Generic'];
        }
        else {
            return \NULL;
        }
    }

    /**
     * 
     * @return type
     */
    public function getInteractive() {
        $interactive = \FALSE;
        if ($this->getProject()->{self::INTERACTIVE}) {
            $interactive = \TRUE;
        }
        return $interactive;
    }

    /**
     * Magic method to manage the parameters
     *
     * @param string $name
     * @param mixed[] $arguments
     * @deprecated since version number
     */
    public function __call($name, $arguments) {
        die($name);
//        if (substr($name, 0, 3) == 'set') {
//            $key = substr($name, 3);
//            $this->_parameters[$key] = $arguments[0];
//        }
//        elseif (substr($name, 0, 3) == 'put') {
//            $key = substr($name, 3);
//            $this->_parameters[$key] = $arguments[0];
//            //iris_debug($this->_parameters);
//        }
//        else {
//            throw new \Iris\Exceptions\CLIException("No function like $name in class Parameter");
//        }
    }

    /**
     *
     * @return string
     * @todo Implement a true license management
     * @deprecated since version number
     */
    public function getLicense() {
        if (isset($this->_parameters['License'])) {
            return $this->_parameters['License'];
        }
        return 'not defined';
    }

    /**
     *
     * @return string
     * @todo Implement a true project name management
     * @deprecated since version number
     */
    public function getDetailedProjectName() {
        if (isset($this->_parameters['DetailedProjectName'])) {
            return $this->_parameters['DetailedProjectName'];
        }
        return $this->getProjectName();
    }

    /**
     * Get the array of project configs
     * 
     * @return [\Iris\SysConfig\Config]
     */
    public function getProjects() {
        return $this->_projects;
    }

    /**
     * 
     * @param type $projectName
     * @return type
     */
    public function getProject($projectName = '') {
        $errorCode = "ERR_UNKNOWNPROJECT";
        if ($projectName == '') {
            $projectName = $this->_currentProjectName;
            $errorCode = "ERR_NODEFPROJECT";
        }
        if (!isset($this->_projects[$projectName])) {
            \Messages::Abort($errorCode, $projectName);
        }
        return $this->_projects[$projectName];
    }

    /**
     * Adds a new project and makes it current
     * 
     * @param string $projectName
     * @param string $projectDir
     */
    public function addNewProject($projectName, $projectDir) {
        $config = new \Iris\SysConfig\Config($projectName);
        $config->{Parameters::PROJECTDIR} = $projectDir;
        $config->{Parameters::PROJECTNAME} = $projectName;
        // default values
        $config->{Parameters::APPLICATIONDIR} = 'application';
        $config->{Parameters::PUBLICDIR} = 'public';
        $config->{Parameters::URL} = \basename($projectDir) . '.local';
        $config->{Parameters::LIBRARYDIR} = 'library';
        $config->{Parameters::DATABASE} = self::DB_NONE;
        $this->_projects[$projectName] = $config;
        $this->setCurrentProjectName($projectName);
        $this->markDirty();
    }

    /**
     * Seter for the current project name
     * 
     * @param string $projectName The new current project name
     */
    public function setCurrentProjectName($projectName) {
        verboseEchoLine("Current project is now $projectName", $this->_verbose);
        $this->_currentProjectName = $projectName;
    }

    /**
     * Tries to return the default project name, if not existent returns a
     * empty string or emits a fatal message according to the $error parameter
     * 
     * @param boolean $error If TRUE, 
     * @return string
     */
    public function getDefaultProjectName($error = \TRUE) {
        if ($this->_currentProjectName == \NULL) {
            $currentName = $this->_projects[Parameters::IRISDEF]->DefaultProject;
            if (!isset($this->_projects[$currentName])) {
                if ($error) {
                    \Messages::Abort("ERR_NODEFPROJECT");
                }
                else {
                    return '';
                }
            }
            $this->_currentProjectName = $currentName;
        }
        return $this->_currentProjectName;
    }

    public function addParameter($parameterName, $value) {
        $project = $this->getProject();
        $project->$parameterName = $value;
        $this->markDirty();
    }

//    public function getDefaultProjectName(){
//        return $this->_projects[Parameters::IRISDEF]->DefaultProject;
//    }
    /**
     * Save all projects
     */
    public function saveProject($force = \FALSE) {
        if ($this->_dirty or $force) {
            $fileName = FrontEnd::GetFilePath('projects');
            $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
            $parser->exportFile($fileName, $this->_projects);
            $this->_dirty = \FALSE;
        }
    }

//    public static function Add($index, $value) {
//        self::$_Parameters[$index] = $value;
//    }

    public function setVerbose($value) {
        $this->_verbose = $value;
    }

    public function useDefault() {
        $this->_currentProjectName = $this->_projects[self::IRISDEF]->DefaultProject;
    }

    public function removeProject($projectName) {
        unset($this->_projects[$projectName]);
        if ($this->_projects[self::IRISDEF]->ProjectName == $projectName) {
            $this->_projects[self::IRISDEF]->ProjectName = '';
            \Messages::Display("MSG_NODEF");
        }
        $this->_dirty = \TRUE;
        $this->saveProject();
    }

    public function debugProject() {
        echoLine($this->getProjectDir());
        echoLine($this->getPublicDir());
        echoLine($this->getApplicationName());
        echoLine($this->getLibraryDir());
        echoLine($this->getUrl());
        die('----');
    }

//    public static function GetProjectIniPath(){
//        static $fileName = \NULL;
//        if($fileName == \NULL){
//           $userDir = \Iris\OS\_OS::GetInstance()->getUserHomeDirectory(); 
//           $fileName = $userDir . "IRIS_USER_PARAMFOLDER .Project::IRIS_PROJECT_INI ;
//        }
//        return $fileName;
//    }
}
