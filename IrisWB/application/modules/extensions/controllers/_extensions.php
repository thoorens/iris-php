<?php

namespace modules\extensions\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _extensions extends \modules\_application {

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        // these parameters are only for demonstration purpose
        // and required by the default layout defined in _extension
        $this->__buttons = 1 + 4;
        $this->__logoName = 'mainLogo';
        $this->callViewHelper('subtitle', 'Extensions');
    }

}
