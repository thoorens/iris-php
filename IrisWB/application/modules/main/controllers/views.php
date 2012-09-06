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
class views extends _main {

    protected function _init(){
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE1';
    }


    public function indexAction() {
        $this->__required = '--- implicit ---';
    }

    public function explicitAction() {
        $scriptName = 'EXPLICIT';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'pmain';
    }

    public function commonAction() {
        $scriptPath = 'commons/third';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/pmain';
    }

}
