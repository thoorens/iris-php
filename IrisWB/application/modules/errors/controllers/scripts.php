<?php

namespace modules\errors\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Script test in workbench
 * 
 * @author jacques
 * @license not defined
 */
class scripts extends _errors {

    public function viewAction() {
        $this->setViewScriptName('stupid');
    }

    public function partialAction() {
        
    }

    public function layoutAction() {
        $this->_setLayout('stupid');
    }

    public function isletAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>errors - scripts - islet</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
