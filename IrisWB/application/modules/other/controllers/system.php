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
class system extends _other {

    

    public function inheritedAction() {
        $scriptName = 'EXPLICIT';
        $this->setViewScriptName($scriptName);
        $this->__required = $scriptName;
        $this->__reqPartial = 'psystem';
    }

    public function inheritedImplicitAction(){
        $this->__required = '--- implicit ---';
    }
    
    
    public function commonInheritedAction() {
        $scriptPath = 'commons/system';
        $this->setViewScriptName($scriptPath);
        $this->__required = $scriptPath;
        $this->__reqPartial = 'commons/psystem';
    }

}
