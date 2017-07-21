<?php

namespace modules\security\controllers;

/**
 * Project : srv_www_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of forms
 * 
 * @author 
 * @license 
 */
class forms extends _security {

    public function indexAction() {
        // this Title var is required by the default layout defined in _security
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    public function postAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - post</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
    public function getAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - get</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
    public function cookieAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - cookie</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
    public function serverAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - server</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
    public function envAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - env</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
    public function analyseAction($type) {
        // these parameters are only for demonstration purpose
        $this->__(NULL, [
            'Title' => "<h1>security - forms - analyse</h1>",
            'buttons' => 1+4,
            'logoName' => 'mainLogo']);
    }
}
