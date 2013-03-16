<?php

namespace IrisInternal\documents\controllers;

use Iris\Documents\Manager;

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
 * An interface to Documents\Manager for file download
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class file extends \IrisInternal\main\controllers\_SecureInternal {

    /**
     * no security required, 
     * make sure Stopwatch won't spoil the end of the files
     */
    public function security() {
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
    }

    public function saveAction() {
        $this->_manageFile(TRUE);
    }

    public function readAction() {
        $this->_manageFile(FALSE);
    }

    /**
     * Download a public file
     */
    public function publicAction() {
        $this->_manageFile(\TRUE, \TRUE);
    }

    public function resourceAction() {
        $manager = Manager::GetInstance();
        $params = $this->_response->getParameters();
        $manager->getResource($params);
        exit;
    }

    public function tutorialAction(){
        $manager = Manager::GetInstance();
        $params = $this->_response->getParameters();
        $manager->getTutorial($params);
        exit;
    }
    
    private function _manageFile($save) {
        $manager = Manager::GetInstance();
        $params = $this->_response->getParameters();
        switch ($manager->getFile($save, $params)) {
            case Manager::GOTIT:
                exit;
                break;
            case Manager::BADNUMBER:
                header('location:/ERROR/document/oldlink');
            case Manager::NOTFOUND:
                header('location:/ERROR/document/notfound');
        }
    }


    protected function _verifyAcl() {
        
    }

}

?>
