<?php

namespace modules\payoff\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of _payoff
 * 
 * @author 
 * @license 
 */
class _payoff extends \modules\_application {

    
    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('!irisShadow');
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in _payoff
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
    }

}
