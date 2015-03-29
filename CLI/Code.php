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
 * @copyright 2011-2014 Jacques THOORENS
 *


  }/**
 * This class creates piece of code: action scripts, controllers,
 * modules and application.
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */

class Code extends _Process {

    const MODULE = 1;
    const CONTROLLER = 2;
    const ACTION = 4;

    /**
     * Creates the "projetName".virtual file containing the parameters
     * for a virtual host in Apache server (only for development purpose)
     * 
     * @param boolean $display if TRUE only display the file content 
     */
    public function makeVirtualParameter($display = FALSE) {
        $parameters = Parameters::GetInstance();
        $projectDir = $parameters->getProjectDir();
        $projectName = $parameters->getProjectName();
        $virtualFile = "$projectDir/$projectName.virtual";
        if (!$display)
            echo "Creating $virtualFile for httpd-virtual.conf.\n";
        $url = $parameters->getUrl();
        $publicDir = $parameters->getPublicDir();
        $docRoot = "$projectDir/$publicDir";
        if (strpos($url, '.') !== FALSE) {
            $text = $this->_virtualApache($docRoot, $url, \TRUE);
        }
        else {
            $text = $this->_virtualApache($docRoot, "$url.local", \TRUE);
            $text .= $this->_virtualApache($docRoot, "$url.prod", \FALSE);
        }
        if ($display) {
            echo $text;
        }
        else {
            $this->_os->file_put_contents($virtualFile, $text);
        }
    }

    /**
     * Composes the virtual parameters for Apache
     * 
     * @param string $docRoot The Root of the site
     * @param string $url The URL of the site
     * @param boolean $development if True, development is on
     */
    private function _virtualApache($docRoot, $url, $development = \TRUE) {
        $setEnv = "SetEnv APPLICATION_ENV development";
        $devEnv = $development ? $setEnv : "# $setEnv";
        if (\TRUE) {
            $comap24 = '# ';
            $comap22 = '';
        }
        else {
            $comap24 = '';
            $comap22 = '# ';
        }
        $text = <<<APACHE
<VirtualHost *:80>
   DocumentRoot "$docRoot"
   # You can change the sever name to whatever you want
   ServerName $url

   <Directory "$docRoot">
       Options Indexes
       Options -MultiViews
       Options FollowSymLinks
       AllowOverride All
                
       # Parameters for Apache 2.0 and 2.2         
       {$comap24}Order allow,deny
       {$comap24}Allow from all
                
       # Parameter for Apache 2.4         
       {$comap22}Require all granted         
   </Directory>
   # This should be omitted in the production environment
   $devEnv

                
</VirtualHost>

APACHE;
        return $text;
    }

    /**
     * Creates and populates the public directory with 
     * some files and directories
     * 
     * @param string $projectDir the project base directory name
     */
    public function makePublic($projectDir) {
        $parameters = Parameters::GetInstance();
        $publicDir = $parameters->getPublicDir();
        echo "Making public directories and files ($publicDir/...).\n";
        $permissions = $this->_os->GetPrivateMod();
        $this->_os->mkDir("$projectDir/$publicDir", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/images", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/css", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/js", $permissions);
        // copy and adapt index.php
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/public';
        $destination = "$projectDir/$publicDir";
        $this->_createFile("$source/index.php", "$destination/index.php", ['{APPLICATION}' => $parameters->getApplicationName()]);
        // other files are simply copied
        $this->_createFile("$source/dothtaccess", "$destination/.htaccess");
        $this->_createFile("$source/Bootstrap.php", "$destination/Bootstrap.php", ['{LIBRARY}' => $parameters->getLibraryName()]);
        echo "You may have to edit $publicDir/.htaccess to suit your provider requirement.\n";
    }

    /**
     * Creates the application tree
     * 
     * @param String $projectDir the project dir name
     */
    public function makeApplication($projectDir) {
        // directories beginning by '!' have full permissions
        $directories = [
            'models/crud',
            '!config/admin',
            '!config/base',
            '!data/private',
            '!data/public',
            '!log',
            'modules'
        ];
        $files = [
            '_application.php' => "modules/_application.php",
            '01_debug.php' => "config/01_debug.php",
            '20_settings.php' => "config/20_settings.php",
            'CrudIconManager.php' => 'models/crud/CrudIconManager.php',
        ];
        $parameters = Parameters::GetInstance();
        $programName = $parameters->getApplicationName();
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destination = "$projectDir/$programName";
        echo "Making application directories and files ($programName/...).\n";
        $this->_createDir($directories, $destination);
        foreach ($files as $template => $file) {
            $this->_createFile("$source/$template", "$destination/$file",['{CONTROLLER_DESCRIPTION}' => "This is the grand father of all controllers in the application",]);
        }
        $this->_newModule($destination, "main");
    }

    /**
     * Generates a module, a controller and/or an action
     * according to the necessity
     * 
     * @param string $module The module name
     * @param string $controller The controller name
     * @param string $action The action name
     */
    public function makeNewCode($module, $controller, $action) {
        $parameters = Parameters::GetInstance();
        $this->_os = \Iris\OS\_OS::GetInstance();
        $configs = $parameters->getProjects();
        $defaultProject = $configs['Iris']->DefaultProject; //must exist
        $projectDir = "/" . str_replace('_', '/', $defaultProject);
        $projectConfig = $configs[$defaultProject];
        $programName = $projectConfig->ApplicationName;
        $destination = "$projectDir/$programName";
        $doneJobs = 0;
        if (!file_exists("$destination/modules/$module")) {
            $this->_newModule($destination, $module, $controller, $action);
            $doneJobs = self::MODULE + self::CONTROLLER + self::ACTION;
        }
        else {
            if (!file_exists("$destination/modules/$module/controllers/$controller.php")) {
                $this->_newController($destination, $module, $controller, $action);
                $doneJobs = self::CONTROLLER + self::ACTION;
            }
            elseif (!file_exists("$destination/modules/$module/views/scripts/{$controller}_$action.iview")) {
                $this->_newAction($destination, $module, $controller, $action);
                $doneJobs = self::ACTION;
            }
        }
        if ($doneJobs == 0) {
            throw new \Iris\Exceptions\CLIException("$module/$controller/$action already exists.");
        }
        else {
            if ($doneJobs & self::MODULE) {
                echo "Module $module has been created.\n";
            }
            $projectConfig->ModuleName = $module;
            if ($doneJobs & self::CONTROLLER) {
                echo "Controller $controller has been created.\n";
            }
            $projectConfig->ControllerName = $controller;
            if ($doneJobs & self::ACTION) {
                echo "Action $action has been created.\n";
            }
            $projectConfig->ActionName = $action;
            $configs[$defaultProject] = $projectConfig;
            $this->_updateConfig($configs);
        }
    }

    /**
     * Generates a new module with its default controller and action
     * 
     * @param string $destinationn The application directory name 
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     */
    private function _newModule($destination, $moduleName, $controllerName = 'index', $actionName = 'index') {
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $directories = array(
            "modules/$moduleName/controllers/helpers",
            "modules/$moduleName/views/layouts",
            "modules/$moduleName/views/scripts",
            "modules/$moduleName/views/helpers"
        );
        $this->_createDir($directories, $destination);
        $destinationMod = "$destination/modules/$moduleName";
        // module controller file
        $this->_createFile("$source/module.php", "$destinationMod/controllers/_$moduleName.php", [
            '{PHP_TAG}' => '<?php', // To avoid syntactic validation by IDE
            '{MODULE}' => $moduleName,
            '{MODULECONTROLLER}' => "_$moduleName",
            '{CONTROLLER_DESCRIPTION}' => "Description of _$moduleName",
                ]
        );
        if (!is_null($controllerName)) {
            $this->_newController($destination, $moduleName, $controllerName, $actionName);
        }
    }

    /**
     * Generates a new controller with its default action
     * 
     * @param type $destinationn The application directory name
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     * @throws \Iris\Exceptions\CLIException
     */
    private function _newController($destination, $moduleName, $controllerName, $actionName = 'index') {
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        if ($moduleName == 'main' and $controllerName == 'index') {
            $title = '$this->callViewHelper("welcome",1)';
        }
        else {
            $title = "'<h1>$moduleName - $controllerName - $actionName</h1> '";
        }
        $controllerPath = "$destinationMod/controllers/$controllerName.php";
        if (file_exists($controllerPath)) {
            throw new \Iris\Exceptions\CLIException("Controller $controllerName already exists.");
        }
        if (\CLI\Parameters::GetInstance()->workbench)
            $template = 'systemindex.php';
        else
            $template = 'index.php';
        $this->_createFile("$source/$template", $controllerPath, array(
            '{PHP_TAG}' => '<?php', // To avoid syntactic validation by IDE
            '{MODULE}' => $moduleName,
            '{MODULECONTROLLER}' => "_$moduleName",
            '{CONTROLLER}' => $controllerName,
            '{CONTROLLER_DESCRIPTION}' => "Description of $controllerName",
            '{TITLE}' => "$title"
                )
        );
        $this->_newAction($destination, $moduleName, $controllerName, $actionName);
    }

    /**
     * Creates a new action
     * 
     * @param string $destination The application directory name
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     * @throws \Iris\Exceptions\CLIException
     */
    private function _newAction($destination, $moduleName, $controllerName, $actionName) {
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        $scriptName = "$destinationMod/views/scripts/{$controllerName}_$actionName.iview";
        if (file_exists($scriptName)) {
            throw new \Iris\Exceptions\CLIException("The action $actionName already exists.");
        }
        $defaultViewName = "$destination/modules/main/views/scripts/_DEFAULTVIEW.iview";
        if (file_exists($defaultViewName)) {
            $this->_createFile($defaultViewName, $scriptName);
        }
        else {
            $this->_createFile("$source/index_index.iview", $scriptName);
        }
        if ($actionName != 'index') {
            $controllerPath = "$destinationMod/controllers/$controllerName.php";
            $source = file($controllerPath);
            $lineNumber = -1;
            foreach ($source as $line) {
                $lineNumber++;
                if (strpos($line, '}') !== FALSE) {
                    $last = $lineNumber;
                }
            }

            $source[$last] = <<<END
    public function {$actionName}Action() {
        // these parameters are only for demonstration purpose
        \$this->__(NULL, array(
            'Title' => "'<h1>$moduleName - $controllerName - $actionName</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}

END;
            $content = implode('', $source);
            file_put_contents($controllerPath, $content);
        }
    }

    /**
     * This function has been used to watermak all the library files with
     * the copyright notice. Each file had to contain a line containing
     * "* Project IRIS-PHP".
     * 
     * It is still functional
     * 
     * Example : 
     * find -name '*.php' -exec iris.php -o {} \;
     * 
     * @throws \Iris\Exceptions\CLIException
     */
    protected function _copyright() {
        $parameters = Parameters::GetInstance();
        $fileName = $parameters->getFileName();
        if (basename($fileName) == 'Code.php') {
            throw new \Iris\Exceptions\CLIException('Code.php cannot modify itself!');
        }
        echo "Reading $fileName\n";
        $genericParameter = $parameters->getGeneric();
        if (!is_null($genericParameter)) {
            list($searchtext, $copyrightFile) = explode('|', $genericParameter);
            $gpl = file_get_contents($copyrightFile);
        }
        else {
            $searchtext = '\* Project IRIS-PHP';
            $gpl = <<<TEXT
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
 * @copyright 2011-2014 Jacques THOORENS
 *

TEXT;
        }
        $file = file($fileName);
        $newFile = '';
        foreach ($file as $line) {
            if (preg_match("/$searchtext/", $line)) {
                $newFile .= $gpl;
                echo "Writing copyright information in $fileName\n";
            }
            else {
                $newFile .= $line;
            }
        }
        $os = \Iris\OS\_OS::GetInstance();
        $os->file_put_contents($fileName, $newFile);
    }

}
