<?php

namespace modules\forms\controllers;

use Iris\Forms\_FormMaker as Maker;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * This is part of the WorkBench fragment
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This is a test for Autoform class
 */
class autoini extends _forms {

    protected function _init() {
        $this->setViewScriptName('common/auto');
        //\Iris\SysConfig\Settings::$DefaultFormClass = '\\Dojo\\Forms\\FormFactory';
        $this->__base = '/forms/autoform/';
    }

    /* ------------------------------------------------------------------------------
     * Forms made using an ini file
     * ------------------------------------------------------------------------------
     */

    /**
     * Tests and illustrates a form made with an ini file (HTML)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function HTMLAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatFile(Maker::MODE_NONE, $factoryType);
    }

    public function HTML_ModelAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatFile(Maker::MODE_ENTITY, $factoryType);
    }

    public function HTML_HandAction() {
        $factoryType = \Iris\Forms\_FormFactory::HTML;
        $this->_treatFile(Maker::MODE_HANDMADE, $factoryType);
    }

    /**
     * Tests and illustrates a form made with an ini file (Dojo)
     * 
     * @param string $dbManagerType Can force a change of entity manager type
     */
    public function DojoAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatFile(Maker::MODE_NONE, $factoryType);
    }

    public function Dojo_ModelAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatFile(Maker::MODE_ENTITY, $factoryType);
    }

    public function Dojo_HandAction() {
        $factoryType = \Iris\Forms\_FormFactory::DOJO;
        $this->_treatFile(Maker::MODE_HANDMADE, $factoryType);
    }

    /**
     * 
     * @param type $dbManagerType
     * @param type $factoryType
     */
    protected function _treatFile($secondary, $factoryType) {
        if ($secondary === Maker::MODE_ENTITY) {
            
        }
        else if ($secondary === Maker::MODE_HANDMADE) {
            
        }
        $maker = new \Iris\Forms\Makers\IniFile(IRIS_PROGRAM_PATH . '/config/forms/example.ini', $factoryType);
        $this->__form = $maker->formRender();
    }

}
