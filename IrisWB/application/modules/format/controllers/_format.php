<?php

namespace modules\format\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of _format
 * 
 * @author 
 * @license 
 */
class _format extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('application');
        $this->__bodyColor = 'BLUE3';
    }

}
