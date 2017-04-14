<?php

namespace modules\main\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

class index extends _main {

    /**
     * Set a layout at controller level
     */
    public function _init() {
        $this->_setLayout('controller');
    }

    /**
     * The home page of the workbench
     */
    public function indexAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    /**
     * An error screen with a normal layout
     * 
     * @param int $number the item number of the error
     */
    public function errorAction($number=0) {
        switch ($number) {
            case 1:
                $this->__message = 'Error in sequence';
                break;
            case 0:
                $this->__message = 'intentional error';
                break;
            default:
                $this->__message = 'unkown error';
                break;
        }
    }

    public function endAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    public function dojoAction() {
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
    }

    /**
     * The table of content of the project
     */
    public function tocAction() {
        $this->_setLayout('color');
        $this->callViewHelper('dojo_Mask');
        $this->__sequence = $this->getScreenList();
        $this->__bodyColor = 'BLUE3';
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    /**
     * A somple page to show a controller defined layout
     */
    public function controllerAction() {
        
    }

}
