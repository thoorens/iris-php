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
 * The main data repository for CLI
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 *
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class Parameters {

    //@todo leave them
    private $_windows;
    private $_linux;

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
     * The parameters read n command line.
     *
     * @var string[]
     */
    private $_parameters = array();

    /**
     * The content of the projects.ini file
     *
     * @var \Iris\SysConfig\Config[]
     */
    private $_projects = NULL;

    /**
     * The saved parameters for the current project
     *
     * @var \Iris\SysConfig\Config
     */
    private $_currentProject = \NULL;

    /**
     *
     * @staticvar type $instance
     * @return Parameters
     */
    public static function GetInstance() {
        static $instance = \NULL;
        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * A classical constructor for singleton
     *
     */
    private function __construct() {
        
    }

    /**
     * Only for information, displays the values of the parameters
     */
    public function displayParameters() {
        foreach ($this->_currentProject as $key => $value) {
            if ($key !== 'CommandLine') {
                echo "$key : $value\n";
            }
        }
    }

    /**
     * A quick way to access a internal parameter value (for special purposes)
     * If it does not exist, return false
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->_getParameter($name, \FALSE);
    }

    /**
     * A quick way to define a internal parameter
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value) {
        $this->_parameters[$name] = $value;
    }

    /**
     * Reads an ini file and analyses its content in a array of configs
     * If file does not exist, creates a config array with an initial 'Iris' element
     *
     * @param string $paramFile the ini file to parse
     * @return array(\Iris\SysConfig\Config)
     */
    public function readParams($paramFile) {
        if (!file_exists($paramFile)) {
            $config0 = new \Iris\SysConfig\Config('Iris');
            $config0->PathIris = \FrontEnd::GetInstance()->getIrisInstallationDir();
            $config0->defaultProject = \NULL;
            return ['Iris' => $config0];
        }
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        return $parser->processFile($paramFile);
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

    /**
     * Tests the existence and loads the projects from the ini files. If there
     * is a default read it. If parameter is TRUE and no project exists, throws
     * an exception.
     *
     * @param boolean $error
     * @throws \Iris\Exceptions\CLIException
     */
    public function requireProjects($error = \TRUE) {
        if (is_null($this->_projects)) {
            $userDir = \Iris\OS\_OS::GetInstance()->getUserHomeDirectory();
            $iniFile = "$userDir" . IRIS_USER_PARAMFOLDER . IRIS_PROJECT_INI;
            if (!file_exists($iniFile) and $error) {
                throw new \Iris\Exceptions\CLIException("
You seem to have no project in your environment.
Create one with 'iris.php --createproject");
            }
            else {
                $configs = $this->readParams($iniFile);
                $this->_projects = $configs;
                $mainConfig = $configs['Iris'];
                $defaultProject = $mainConfig->DefaultProject;
                if (!is_null($defaultProject) and $defaultProject != '') {
                    $this->_currentProject = $configs[$defaultProject];
                }
            }
        }
    }

    /**
     * Tries to read the ini file and to find a default project, otherwise
     * fails.
     *
     * @throws \Iris\Exceptions\CLIException
     */
    public function requireDefaultProject() {
        $this->requireProjects();
        if (is_null($this->_currentProject)) {
            throw new \Iris\Exceptions\CLIException("
You seem to have no default project in your environment.
Select one with 'iris.php --setdefaultproject'
or create one with 'iris.php --createproject'.");
        }
    }

    public function createNewConfig() {
        $config = new \Iris\SysConfig\Config($this->getProjectName());
        $config->ProjectName = $this->getProjectName();
        $config->ProjectDir = $this->getProjectDir();
        $config->PublicDir = $this->getPublicDir();
        $config->ApplicationName = $this->getApplicationName();
        $config->Url = $this->getUrl();
        $config->Database = $this->getDatabase();
        $config->ModuleName = $this->getModuleName();
        $config->ControllerName = $this->getControllerName();
        $config->ActionName = $this->getActionName();
        $config->Locked = $this->getLocked();
        $this->setSpecial($config, 'License');
        $this->setSpecial($config, 'Author');
        $this->setSpecial($config, 'DetailedProjectName');
        $this->setSpecial($config, 'Comment');
        return $config;
    }

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
        return $this->_getParameter('ProjectName');
    }

    /**
     * Accessor get for the project directory
     * @return string
     */
    public function getProjectDir() {
        return $this->_getParameter('ProjectDir');
    }

    /**
     * Accessor get for the public directory name
     * @return string
     */
    public function getPublicDir() {
        return $this->_getParameter('PublicDir', 'public');
    }

    /**
     * Accessor get for the public directory name
     * @return string
     */
    public function getLibraryName() {
        return $this->_getParameter('LibraryName', 'library');
    }

    /**
     * Accessor get for the current module name
     * @return string
     */
    public function getModuleName() {
        return $this->_getParameter('ModuleName', 'main');
    }

    /**
     * Accessor get for
     * @return string the current controller name
     */
    public function getControllerName() {
        return $this->_getParameter('ControllerName', 'index');
    }

    /**
     * Accessor get for the current action name
     * @return string
     */
    public function getActionName() {
        return $this->_getParameter('ActionName', 'index');
    }

    public function getMenuName() {
        
    }

    public function getEntityName() {
        return $this->_getParameter('EntityName', 'tests');
    }

    /**
     * Accessor get for the application name
     * @return string
     */
    public function getApplicationName() {
        return $this->_getParameter('ApplicationName', 'application');
    }

    public function getDatabase($old = \FALSE) {
        return $this->_getParameter('Database', '==NONE==', $old);
    }

    public function getLocked() {
        return $this->_getParameter('Locked', 1);
    }

    /**
     * Accessor get for the defaut
     * @return string
     */
    public function getClasseName() {
        return $this->_getParameter('ClassName');
    }

    /**
     * Accessor get for parameters in command line
     * @return string
     */
    public function getCommandLine() {
        return $this->_getParameter('CommandLine', $GLOBALS['argv']);
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
     * @throws \Iris\Exceptions\CLIException
     */
    private function _getParameter($name, $default = \NULL, $projectValue = \FALSE) {
        $value = $default;
        if (!$projectValue and isset($this->_parameters[$name])) {
            $value = $this->_parameters[$name];
        }
        elseif (!$projectValue and !is_null($this->_currentProject)) {
            $project = $this->_currentProject;
            if (isset($project->$name)) {
                $value = $project->$name;
            }
        }
        return $value;
    }

    /**
     * Returns the local URL (by default the name of the base dir + .local
     *
     * @return string
     */
    public function getUrl() {
        return $this->_getParameter('Url', basename($this->getProjectDir()) . '.local', \TRUE);
    }

    /**
     * 
     * @return string
     */
    public function getFileName() {
        return $this->_parameters['FileName'];
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
        if (isset($this->_parameters['Interactive'])) {
            return \TRUE;
        }
        else {
            return \FALSE;
        }
    }

    /**
     * Magic method to manage the parameters
     *
     * @param string $name
     * @param mixed[] $arguments
     */
    public function __call($name, $arguments) {
        if (substr($name, 0, 3) == 'set') {
            $key = substr($name, 3);
            $this->_parameters[$key] = $arguments[0];
        }
        elseif (substr($name, 0, 3) == 'put') {
            $key = substr($name, 3);
            $this->_parameters[$key] = $arguments[0];
            iris_debug($this->_parameters);
        }
        else {
            throw new \Iris\Exceptions\CLIException("No function like $name in class Parameter");
        }
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
    public function getProjects() {
        return $this->_projects;
    }

    /**
     * 
     * @param \Iris\SysConfig\Config $currentProject
     */
    public function setCurrentProject($currentProject) {
        $this->_currentProject = $currentProject;
    }

    /**
     * 
     * @return \Iris\SysConfig\Config 
     */
    public function getCurrentProject() {
        return $this->_currentProject;
    }

    /**
     * Save all projects
     */
    public function saveProject() {
        $os = \Iris\OS\_OS::GetInstance();
        $paramDir = $os->getUserHomeDirectory() . IRIS_USER_PARAMFOLDER;
        $this->writeParams("$paramDir" . IRIS_PROJECT_INI, $this->_projects);
    }

}
