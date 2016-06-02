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
     * @param Parameters $parameters the optional parameters
     */
    public function makePublic($projectDir, $parameters) {
        $publicDir = $parameters->getPublicDir();
        \Messages::Display('MSG_PUBLIC', $publicDir);
        $permissions = $this->_os->GetPrivateMod();
        $this->_os->mkDir("$projectDir/$publicDir", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/images", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/css", $permissions);
        $this->_os->mkDir("$projectDir/$publicDir/js", $permissions);
        // copy and adapt index.php
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/public';
        $destination = "$projectDir/$publicDir";
        $this->_createFile("$source/index.php", "$destination/index.php", ['{APPLICATION}' => $parameters->getApplicationName()]);
        // other files are simply copied
        $this->_createFile("$source/dothtaccess", "$destination/.htaccess");
        $this->_createFile("$source/Bootstrap.php", "$destination/Bootstrap.php", ['{LIBRARY}' => $parameters->getLibraryDir()]);
        \Messages::Display('MSG_HTACCESS', $publicDir);
        ;
    }

    /**
     * Creates the application tree
     * 
     * @param String $projectDir the project dir name
     */
    public function makeApplication($projectDir) {
        // directories beginning by '!' have full permissions
        $programdir = [
            'models/crud',
            '!config/admin',
            '!config/base',
            '!config/form',
            '!data/private',
            '!data/protected',
            '!log',
            'modules'
        ];
        $datadir = [
            '!data/private',
            '!data/protected',
        ];
        $files = [
            '_application.php' => "modules/_application.php",
            '01_debug.php' => "config/01_debug.php",
            '20_settings.php' => "config/20_settings.php",
            'CrudIconManager.php' => 'models/crud/CrudIconManager.php',
        ];
        $parameters = Parameters::GetInstance();
        //$parameters->getNewProject()['program'].' - '.$parameters->getApplicationName();
        $programName = $parameters->getApplicationName();
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/application';
        $destination = "$projectDir/$programName";
        \Messages::Display('MSG_APPLICATION', $programName);
        $this->_createDir($programdir, $destination);
        $defaultLanguage = Analyser::GetLanguage();
        if ($defaultLanguage == 'EN') {
            $language = '//Iris\SysConfig\Settings::$DefaultLanguage = ' . "'en';";
            '';
        }
        else {
            $defaultLanguage = strtolower($defaultLanguage);
            $language = 'Iris\SysConfig\Settings::$DefaultLanguage = ' . "'$defaultLanguage';";
        }
        $changes = [
            '{CONTROLLER_DESCRIPTION}' => "This is the grand father of all controllers in the application",
            '{PHP_TAG}' => '<?php', // To avoid syntactic validation by IDE
            '{LocalLanguage}' => $language,
        ];
        foreach ($files as $template => $file) {
            $this->_createFile("$source/$template", "$destination/$file", $changes);
        }
        $this->_newModule($destination, "main");
        $parameters->saveProject();
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
        $programName = $parameters->getApplicationName();
        $projectDir = $parameters->getProjectDir();
        $destination = "$projectDir/$programName";
        if (!file_exists("$destination/modules/$module")) {
            $this->_newModule($destination, $module, $controller, $action);
            $parameters->saveProject();
        }
        elseif (!file_exists("$destination/modules/$module/controllers/$controller.php")) {
            $this->_newController($destination, $module, $controller, $action);
            $parameters->saveProject();
        }
        elseif (!file_exists("$destination/modules/$module/views/scripts/{$controller}_$action.iview")) {
            $this->_newAction($destination, $module, $controller, $action);
            $parameters->saveProject();
        }
        else {
            \Messages::Abort('ERR_EXISTING_MCA', "$module/$controller/$action");
        }
    }

    /**
     * Generates a new module with its default controller and action
     * 
     * @param string $destination The application directory name 
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     */
    private function _newModule($destination, $moduleName, $controllerName = 'index', $actionName = 'index') {
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/application';
        $directories = [
            "modules/$moduleName/controllers/helpers",
            "modules/$moduleName/views/layouts",
            "modules/$moduleName/views/scripts",
            "modules/$moduleName/views/helpers"
        ];
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
        Parameters::GetInstance()->setValue(Parameters::MODULENAME, $moduleName);
        $this->_newController($destination, $moduleName, $controllerName, $actionName);
    }

    /**
     * Generates a new controller with its default action
     * 
     * @param type $destination The application directory name
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     */
    private function _newController($destination, $moduleName, $controllerName, $actionName = 'index') {
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        if ($moduleName == 'main' and $controllerName == 'index') {
            $title = '$this->callViewHelper("welcome",1)';
        }
        else {
            $title = "'<h1>$moduleName - $controllerName - $actionName</h1> '";
        }
        $controllerPath = "$destinationMod/controllers/$controllerName.php";
        if (file_exists($controllerPath)) {
            \Messages::Abort("ERR_EXISTING_C", $controllerName);
        }
        $parameters = Parameters::GetInstance();
        if ($parameters->getProject()->workbench) {
            $template = 'systemindex.php';
        }
        else {
            $template = 'index.php';
        }
        $this->_createFile("$source/$template", $controllerPath, [
            '{PHP_TAG}' => '<?php', // To avoid syntactic validation by IDE
            '{MODULE}' => $moduleName,
            '{MODULECONTROLLER}' => "_$moduleName",
            '{CONTROLLER}' => $controllerName,
            '{CONTROLLER_DESCRIPTION}' => "Description of $controllerName",
            '{TITLE}' => "$title"
                ]
        );
        Parameters::GetInstance()->setValue(Parameters::CONTROLLERNAME, $controllerName);
        \Messages::Display('MSG_CREATE_C', $controllerName);
        if ($actionName != 'index') {
            $this->_newAction($destination, $moduleName, $controllerName, "index");
        }
        $this->_newAction($destination, $moduleName, $controllerName, $actionName);
    }

    /**
     * Creates a new action
     * 
     * @param string $destination The application directory name
     * @param string $moduleName The module name
     * @param string $controllerName The controller name
     * @param string $actionName The action name
     */
    private function _newAction($destination, $moduleName, $controllerName, $actionName) {
        $source = IRIS_LIBRARY_DIR . '/CLI/Files/application';
        $destinationMod = "$destination/modules/$moduleName";
        $scriptName = "$destinationMod/views/scripts/{$controllerName}_$actionName.iview";
        if (file_exists($scriptName)) {
            \Messages::Abort("ERR_EXISTING_A", $actionName);
        }
        // if a special standard script exists in the application, use it
        $defaultViewName = "$destination/modules/main/views/scripts/_DEFAULTVIEW.iview";
        if (file_exists($defaultViewName)) {
            $this->_createFile($defaultViewName, $scriptName);
        }
        // otherwise use a default script
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
        \$this->__(NULL, [
            'Title' => "<h1>$moduleName - $controllerName - $actionName</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
}

END;
            $content = implode('', $source);
            file_put_contents($controllerPath, $content);
        }
        Parameters::GetInstance()->setValue(Parameters::ACTIONNAME, $actionName);
        \Messages::Display('MSG_CREATE_A', $actionName);
    }

    /**
     * This function has been used to watermak all the library files with
     * the copyright notice. Each file had to contain a line containing
     * "* Project IRIS-PHP".
     * 
     * It is still functional, but useless
     * 
     * Example : 
     * find -name '*.php' -exec iris.php -o {} \;
     * 
     * @deprecated since version 2015
     */
    protected function _copyright() {
        $parameters = Parameters::GetInstance();
        $fileName = $parameters->getFileName();
        if (basename($fileName) == 'Code.php') {
            \Messages::Abort('ERR_AUTOCODE');
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
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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
