<?php

namespace modules\errors\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Test of variable availability
 * 
 * @author jacques
 * @license not defined
 */
class variables extends _errors {

    public function viewAction() {
        $this->__var1 = "variable1";
        $this->__var2 = "variable2";
        $this->__var3 = "variable3";
    }
    
    public function partialAction(){
        $this->__var = 'Test';
    }

    public function partialPrivateAction(){
        $this->__var1 = 'Variable 1 from controller';
        $this->__var2 = 'Variable 2 from controller';
    }
    
    public function isletAction() {
        
    }
}
