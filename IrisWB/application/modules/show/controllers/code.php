<?php

namespace modules\show\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of code
 * 
 * @author jacques
 * @license not defined
 */
class code extends _show {

    public function __callAction($actionName, $parameters) {
        \Iris\SysConfig\Settings::DisableMD5Signature();
        $this->__module = substr($actionName, 0, strlen($actionName) - 6);
        $this->__controller = $parameters[0];
        $this->__action = $parameters[1];
        $this->__bodyColor = 'GREEN3';
        $this->_setLayout('color');
        $this->setViewScriptName('all');
    }

}
