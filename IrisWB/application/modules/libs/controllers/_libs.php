<?php

namespace modules\libs\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _libs extends \modules\_application {

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        $this->callViewHelper('subtitle', 'Libraries');
    }

}
