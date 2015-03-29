<?php

namespace modules\autodb\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of _autodb
 * 
 * @author 
 * @license 
 */
class _autodb extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('!irisShadow');
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in _autodb
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
    }

}
