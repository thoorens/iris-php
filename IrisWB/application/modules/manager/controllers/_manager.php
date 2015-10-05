<?php

namespace modules\manager\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of {CONTROLLER}
 * 
 * @author jacques
 * @license not defined
 */
class _manager extends \modules\_application {

    use \Iris\DB\DataBrowser\tCrudManager;

    /**
     * This method can contain module level
     * settings
     */
    protected final function _moduleInit() {
        // You should modify this demo layout
        $this->_setLayout('manager');
        $this->__bodyColor = 'ORANGE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
        $this->_changeCrudDirectory('\\models_internal\\crud\\');
        $this->callViewHelper('subtitle', 'Internal manager');
    }

}
