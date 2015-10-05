<?php

namespace modules\main\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Test of the views
 * 
 * @author jacques
 * @license not defined
 */
class system extends _main {

    protected function _init() {
        $this->_setLayout('color');
        $this->__bodyColor = 'ORANGE1';
    }

    public function indexAction() {
        $this->__required = '--- implicit ---';
    }

    public function explicitAction() {
        $scriptName = 'EXPLICIT';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'psystem';
    }

    public function commonAction() {
        $scriptPath = 'commons/system';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/psystem';
    }

    
    public function iloAction() {
        $this->setViewScriptName('/ILO#/ilo');
    }
}
