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
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
        \Iris\SysConfig\Settings::DisableMD5Signature();
    }

    public function errorAction($number) {
        switch ($number) {
            case 1:
                $this->__message = 'Error in sequence';
        }
    }

    public function endAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::DisableMD5Signature();
    }

    public function dojoAction() {
        \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
    }

    public function tocAction() {
        $this->_setLayout('color');
        $this->callViewHelper('dojo_Mask');
        $this->__sequence = $this->getScreenList();
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::DisableMD5Signature();
    }

    public function controllerAction() {
        
    }

}
