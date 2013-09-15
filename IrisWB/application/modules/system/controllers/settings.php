<?php

namespace modules\system\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of settings
 * 
 * @author jacques
 * @license not defined
 */
class settings extends _system {

    public function mainAction() {
        $this->__settings = \Iris\SysConfig\Settings::Debug(\FALSE);
        $this->setViewScriptName('all');
    }

    public function dojoAction() {
        $this->__settings = \Dojo\Engine\Settings::Debug(\FALSE);
        $this->setViewScriptName('all');
    }

    public function errorAction() {
        $this->__settings = \Iris\Errors\Settings::Debug(\FALSE);
        $this->setViewScriptName('all');
    }

    public function iniAction() {
        $this->__defaultsettings = \modules\system\classes\Settings::Debug(\FALSE);
        $filePath = IRIS_PROGRAM_PATH . '/modules/system/config/settings.ini';
        $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
        $params = $parser->processFile($filePath, FALSE);
        \modules\system\classes\Settings::FromConfigs($params);
        $this->__modifiedsettings = \modules\system\classes\Settings::Debug(\FALSE);
        $this->setViewScriptName('');
    }

    public function managementAction() {
        $this->__defaultsettings = \modules\system\classes\Settings::Debug(\FALSE);
        $this->__backgroundColor1 = \modules\system\classes\Settings::GetBackgroundColor();
        \modules\system\classes\Settings::SetBackgroundColor('blue');
        $this->__backgroundColor2 = \modules\system\classes\Settings::GetBackgroundColor();
        $this->__layout1 = \modules\system\classes\Settings::HasLayout();
        \modules\system\classes\Settings::DisableLayout();
        $this->__layout2 = \modules\system\classes\Settings::HasLayout();
        \modules\system\classes\Settings::EnableLayout();
        $this->__layout3 = \modules\system\classes\Settings::HasLayout();
        
        
    }

}
