<?php

namespace Iris\Admin;

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
 */

/**
 * A reflexion class on the project, populating a database with modules,
 * controllers and actions references.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Scanner {

    /**
     *
     * @var \Iris\DB\_Entity 
     */
    protected $_tModules;

    /**
     *
     * @var \Iris\DB\_Entity 
     */
    protected $_tControllers;

    /**
     *
     * @var \Iris\DB\_Entity 
     */
    protected $_tActions;

    /**
     *
     * @var array 
     */
    protected $_scannedModules = NULL;

    /**
     *
     * @var array 
     */
    protected $_scannedControllers = array();

    /**
     *
     * @var array 
     */
    protected $_scannedActions = array();

    public function clean($file) {
        $fileName = IRIS_PROGRAM_PATH . "/modules/$file";
        //$fileName = '/srv/Iris/library/Iris/Engine/Loader.php';
        if (!file_exists($fileName)) {
            $contenu = "Fichier inconnu : $fileName";
        }
        else {
            $contenu = file_get_contents($fileName);
            self::RemoveComments($contenu);
            //$contenu = highlight_string($contenu, TRUE);
        }
        return explode("\n", $contenu);
    }

    public function __construct() {
        $this->_tModules = new models\TModules;
    }

    public function collect() {
        $ModuleData = array();
        foreach ($this->_scannedModules as $module => $dummy) {
            $controllerData = array();
            foreach ($this->_scannedControllers[$module] as $controller => $dummy2) {
                $controllerData[$controller] = "Détails";
            }
            $ModuleData[$module] = $controllerData;
        }
        return $ModuleData;
    }

    /**
     * Scans all the application in search of modules, controllers and actions
     */
    public function scanApplication() {
        $this->_moduleScan();
        //return;
        // controller loop
        foreach ($this->_scannedModules as $moduleName => $moduleFileName) {
            $tModules = new models\TModules();
            $module = $tModules->fetchRow('ModuleName=', $moduleName);
            $this->_controllerScan($moduleName, $moduleFileName, $module->id);
            foreach ($this->_scannedControllers as $controllerName => $controllerFileName) {

                iris_debug($this->_scannedControllers);
                $actions = $this->_actionScan($moduleName, $controllerName, $controllerFileName);
            }
        }
    }

    /**
     * Scans the application directory in search of modules
     */
    protected function _moduleScan() {
//        $newC = new models\TControllers;
//        $newC->createRow();
        $modules = array();
        $path = IRIS_PROGRAM_PATH . "/modules";
        $dir = new \DirectoryIterator($path);
        $tModule = new models\TModules();
        $tModule->getEntityManager()->directSQL("Update Modules set Deleted = 1;");
        foreach ($dir as $file) {
            //$tModule =new models\TModules();
            $fileName = $file->getFilename();
            if ($file->isDir() and $fileName[0] != '.') {
                $tModule->where('ModuleName=', $fileName);
                $module = $tModule->fetchRow();
                if (is_null($module)) {
                    $module = $tModule->createRow();
                    $module->ModuleName = $fileName;
                }
                else {
                    $module->Deleted = \FALSE;
                }
                $module->save();
                $modules[$fileName] = "$path/$fileName";
            }
        }
        $this->_scannedModules = $modules;
    }

    protected function _controllerScan($moduleName, $moduleFileName, $moduleId) {
        $controllers = array();
        $tControllers = new models\TControllers();
        $tControllers->getEntityManager()->directSQL("Update Controllers set Deleted = 1;");
        $dir = new \DirectoryIterator("$moduleFileName/controllers");
        foreach ($dir as $file) {
            $fileName = $file->getFilename();
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            if ($file->isFile() and $extension == 'php') {
                $controllerName = basename($fileName, '.php');
                $controllers[$controllerName] = $file->getRealPath();
                $tControllers->where('ControllerName=',$controllerName);
                $tControllers->where('module_id=',$moduleId);
                $controller = $tControllers->fetchRow();
                if(is_null($controller)){
                    $controller = $tControllers->createRow();
                    $controller->ControllerName = $controllerName;
                    $controller->module_id = $moduleId;
                }
                else{
                    $controller->Deleted = \FALSE;
                }
                $controller->save();
            }
        }
        $this->_scannedControllers[$moduleName] = $controllers;
    }

    protected function _actionScan($module, $controlerName, $controllerFileName) {
        $fichier = $this->clean($controllerFileName);
    }

    /**
     * suppress comments in a php file passed as a string
     * 
     * http://php.net/manual/fr/function.preg-replace.php
     * @author erik.stetina@gmail.com
     * 
     * @param string $string
     * @return string
     */
    public static function RemoveComments(& $string) {
        $string = preg_replace("%(#|;|(//)).*%", "", $string);
        $string = preg_replace("%/\*(?:(?!\*/).)*\*/%s", "", $string); // google for negative lookahead
        return $string;
    }

}

?>
