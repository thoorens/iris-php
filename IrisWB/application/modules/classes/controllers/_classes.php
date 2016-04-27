<?php

namespace modules\classes\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of _classes
 * 
 * @author 
 * @license 
 */
class _classes extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('application');
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in _classes
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
    }

}
