<?php

namespace modules\test\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _test {

    public function indexAction() {
        // this Title var is required by the default layout defined in _test
        $this->__Title = "Test des classes Admin";
        $scanner = new \Iris\Admin\Scanner();
        $scanner->scanApplication();
        
    }
    
    

}
