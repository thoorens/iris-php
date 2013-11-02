<?php

namespace modules\subhelpers\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _subhelpers extends \modules\_application {

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        $this->callViewHelper('subtitle', 'Subhelpers');
    }

}
