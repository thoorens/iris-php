<?php

namespace modules\other\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Test of the views
 * 
 * @author jacques
 * @license not defined
 */
class views extends _other {

    public function indexAction() {
        $this->__required = '--- implicit ---';
    }

    public function explicitAction() {
        $scriptName = 'EXPLICIT2';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'pmain';
    }

    public function inheritedAction() {
        $scriptName = 'EXPLICIT';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'pmain';
    }

    public function inheritedImplicitAction(){
        $this->__required = '--- implicit ---';
    }
    
    public function commonAction() {
        $scriptPath = 'commons/third2';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/pmain2';
    }

    public function commonInheritedAction() {
        $scriptPath = 'commons/third';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/pmain';
    }

    public function testLoaderAction(){
        
    }
}
