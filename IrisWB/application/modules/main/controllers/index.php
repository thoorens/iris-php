<?php

namespace modules\main\controllers;

class index extends _main {

    /**
     * Set a layout at controller level
     */
    public function _init() {
        $this->_setLayout('controller');
    }

    public function indexAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    public function errorAction($number=0) {
        switch ($number) {
            case 1:
                $this->__message = 'Error in sequence';
        }
    }

    public function endAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    public function dojoAction() {
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
    }

    public function tocAction() {
        $this->_setLayout('color');
        $this->callViewHelper('dojo_Mask');
        $this->__sequence = $this->getScreenList();
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    public function controllerAction() {
        
    }

}
