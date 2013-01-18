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
        if(!$display)echo "Creating $virtualFile for httpd-virtual.conf.\n";
        $url = $parameters->getUrl();
        $publicDir = $parameters->getPublicDir();
        $docRoot = "$projectDir/$publicDir";
        if(strpos($url,'.')!==FALSE){
            $text = $this->_virtualApache($docRoot, $url, \TRUE);
        }else{
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
     * @param string $docRoot
     * @param string $url
     * @param boolean $development 
     */
    private function _virtualApache($docRoot, $url, $development=\TRUE) {
        $setEnv = "SetEnv APPLICATION_ENV development";
        $devEnv = $development ? $setEnv : "# $setEnv";
        $text = <<<APACHE
<VirtualHost *:80>
   DocumentRoot "$docRoot"
   # You can change the sever name to whatever you want
   ServerName $url

   <Directory "$docRoot">
       Options Indexes MultiViews FollowSymLinks
       AllowOverride All
       Order allow,deny
       Allow from all
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
        $this->_createFile("$source/index.php", "$destination/index.php", array('{APPLICATION}' => $parameters->getApplicationName()));
        // other files are simply copied
        $this->_createFile("$source/dothtaccess", "$destination/.htaccess");
        $this->_createFile("$source/Bootstrap.php", "$destination/Bootstrap.php");
        echo "You may have to edit $publicDir/.htaccess to suit your provider requirement.\n";
    }

    /**
     * Creates the application tree
     * 
     * @param String $projectDir the project dir name
     * @param String $programName the program name (def: program)
     */
    public function makeApplication($projectDir) {
        // directories beginning by '!' have full permissions
        $directories = [
            'models/crud',
            '!config/admin',
            '!data/private',
            '!data/public',
            'modules'
        ];
        $files = [
            '_application.php' => "modules/_application.php",
            '00_debug.php' => "config/00_debug.php",
            '20_settings.php' => "config/20_settings.php",
        ];
        $parameters = Parameters::GetInstance();    
        $programName = $parameters->getApplicationName();
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destination = "$projectDir/$programName";
        echo "Making application directories and files ($programName/...).\n";
        $this->_createDir($directories, $destination);
        foreach ($files as $template => $file) {
            $this->_createFile("$source/$template", "$destination/$file");
        }
        $this->_newModule($destination, "main");
    }

    /**
     * Generates a module, a controller and/or an action
     * according to the necessity
     * 
     * @param string $module
     * @param string $controller
     * @param string $action 
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
            $this->_updateConfig($configs);
        }
    }

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
        $this->_createFile("$source/module.php", "$destinationMod/controllers/_$moduleName.php", array(
            '{MODULE}' => $moduleName,
            '{MODULECONTROLLER}' => "_$moduleName"
                )
        );
        $this->_newController($destination, $moduleName, $controllerName, $actionName);
    }

    private function _newController($destination, $moduleName, $controllerName, $actionName = 'index') {
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        if ($moduleName == 'main' and $controllerName == 'index') {
            $title = '$this->_view->welcome(1)';
        }
        else {
            $title = "'<h1>$moduleName - $controllerName - $actionName</h1> '";
        }
        $controllerPath = "$destinationMod/controllers/$controllerName.php";
        if (file_exists($controllerPath)) {
            throw new \Iris\Exceptions\CLIException("Controller $controllerName already exists.");
        }
        $this->_createFile("$source/index.php", $controllerPath, array(
            '{MODULE}' => $moduleName,
            '{MODULECONTROLLER}' => "_$moduleName",
            '{CONTROLLER}' => $controllerName,
            '{TITLE}' => "$title"
                )
        );
        $this->_newAction($destination, $moduleName, $controllerName, $actionName);
    }

    private function _newAction($destination, $moduleName, $controllerName, $actionName) {
        $source = Analyser::GetIrisLibraryDir() . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        $scriptName = "$destinationMod/views/scripts/{$controllerName}_$actionName.iview";
        if (file_exists($scriptName)) {
            throw new \Iris\Exceptions\CLIException("The action $actionName already exists.");
        }
        $this->_createFile("$source/index_index.iview", $scriptName);
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

    protected function _copyright() {
        $parameters = Parameters::GetInstance();
        $fileName = $parameters->getFileName();
        if (basename($fileName) == 'Code.php') {
            throw new \Iris\Exceptions\CLIException('Code.php cannot modify itself!');
        }
        echo "Reading $fileName\n";
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
 * @copyright 2011-2013 Jacques THOORENS
 *

TEXT;
        $file = file($fileName);
        $newFile = '';
        foreach ($file as $line) {
            if (preg_match('/\* Project IRIS-PHP/', $line)) {
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
