<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of animation
 * 
 * @author jacques
 * @license not defined
 */
class animation extends _dojo {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>dojo - animation - index</h1> ',
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

    
    public function fadeinAction(){
        
    }
    
    public function scrollAction() {
        
    }
}
