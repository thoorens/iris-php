<?php

namespace modules\system\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _system extends \modules\_application {

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        $this->callViewHelper('subtitle', 'System');
    }

}
