<?php

namespace CLI {

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
     * This class manages a project: creation, deletion, locking and
     * displaying status
     *
     * @author Jacques THOORENS (jacques@thoorens.net)
     * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
     * @version $Id: $
     */
    class Project extends _Process {

        /**
         * Permits to display some usefull informations about the current project
         */
        protected function _show($option) {
            $parameters = $this->getParameters();
            switch ($option) {
// Recreates the file for Apache
                case 'virtual':
                    $parameters->requireDefaultProject();
                    //$projectConfig = $analyser->loadDefaultProject();
                    require_once Analyser::GetIrisLibraryDir() . '/CLI/Code.php';
                    $coder = new Code($parameters);
                    $coder->makeVirtualParameter(TRUE);
                    break;
// Shows all parameters for the default project
                case 'status':
                    $parameters->requireDefaultProject();
                    $project = $parameters->getCurrentProject();
                    \echoLine("-------------------------------------------------------------");
                    \echoLine(sprintf("Status of %s", $project->ProjectName));
                    \echoLine("-------------------------------------------------------------");
                    $parameters->displayParameters();
                    break;
// Lists all existing projects
                case 'list':
                    $parameters->requireProjects();
                    \echoLine("-------------------------------------------------------------");
                    \echoLine("List of existing project(s) ");
                    \echoLine("-------------------------------------------------------------");
                    $projects = $parameters->getProjects();
                    $defaultProject = $parameters->getCurrentProject();
                    $defaultProjectName = is_null($defaultProject) ? '' : $defaultProject->ProjectName;
                    array_shift($projects);
                    $num = 1;
                    foreach ($projects as $projectName => $project) {
                        if ($project->Locked) {
                            $locked = '(L)';
                        }
                        else {
                            $locked = '   ';
                        }
                        printf("%2d. %s %s", $num++, $locked, $projectName);
                        if ($projectName == $defaultProjectName) {
                            \echoLine( " (default)");
                        }
                        \echoLine("");
                    }
            }
        }

        protected function _test(){
            iris_debug($this->_analyser);
        }
        
        /**
         * Locks a project, to prevent its deletion.
         */
        protected function _lockproject() {
            $this->_protectProject(\TRUE);
        }

        /**
         * Unlocks a project to permit its deletion
         */
        protected function _unlockproject() {
            $this->_protectProject(\FALSE);
        }

        /**
         * Marks an existing project as been (un)locked. Exception when project
         * @param boolean $status
         * @throws \Iris\Exceptions\CLIException
         */
        private function _protectProject($status) {
            $parameters = $this->getParameters();
            $parameters->requireProjects();
            $configs = $parameters->getProjects();
            $projectName = $parameters->getProjectName();
            if (!isset($configs[$projectName])) {
                throw new \Iris\Exceptions\CLIException("The project '$projectName' doesn't exist. Choose another one.\n");
            }
            $configs[$projectName]->Locked = $status ? 1 : 0;
            $this->_updateConfig($configs);
            $finalState = $status ? 'locked' : 'unlocked';
            \echoLine("The project $projectName has been $finalState.");
        }

        protected function _docproject() {
            $parameters = $this->getParameters();
            $parameters->requireProjects();
            $configs = $parameters->getProjects();
            $projectName = $parameters->getProjectName();
            if (!isset($configs[$projectName])) {
                throw new \Iris\Exceptions\CLIException("The project '$projectName' doesn't exist. Choose another one.\n");
            }
            $this->_readProjectDocumentation();
            $this->_updateConfig($configs);
        }

        /**
         * Creates a new project from scratch
         *
         */
        protected function _createproject() {
            /* @var $parameters Parameters */
            $parameters = $this->getParameters();
            $projects = $this->_createProjectConfig($parameters);
            if ($parameters->getInteractive()) {
                $this->_readProjectDocumentation();
            }
            $CommandLine = $parameters->getCommandLine();
            $simulating = FALSE;
            if (count($CommandLine) > 1 and $CommandLine[1] == 'simulate') {
                $simulating = TRUE;
            }
            $this->_os = \Iris\OS\_OS::GetInstance();
            $this->_os->setSimulate($simulating);
            $projectDir = $parameters->getProjectDir();
            $projectName = $parameters->getProjectName();
// Creates a folder for the project (if it doesn't exist)
            if (file_exists($projectDir) and file_exists("$projectDir/.$projectName.irisproject")) {
                throw new \Iris\Exceptions\CLIException("The folder '$projectDir' seems to contain a possibly broken project. Choose another name.\n");
            }
            if (file_exists($projectDir) and !$parameters->Force) {
                throw new \Iris\Exceptions\CLIException("A folder '$projectDir' already exists.Choose another name\n");
            }
            \echoLine( "Creating new project $projectName in folder $projectDir");
            if (!file_exists($projectDir)) {
                $this->_os->mkDir($projectDir);
            }
            \echoLine( "Testing $projectDir/.$projectName.irisproject");
// Create the project file
            $this->_os->touch("$projectDir/.$projectName.irisproject");
// Creates the three parts of the project + a file for Apache
            require_once Analyser::GetIrisLibraryDir() . '/CLI/Code.php';
            $coder = new Code($parameters);
            $coder->_os = $this->_os;
            $coder->makePublic($projectDir);
            $coder->makeApplication($projectDir);
            $this->_makeLibrary($projectDir, $parameters->getLibraryName());
            $coder->makeVirtualParameter();
// add a new config to the configs
            if (!$simulating) {
                $this->_updateConfig($projects);
            }
        }

        /**
         * Creates a new project configs and puts it in the parameter array
         * @return array
         */
        private function _createProjectConfig() {
            $parameters = $this->getParameters();
            $parameters->requireProjects(\FALSE); // no error if not exists
            $projects = $parameters->getProjects();
            if (is_null($projects)) {
                $projects['Iris'] = new \Iris\SysConfig\Config('Iris');
            }
            $config = $parameters->createNewConfig();
            $projects[$config->getName()] = $config;
            $config->ModuleName = 'main';
            $config->ControllerName = 'index';
            $config->ActionName = 'index';
            $projects['Iris']->DefaultProject = $config->ProjectName;
            $parameters->setCurrentProject($config);
            return $projects;
        }

        /**
         * Provides an optional way to document more seriously each file
         * created with CLI.
         */

        /**
         *
         * @param type $configs
         */
        private function _readProjectDocumentation() {
            $config = $this->getParameters()->getCurrentProject();
            $config->Author = Analyser::PromptUser('Author', $config->Author, getenv('USER'));
            $config->License = Analyser::PromptUser('License', $config->tLicense);
            $config->Comment = Analyser::PromptUser('Comment', $config->Comment);
        }

        /**
         * Changes the developper environment so that another project will be
         * used by default for all the project relative command
         * Note: --createproject implicitely changed the default project to the new one.
         * 
         * @throws \Iris\Exceptions\CLIException
         */
        protected function _setdefaultproject() {
            $parameters = Parameters::GetInstance();
            $parameters->requireProjects();
            $projectName = $parameters->getProjectName();
            $projects = $parameters->getProjects();
            if (!isset($projects[$projectName])) {
                throw new \Iris\Exceptions\CLIException("The project $projectName is unknown.");
            }
            $projects['Iris']->DefaultProject = $projectName;
            $this->_updateConfig($projects);
            \echoLine("$projectName is now your default project.");
        }

        /**
         * Completely deletes a project (including all the files it contains)
         * CAUTION : ALL THE FILES ARE DELETED
         * To prevent errors, the command must be followed by "confirm"
         * You can also use --lockproject to lock the project (it can be modified but not deleted)
         */
        protected function _removeproject() {
            $parameters = Parameters::GetInstance();
            $parameters->requireProjects();
            $projects = $parameters->getProjects();
            if (!isset($projects[$parameters->getProjectName()])) {
                throw new \Iris\Exceptions\CLIException('You cannot remove a non existent project');
            }
            $CommandLine = $parameters->getCommandLine();
            $simulate = TRUE;
            if (count($CommandLine) > 1 and $CommandLine[1] == 'confirm') {
                $simulate = FALSE;
            }
            $projectDir = $parameters->getProjectDir();
            $projectName = $parameters->getProjectName();
            if ($projects[$projectName]->Locked) {
                throw new \Iris\Exceptions\CLIException('Caution : the project is locked. You may wish to use --unlockproject.');
            }
            self::EmptyDir($projectDir, 0, $simulate);
            unset($projects[$projectName]);
            $defaultProject = $projects['Iris']->DefaultProject;
            if ($simulate) {
                throw new \Iris\Exceptions\CLIException('You must terminate the command removeproject by the word "confirm" to actually delete the project.');
            }
            else {
                \echoLine("The project $projectName has been completely removed.");
                if ($projectName == $defaultProject) {
                    \echoLine("There is no more default project.
Use'iris.php --selectdefaultproject' to define a new one.");
                    $Iris = $projects['Iris'];
                    unset($Iris->DefaultProject);
                }
                $this->_updateConfig($projects);
            }
        }

        /**
         * Creates a new module for the default project.
         * Verifies whether there is a default project and the
         * module does not exist.
         */
        protected function _generate() {
            $parameters = Parameters::GetInstance();
            $parameters->requireDefaultProject();
            $moduleName = $parameters->getModuleName();
            $controllerName = $parameters->getControllerName();
            $actionName = $parameters->getActionName();
            require_once Analyser::GetIrisLibraryDir() . '/CLI/Code.php';
            $coder = new Code($parameters);
            $coder->makeNewCode($moduleName, $controllerName, $actionName);
        }

        /**
         * Creates an new menu with dummy items
         */
        protected function _makemenu() {
            $parameters = Parameters::GetInstance();
            $parameters->requireDefaultProject();
            $menuName = $parameters->getMenuName();
            $items = $parameters->getItems();
            $dir = $parameters->getProjectDir();
            $application = $parameters->getApplicationName();
            $file = "$dir/$application/config/30_menu.ini";
            $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
            $menus = $parser->processFile($file);
            if (isset($menus[$menuName])) {
                $menu = $menus[$menuName];
            }
            else {
                $menu = new \Iris\SysConfig\Config($menuName);
            }
//        $menu->set('label','')//foreach ($menu as $menuitem)
//        $menu->set('uri','')//foreach ($menu as $menuitem)
//        $menu->set('label','')//foreach ($menu as $menuitem)
            var_dump($menu);
            die("Menu $menuName with $items items");
        }

        /**
         * Creates the link to the IRIS-PHP framework
         * 
         * @param string $projectDir Root directory of the project
         */
        private function _makeLibrary($projectDir, $libraryName) {
            $this->_os->symlink(Analyser::GetIrisLibraryDir(), "$projectDir/$libraryName");
        }

        /**
         * Removes all files in the requested directory. 
         * CAUTION : if third parameter is TRUE, all files are wiped out
         *  
         * @param string $dir The path to the base dir from which delete
         * @param int $level Indentation level (used in simulation display)
         * @param boolean $simulating If TRUE only display the file to be deleted
         */
        public static function EmptyDir($dir, $level = 0, $simulating = TRUE) {
            $os = \Iris\OS\_OS::GetInstance();
            $os->setSimulate($simulating);
            $os->tabLevel = $level;
            if (filetype($dir) == 'link') {
                $os->unlink($dir);
            }
            else {
                if ($simulating) {
                    for ($l = 0; $l < $level; $l++) {
                        \echoLine( "  ");
                    }
                    $level++;
                    \echoLine( "Entering $dir");
                }
                $handle = opendir($dir);
                while ($elem = readdir($handle)) {
                    if (is_dir($dir . '/' . $elem) && substr($elem, -2, 2) !== '..' && substr(
                                    $elem, -1, 1) !== '.') { //si c'est un repertoire
                        self::EmptyDir($dir . '/' . $elem, $level, $simulating);
                        $os->tabLevel = $level - 1;
                    }
                    else {
                        if (substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') {
                            $os->unlink($dir . '/' . $elem);
                        }
                    }
                }
                $os->rmdir($dir);
            }
        }

    }

}

