<?php

namespace modules\db\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _db extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected function _moduleInit() {
        $this->_setLayout('database');
        $this->registerSubcontroller(1, 'menu');
    }

    
    
}
