<?php

namespace modules\extensions\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _extensions {

    public function indexAction() {
        // this Title var is required by the default layout defined in _extension
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

    public function coreAction() {
        $this->__text = "This text is a simple sentence.";
    }
}
