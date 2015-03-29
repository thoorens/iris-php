<?php

namespace modules\jquery\controllers;

/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of slideshow
 * 
 * @author 
 * @license 
 */
class slideshow extends _jquery {

    public function indexAction() {
        // this Title var is required by the default layout defined in _jquery
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    public function index2Action() {
        // this Title var is required by the default layout defined in _jquery
        $this->__Title = $this->callViewHelper('styleLoader','design.css');
    }

   public function index3Action() {
        // this Title var is required by the default layout defined in _jquery
        $this->__Title = $this->callViewHelper('styleLoader','design.css');
    } 
    
}
