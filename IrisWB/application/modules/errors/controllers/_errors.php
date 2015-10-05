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

        $this->_setLayout('wberror');
        $this->__bodyColor = 'ORANGE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
        \Iris\Errors\Settings::$Controller = '/errordemo/Error';
        \Iris\Errors\Settings::$Keep = \TRUE;
        $this->callViewHelper('subtitle', 'Error demo');
    }

}
