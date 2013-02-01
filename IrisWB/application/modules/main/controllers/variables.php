<?php

namespace modules\main\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of variables
 * 
 * @author jacques
 * @license not defined
 */
class variables extends _main {

    protected function _init() {
        $this->_setLayout('main');
        $this->__bodyColor = 'ORANGE2';
    }

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__var1 = 'Initial content';
        $this->__array = [1, 2, 3];
    }

    public function layoutAction() {
        // these parameters are only for demonstration purpose
        $this->__var1 = 'Initial content';
        $this->__descVar1 = [
            htmlentities('<?=$this->var1?>'),
            htmlentities('{var1}'),
            htmlentities('<?=$var1?>'),
        ];
        $this->__array = [1, 2, 3];
        $this->_setLayout('special');
        $this->__bodyColor = 'ORANGE1';
    }

}
