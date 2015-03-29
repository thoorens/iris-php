<?php

namespace modules\autodb\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of index
 * 
 * @author 
 * @license 
 */
class index extends _autodb {

    public function indexAction() {
        // this Title var is required by the default layout defined in _autodb
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

}
