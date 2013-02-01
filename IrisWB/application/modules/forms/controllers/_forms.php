<?php

namespace modules\forms\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _forms extends \modules\_application {

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('main');
        $this->__bodyColor = 'ORANGE3';
        // choose Dojo type forms
        $ff = new \Dojo\Forms\FormFactory();
        $ff->setDefault();
    }

}
