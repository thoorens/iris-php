<?php

namespace modules\ajax\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of get
 * 
 * @author jacques
 * @license not defined
 */
class get extends _ajax {

    /**
     * These message action are meant to be read by Ajax requests 
     */
    
    public function message1Action() {
        $this->_ajaxMode('text/html');
        $this->__message = 'This text has been read directly from the server.';
        $this->setViewScriptName('message');
    }

    public function message2Action() {
        $this->_ajaxMode('text/html');
        $this->__message = 'This text has been read after five seconds from the server';
        $this->setViewScriptName('message');
    }

    public function message3Action() {
        $this->_ajaxMode('text/html');
        $this->__message = 'This text has been sent by the server after the button 
            has been clicked (this action may be repeated)';
        $this->setViewScriptName('message');
    }

    public function message4Action() {
        $this->_ajaxMode('text/html');
        $this->__message = 'This text has been sent by the server after the mouse cursor 
            has been moved over the button (this action may be repeated)';
        $this->setViewScriptName('message');
    }

    public function message5Action() {
        $this->_ajaxMode('text/html');
        $this->__message = 'This text has been sent by the server after a message has been sent by a counter';
        $this->setViewScriptName('message');
    }

    public function message6Action($arg) {
        $this->_ajaxMode('text/html');
        $this->__arg = $arg;
        $this->__message = "This text has been sent by the server after a message with argument '$arg'
            has been sent by a down counter.";
        $this->setViewScriptName('message6');
    }

    public function message7Action($arg1, $arg2) {
        $this->_ajaxMode('text/html');
        $this->__arg1 = $arg1;
        $this->__arg2 = $arg2;
        $this->__message = "This text has been sent by the server after a message 
            with arguments '$arg1' and '$arg2' has been sent by an up counter.";
        $this->setViewScriptName('message7');

    }

}
