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

    public function message1Action() {
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been read directly from the server.</p>
STOP
        ;
        }
    
    public function message2Action(){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been read after five seconds from the server.</p>
STOP
        ;
    }

    public function message3Action(){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been sent by the server after the button has been clicked (this action may be repeated).</p>
STOP
        ;
    }

     public function message4Action(){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been sent by the server after the mouse cursor has been moved over the button (this action may be repeated).</p>
STOP
        ;
    }
    public function message5Action(){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been sent by the server after a message has been sent by a counter.</p>
STOP
        ;
    } 
    
    public function message6Action($arg){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been sent by the server after a message has been sent by a counter with argument "$arg".</p>
STOP
        ;
    } 
    
    public function message7Action($arg1, $arg2){
        $this->_ajaxMode('text/plaintext');
        echo <<< STOP
<h3>Ajax</h3>
    <p>This text has been sent by the server after a message has been sent by a counter with argument "$arg1" and "$arg2".</p>
STOP
        ;
    } 
}
