<?php

namespace modules\db\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of new
 * 
 * @author 
 * @license 
 */
class newem extends _db {

    protected function _init() {

    }

    public function indexAction() { 
        // this Title var is required by the default layout defined in _db
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

}
