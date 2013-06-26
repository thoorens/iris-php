<?php

namespace modules\dojo\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of extension
 * 
 * @author jacques
 * @license not defined
 */
class extension extends _dojo {

    protected function _init() {
        $this->_setLayout('simple');
    }

    
    public function declarativeAction() {
        $this->_setLayout('simple');
        // this Title var is required by the default layout defined in _dojo
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    public function programmatiqueAction(){
        
    }

    public function usefulAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>dojo - extension - usefull</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
