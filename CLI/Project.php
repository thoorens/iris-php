<?php

namespace CLI {

    /*
     * This file is part of IRIS-PHP, distributed under the General Public License version 3.
     * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
     * More details about the copyright may be found at
     * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
     *  
     * @copyright 2011-2015 Jacques THOORENS
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
         * Permits to display some usefull informations about the current projects
         */
        protected function _show($option) {
            $parameters = Parameters::GetInstance();
            switch ($option) {
                case 'status':
                    $this->_showStatus($parameters);
                    break;
                case 'list':
                    $this->_showList($parameters);
                    break;
// Recreates the file for Apache
                case 'virtual':
                    $parameters->requireDefaultProject();
                    //$projectConfig = $analyser->loadDefaultProject();
                    FrontEnd::Loader('/CLI/Code.php');
                    $coder = new Code($parameters);
                    $coder->makeVirtualParameter(TRUE);
                    break;
// Display the content of projects.ini
                case 'ini':
                    $projectFileName = FrontEnd::GetFilePath('projects', 'test');
                    echo(file_get_contents($projectFileName));
                    break;
                case 'iris':
                    $projectFileName = FrontEnd::GetFilePath('iris', 'test');
                    echo(file_get_contents($projectFileName));
                    echoLine('');
                    break;
            }
        }

        /**
         * Displays the list of the defined projects
         */
        private function _showList($parameters) {
            \echoLine(Parameters::LINE);
            \echoLine("List of existing project(s) ");
            \echoLine(Parameters::LINE);
            $projects = $parameters->getProjects();
            $defaultProjectName = $projects[Parameters::IRISDEF]->DefaultProject;
            //$defaultProjectName = is_null($defaultProject) ? '' : $defaultProject->ProjectName;
            // forgets ['iris'] section
            array_shift($projects);
            $num = 1;
            foreach ($projects as $projectName => $project) {
                $locked = $project->Locked ? '(L)' : '   ';
                $def = $projectName == $defaultProjectName ? " (default)" : "";
                printf("%2d. %s %s %s\n", $num++, $locked, $projectName, $def);
            }
        }

        /**
         * Displays the parameters of the default project
         *  @param \CLI\Parameters $parameters 
         */
        private function _showStatus($parameters) {
            $defaultProject = $parameters->getProject();
            \echoLine(Parameters::LINE);
            \echoLine(sprintf("Status of %s (default project)", $defaultProject->ProjectName));
            \echoLine(Parameters::LINE);
            $parameters->displayParameters($defaultProject);
        }

        protected function _test() {
            iris_debug($this->_analyser);
        }

        /**
         * Locks a project, to prevent its deletion.
         */
        protected function _lockproject($projectName) {
            $this->_protectProject($projectName, \TRUE);
        }

        /**
         * Unlocks a project to permit its deletion
         */
        protected function _unlockproject($projectName) {
            $this->_protectProject($projectName, \FALSE);
        }

        /**
         * Marks an existing project as been (un)locked. Exception when project
         * 
         * @param string $projectName
         * @param boolean $status
         */
        private function _protectProject($projectName, $status) {
            $parameters = Parameters::GetInstance();
            $config = $parameters->getProject($projectName);
            $config->Locked = $status ? 1 : 0;
            $parameters->saveProject(\TRUE);
            if ($status) {
                \Messages::Abort("MSG_LOCK", $projectName);
            }
            else {
                \Messages::Abort("MSG_UNLOCK", $projectName);
            }
        }

        protected function _docproject() {
            $parameters = Parameters::GetInstance();
            $configs = $parameters->getProjects();
            $projectName = $parameters->getProjectName();
            if (!isset($configs[$projectName])) {
                \Messages::Abort('ERR_NOPROJECT',$projectName);
            }
            $this->_readProjectDocumentation();
            $parameters->saveProject(); // no necessary ??
            //$this->_updateConfig($configs);
        }

        /**
         * Creates a new project from scratch
         *
         */
        protected function _createproject($projectName) {
            /* @var $parameters Parameters */
            $parameters = Parameters::GetInstance();
            if ($parameters->getInteractive()) {
                $this->_readProjectDocumentation($parameters->getProject());
            }
            $CommandLine = $parameters->getCommandLine();
            if ($parameters->getCommandLine() == 'simulate') {
                $simulating = TRUE;
            }
            else{
                $simulating = FALSE;
            }
            $this->_os = \Iris\OS\_OS::GetInstance();
            $this->_os->setSimulate($simulating);
            $projectDir = $parameters->getProjectDir();
            //$projectName = $parameters->getProjectName();
            \echoLine("Creating new project $projectName in folder $projectDir");
            if (!file_exists($projectDir)) {
                $this->_os->mkDir($projectDir);
            }
            \echoLine("Testing $projectDir/.$projectName.irisproject");
// Create the project file
            $this->_os->touch("$projectDir/.$projectName.irisproject");
// Creates the three parts of the project + a file for Apache
            FrontEnd::Loader('/CLI/Code.php');
            $coder = new Code($parameters);
            $coder->_os = $this->_os;
            $coder->makePublic($projectDir, $parameters);
            $coder->makeApplication($projectDir);
            $this->_makeLibrary($projectDir, $parameters->getLibraryDir());
            $coder->makeVirtualParameter();
// add a new config to the configs
            if (!$simulating) {
                $this->_setDefaultProject($projectName);
                //$parameters->saveProject(\TRUE);
            }
        }

        /**
         * Creates a new project configs and puts it in the parameter array
         * @return array
         */
//        private function _createProjectConfig() {
//            $parameters = Parameters::GetInstance();
//            ///$parameters->requireProjects(\FALSE); // no error if not exists
//            $projects = $parameters->getProjects();
//            if (count($projects) == 0) {
//                $projects[Parameters::IRISDEF] = new \Iris\SysConfig\Config(Parameters::IRISDEF);
//            }
//            $config = $parameters->createNewConfig();
//            $projects[$config->getName()] = $config;
//            $config->ModuleName = 'main';
//            $config->ControllerName = 'index';
//            $config->ActionName = 'index';
//            $projects[Parameters::IRISDEF]->DefaultProject = $config->ProjectName;
//            $parameters->setCurrentProject($config);
//            return $projects;
//        }

        /**
         * Provides an optional way to document more seriously each file
         * created with CLI.
         */

        /**
         *
         * @param \Iris\SysConfig\Config $project
         */
        private function _readProjectDocumentation($project) {
            $project = Parameters::GetInstance()->getProject();
            $project->Author = Analyser::PromptUser('Author', $project->Author, getenv('USER'));
            $project->License = Analyser::PromptUser('License', $project->tLicense);
            $project->Comment = Analyser::PromptUser('Comment', $project->Comment);
        }

        /**
         * Changes the developper environment so that another project will be
         * used by default for all the project relative command
         * Note: --createproject implicitely changed the default project to the new one.
         */
        protected function _setDefaultProject($projectName) {
            $projects = Parameters::GetInstance()->getProjects();
            if (!isset($projects[$projectName])) {
                \Messages::Abort("ERR_UNKNOWNPROJECT", $projectName);
            }
            $projects[Parameters::IRISDEF]->DefaultProject = $projectName;
            \Messages::Display("MSG_NEWDEF", $projectName);
            $this->_protectProject($projectName, \TRUE);
//            $parameters->markDirty();
//            $parameters->saveProject(\TRUE);
//            $this->_showList($parameters);
//            \echoLine("$projectName is now your default project.");
        }

        /**
         * Completely deletes a project (including all the files it contains)
         * CAUTION : ALL THE FILES ARE DELETED
         * To prevent errors, the command must be followed by "confirm"
         * You can also use --lockproject to lock the project (it can be modified but not deleted)
         */
        protected function _removeproject($projectName) {
            $parameters = Parameters::GetInstance();
            $projects = $parameters->getProjects();
            if (!isset($projects[$projectName])) {
                \Messages::Abort("ERR_CANNOTDELETE", $projectName);
            }
            $projectDir = $projects[$projectName]->ProjectDir;
            if (!file_exists($projectDir)) {
                \Messages::Abort("ERR_CANNOTFIND", $projectDir, $projectName);
            }
            $CommandLine = $parameters->getCommandLine();
            $simulate = TRUE;
            if ($CommandLine == 'confirm') {
                $simulate = FALSE;
            }
            else {
                $simulate = TRUE;
            }
            if ($projects[$projectName]->Locked) {
                \Messages::Abort("ERR_LOCKED", $projectName);
            }
            $projectDir = $projects[$projectName]->ProjectDir;
            self::EmptyDir($projectDir, 1, $simulate);
            unset($projects[$projectName]);
            $defaultProject = $projects[Parameters::IRISDEF]->DefaultProject;
            if ($simulate) {
                \Messages::Abort("ERR_CONFIRM", $projectName);
            }
            else {
                $parameters->removeProject($projectName);
                $defaultProject = $projects[Parameters::IRISDEF]->DefaultProject;
                \Messages::Display("MSG_DELETED",$projectName);
                if ($projectName == $defaultProject) {
                    \Messages::Display("MSG_NOMOREDEF");
                    $Iris = $projects[Parameters::IRISDEF];
                    unset($Iris->DefaultProject);
                }
                $parameters->saveProject(\TRUE);
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
            FrontEnd::Loader('/CLI/Code.php');
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
            \Messages::Abort("Menu $menuName with $items items");
        }

        /**
         * Creates the link to the IRIS-PHP framework
         * 
         * @param string $projectDir Root directory of the project
         */
        private function _makeLibrary($projectDir, $libraryName) {
            $this->_os->symlink(IRIS_LIBRARY_DIR, "$projectDir/$libraryName");
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
            if (filetype($dir) == 'link') {
                $os->unlink($dir);
            }
            else {
                $os->tabLevel = $level;
                if ($simulating) {
                    $number = -$level * 4;
                    echo(sprintf("%${number}s", ' '));
                    $oldlevel = $level++;
                    \echoLine("Entering directory $dir");
                }
                $handle = opendir($dir);
                while ($elem = readdir($handle)) {
                    if (is_dir($dir . '/' . $elem) && substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') { //if it si a directory
                        self::EmptyDir($dir . '/' . $elem, $level, $simulating);
                        $os->tabLevel = $level - 1;
                    }
                    else {
                        if (substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') {
                            $os->unlink($dir . '/' . $elem);
                        }
                    }
                }
                if ($simulating) {
                    $spaces = -$oldlevel * 4;
                    echo(sprintf("%${spaces}s", ' '));
                    \echoLine("Exiting  directory $dir");
                    $os->tabLevel = $level - 2;
                }
                $os->rmdir($dir);
            }
        }

    }

}

