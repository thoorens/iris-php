<?php

namespace modules\other\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * 
 * In work bench tests some layout configurations
 * @author jacques
 * @license not defined
 */
class layout extends _other {

    public function indexAction() {
        
    }

    public function moduleAction() {
        
    }

    public function actionAction() {
        
    }

    
    public function deleteAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>other - layout - delete</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
