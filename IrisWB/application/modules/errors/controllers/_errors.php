<?php

namespace modules\errors\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _errors extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected function _moduleInit() {
        
        $this->_setLayout('application');
        $this->__bodyColor = 'ORANGE3';
        $this->_noMd5();

    }

}
