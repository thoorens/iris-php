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

    public function clean($fileName) {
        if (!file_exists($fileName)) {
            $contenu = "Fichier inconnu : $fileName";
        }
        else {
            $contenu = file_get_contents($fileName);
//            self::RemoveComments($contenu);
//            self::RemoveBlancs($contenu);
        }
        return explode("\n", $contenu);
    }

    public function __construct() {
        $this->_tModules = \Iris\Admin\models\TModules::GetEntity();
        $this->_tControllers = \Iris\Admin\models\TControllers::GetEntity();
        $this->_tActions = models\TActions::GetEntity();
    }

//    public function collect() {
//        $ModuleData = array();
//        foreach ($this->_scannedModules as $module => $dummy) {
//            $controllerData = array();
//            foreach ($this->_scannedControllers[$module] as $controller => $dummy2) {
//                $controllerData[$controller] = "Détails";
//            }
//            $ModuleData[$module] = $controllerData;
//        }
//        return $ModuleData;
//    }

    public function getModules() {
        $tModules = $this->_tModules;
        $tModules->where('Deleted=', 0)->order('Name');
        return $tModules->fetchAll();
    }

    public function getControllers($moduleId) {
        $tControllers = $this->_tControllers;
        $tControllers->where('Deleted=', 0)
                ->where('module_id=',$moduleId)
                ->order('Name');
        return $tControllers->fetchAll();
    }

    public function getActions($controllerId) {
        $tActions = $this->_tActions;
        $tActions->where('Deleted=', 0)
                ->where('controller_id=',$controllerId)
                ->order('Name');
        return $tActions->fetchAll();
    }

    /**
     * Scans all the application in search of modules, controllers and actions
     */
    public function scanApplication() {
        $tModules = $this->_tModules;
        // Before scanning mark all objects as deleted
        $tModules->markDeleted('modules');
        $tModules->markDeleted('controllers');
        $tModules->markDeleted('actions');
        $modules = $this->_moduleScan();
        foreach ($modules as $moduleName => $moduleFileName) {
            $module = $tModules->fetchRow('Name=', $moduleName);
            $controllers = $this->_controllerScan($moduleName, $moduleFileName, $module->id);
            foreach ($controllers as $controllerName => $controllerFileName) {
                $this->_actionScan($module->id, $controllerName, $controllerFileName);
            }
        }
        \Iris\Admin\models\TAdmin::LastUpdate();
    }

    /**
     * Scans the application directory in search of modules. 
     * Old modules are marked as deleted
     */
    protected function _moduleScan() {
        $modules = array();
        $path = IRIS_PROGRAM_PATH . "/modules";
        $dir = new \DirectoryIterator($path);
        $tModule = $this->_tModules;
        foreach ($dir as $file) {
            $fileName = $file->getFilename();
            if ($file->isDir() and $fileName[0] != '.') {
                $tModule->undeleteOrInsert([$fileName], [$fileName]);
                $modules[$fileName] = "$path/$fileName";
            }
        }
        return $modules;
    }

    protected function _controllerScan($moduleName, $moduleFileName, $moduleId) {
        $controllers = array();
        $tControllers = $this->_tControllers;
        $dir = new \DirectoryIterator("$moduleFileName/controllers");
        foreach ($dir as $file) {
            $fileName = $file->getFilename();
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            if ($file->isFile() and $extension == 'php' and $fileName[0]!='_') {
                $controllerName = basename($fileName, '.php');
                $controllers[$controllerName] = $file->getRealPath();
                $tControllers->undeleteOrInsert([$controllerName, $moduleId]);
            }
        }
        return $controllers;
    }

    protected function _actionScan($moduleId, $controlerName, $controllerFileName) {
        $fichier = $this->clean($controllerFileName);
        foreach ($fichier as $line) {
            if (preg_match('/function (.*)Action/', $line)) {
                $actionName = preg_replace('/.*function (.*)Action.*/', '$1', $line);
                $tControllers = $this->_tControllers;
                $tControllers->where('module_id=', $moduleId)
                        ->where('Name=', $controlerName);
                $controllerId = $tControllers->fetchRow()->id;
                $tActions = $this->_tActions;
                $tActions->undeleteOrInsert([$actionName, $controllerId]);
            }
        }
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
    }

    public static function RemoveBlancs(& $string) {
        $string = preg_replace("/^ *[^a-zA-Z<\$-]/m", "", $string);
    }

}


