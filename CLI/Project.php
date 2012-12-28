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

            switch ($option) {
// Recreates the file for Apache
                case 'virtual':
                    $projectConfig = $this->_analyser->loadDefaultProject();
                    require_once Analyser::GetIrisSystemDir() . '/CLI/Code.php';
                    $coder = new Code($this->_analyser);
                    $coder->makeVirtualParameter(TRUE);
                    break;
// Shows all parameters for the default project
                case 'status':
                    $projectConfig = $this->_analyser->loadDefaultProject();
                    echo "-------------------------------------------------------------\n";
                    echo sprintf("Status of %s\n", $projectConfig->ProjectName);
                    echo "-------------------------------------------------------------\n";
                    $this->_analyser->displayParameters();
                    break;
// Lists all existing projects                 
                case 'list':
                    echo "-------------------------------------------------------------\n";
                    echo "List of existing project(s) \n";
                    echo "-------------------------------------------------------------\n";
                    $configs = $this->_analyser->getConfigs();
                    $defaultProject = $configs['Iris']->DefaultProject;
                    array_shift($configs);
                    $num = 1;
                    foreach ($configs as $projectName => $config) {
                        if ($config->Locked) {
                            $locked = '(L)';
                        }
                        else {
                            $locked = '   ';
                        }
                        printf("%2d. %s %s", $num++, $locked, $projectName);
                        if ($projectName == $defaultProject) {
                            echo " (default)";
                        }
                        echo "\n";
                    }
            }
        }

        /**
         * Locks a project, to prevent its deletion
         */
        protected function _lockproject() {
            $projectName = $this->_protectProject(\TRUE);
        }

        /**
         * Unlocks a project to permit its deletion
         */
        protected function _unlockproject() {
            $projectName = $this->_protectProject(\FALSE);
        }

        /**
         * Marks an existing project as been (un)locked. Exception when project
         * @param boolean $status
         * @throws \Iris\Exceptions\CLIException 
         */
        private function _protectProject($status) {
            $configs = $this->_analyser->getConfigs();
            $projectName = $this->_analyser->getProjectName();
            if (!isset($configs[$projectName])) {
                throw new \Iris\Exceptions\CLIException("The project '$projectName' doesn't exist. Choose another one.\n");
            }
            $configs[$projectName]->Locked = $status ? 1 : 0 ;
            $this->_updateConfig($configs);
            $finalState = $status ? 'locked' : 'unlocked';
            echo "The project $projectName has been $finalState.\n";
        }

        protected function _docproject() {
            die('ok for doc');
        }

        /**
         * Creates a new project from scratch
         * 
         */
        protected function _createproject() {
            $param = $this->_analyser->getParameters();
            if (isset($param['Interactive'])) {
                $this->_document();
            }
            $CommandLine = $this->_analyser->getCommandLine();
            $simulating = FALSE;
            if (count($CommandLine) > 1 and $CommandLine[1] == 'simulate') {
                $simulating = TRUE;
            }
            $this->_os = \Iris\OS\_OS::GetOSInstance();
            $this->_os->setSimulate($simulating);
            $projectDir = $this->_analyser->getProjectDir();
            $projectName = $this->_analyser->getProjectName();
// Creates a folder for the project (if it doesn't exist)
            if (file_exists($projectDir) and file_exists("$projectDir/.$projectName.irisproject")) {
                throw new \Iris\Exceptions\CLIException("The project '$projectName' already exists. Choose another name.\n");
            }
            echo "Creating new project $projectName in folder $projectDir\n";
            if (!file_exists($projectDir)) {
                $this->_os->mkDir($projectDir);
            }
            echo "Testing $projectDir/.$projectName.irisproject\n";
// Create the project file
            $this->_os->touch("$projectDir/.$projectName.irisproject");
// Creates the three parts of the project + a file for Apache 
            require_once Analyser::GetIrisSystemDir() . '/CLI/Code.php';
            $coder = new Code($this->_analyser);
            $coder->_os = $this->_os;
            $coder->makePublic($projectDir);
            $coder->makeApplication($projectDir);
            $this->_makeLibrary($projectDir);
            $coder->makeVirtualParameter();
// add a new config to the configs
            if (!$simulating) {
                $analyser = $this->_analyser;
                $module = $analyser->getModuleName();
                $controller = $analyser->getControllerName();
                $analyser->getActionName();
                $configs = $analyser->getConfigs();
                $config = $analyser->createNewConfig();
                $configs[$config->getName()] = $config;
                unset($configs['Iris']->DefaultProject);
                $configs['Iris']->DefaultProject = $config->ProjectName;
                $this->_updateConfig($configs);
            }
        }

        /**
         * Provides an optional way to document more seriously each file
         * created with CLI.
         */
        private function _document() {
            $analyser = $this->_analyser;
            $analyser->putDetailedProjectName($analyser->promptUser(
                            'More readable project name', $analyser->getDetailedProjectName()));
            $analyser->putAuthor($analyser->promptUser('Author', $analyser->getAuthor()));
            $analyser->putLicense($analyser->promptUser('License', $analyser->getLicense()));
            $analyser->putComment($analyser->promptUser('Comment', $analyser->getComment()));
        }

        protected function _setdefaultproject() {
            $projectName = $this->_analyser->getProjectName();
            $configs = $this->_analyser->getConfigs();
            if (!isset($configs[$projectName])) {
                throw new \Iris\Exceptions\CLIException("The project $projectName is unknown.");
            }
            $configs['Iris']->DefaultProject = $projectName;
            $this->_updateConfig($configs);
            echo "$projectName is now the default project.\n";
        }

        /**
         * Completely deletes a project (including all the files it contains)
         * CAUTION : ALL THE FILES ARE DELETED
         * To prevent errors, the command must be followed by "confirm"
         * You can also use --lockproject to lock the project (it can be modified but not deleted)
         */
        protected function _removeproject() {
            $configs = $this->_analyser->getConfigs();
            if (!isset($configs[$this->_analyser->getProjectName()])) {
                throw new \Iris\Exceptions\CLIException('You cannot remove a non existent project');
            }
            $os = \Iris\OS\_OS::GetOSInstance();
            $CommandLine = $this->_analyser->getCommandLine();
            $simulate = TRUE;
            if (count($CommandLine) > 1 and $CommandLine[1] == 'confirm') {
                $simulate = FALSE;
            }
            $projectDir = $this->_analyser->getProjectDir();
            $projectName = $this->_analyser->getProjectName();
            if ($configs[$projectName]->Locked) {
                throw new \Iris\Exceptions\CLIException('Caution : the project is locked. You may wish to use --unlockproject.');
            }
            self::EmptyDir($projectDir, 0, $simulate);
            unset($configs[$projectName]);
            $defaultProject = $configs['Iris']->DefaultProject;
            if ($simulate) {
                throw new \Iris\Exceptions\CLIException('You must terminate the command removeproject by the word "confirm" to actually delete the project.');
            }
            else {
                if ($projectName == $defaultProject) {
                    echo "Le projet par défaut va être effacé\n";
                }
                if ($projectName == $defaultProject) {
                    $Iris = $configs['Iris'];
                    unset($Iris->DefaultProject);
                }
                $this->_updateConfig($configs);
            }
        }

        /**
         * Creates a new module for the default project. 
         * Verifies whether there is a default project and the 
         * module does not exist.
         */
        protected function _generate() {
// if no default project, next line throws an exception
//$this->_analyser->loadDefaultProject(FALSE);
            $moduleName = $this->_analyser->getModuleName();
            $controllerName = $this->_analyser->getControllerName();
            $actionName = $this->_analyser->getActionName();
            require_once Analyser::GetIrisSystemDir() . '/CLI/Code.php';
            $coder = new Code($this->_analyser);
            $coder->makeNewCode($moduleName, $controllerName, $actionName);
        }

        /**
         * Creates an new menu with dummy items
         */
        protected function _makemenu() {
            $analyser = $this->_analyser;
            $analyser->loadDefaultProject();
            $parameters = $analyser->getParameters();
            $menuName = $parameters['MenuName'];
            $items = $parameters['Items'];
            $dir = $analyser->getProjectDir();
            $application = $analyser->getApplicationName();
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
         * création des liens vers la bibliothèque Iris
         * @param type $projectDir répertoire de base du projet
         */
        private function _makeLibrary($projectDir) {
            $this->_os->symlink(Analyser::GetIrisSystemDir(), "$projectDir/library");
        }

        /**
         *
         * @param string $dir The path to the base dir from which delete
         * @param int $level Indentation level (for display only)
         * @param boolean $simulating If TRUE only display the file to be deleted
         */
        public static function EmptyDir($dir, $level = 0, $simulating = TRUE) {
            $os = \Iris\OS\_OS::GetOSInstance();
            $os->setSimulate($simulating);
            $os->tabLevel = $level;
            if (filetype($dir) == 'link') {
                $os->unlink($dir);
            }
            else {
                if ($simulating) {
                    for ($l = 0; $l < $level; $l++) {
                        echo "  ";
                    }
                    $level++;
                    echo "Entering $dir\n";
                }
                $handle = opendir($dir);
                while ($elem = readdir($handle)) {
//ce while vide tous les repertoire et sous rep
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

        protected function _test() {
            $parameters = $this->_analyser->getParameters();
            foreach ($parameters as $name => $value) {
                echo "$name : $value\n";
            }
        }

    }

}

