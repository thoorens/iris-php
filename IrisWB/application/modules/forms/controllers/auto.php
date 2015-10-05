<?php

namespace modules\forms\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of auto
 * 
 * @author 
 * @license 
 */
class auto extends _forms {

    public function indexAction() {
        // this Title var is required by the default layout defined in _forms
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

    public function nodefAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>forms - auto - nodef</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
