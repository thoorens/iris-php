<?php
namespace IrisInternal\documents\controllers;

use Iris\Documents\Manager;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
    }

    /**
     * A standart controller has to verify that the user is allowed to 
     * have access to it. This controller grants access to everybody
     */
    protected function _verifyAcl() {
        
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
        die('PUBLIC');
        $this->_manageFile(\TRUE, \TRUE);
    }
    
    /**
     * Download a protected file
     */
    public function protectedAction() {
        $this->_resource('protected');
    }

    /**
     * Download a private file
     */
    public function privateAction() {
        $this->_resource('private');
    }
    
    public function bgAction() {
        $this->_resource('bg');
    }

    /**
     * Download a css file from ILO
     */
    public function cssAction() {
        $this->_resource('css');
    }

    public function githubAction() {
        $this->_resource('github');
    }

    public function imagesAction() {
        $this->_resource('images');
    }

    public function logosAction() {
        $this->_resource('logos');
    }

    public function viewsAction() {
        $this->_resource('views');
    }

    public function javascriptAction(){
        $this->_resource('javascript');
    }
    
    private function _resource($base) {
        $manager = \Iris\Documents\Manager::GetInstance();
        $params = $this->_response->getParameters();
        array_unshift($params, $base);
        if (strpos('privateprotected', $base) == \FALSE) {
            $manager->getResource($params);
        }
        else {
            $manager->getFile(\FALSE, $params);
        }
        exit;
    }

    private function _manageFile($save) {
        $manager = \Iris\Documents\ Manager::GetInstance();
        $params = $this->_response->getParameters();
        switch ($manager->getFile($save, $params)) {
            case Manager::GOTIT:
                exit;
                break;
            case Manager::BADNUMBER:
                header('location:/error/document/oldlink');
            case Manager::NOTFOUND:
                header('location:/Error/document/notfound');
                //header('location:/Error/document/notfound');
        }
    }


}


