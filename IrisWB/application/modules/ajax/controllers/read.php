<?php

namespace modules\ajax\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class read extends _ajax {

    /*
     * Everything in the view scripts ! This is only a demo.
     * Each view has a minimum title and one or two Ajax calls
     */
    
    /**
     * This action adds information directly in the screen
     * through an Ajax request
     */
    public function getAction() {
        
    }

    /**
     * This action adds information in the screen after a short
     * 5 second delay
     */
    public function timeAction() {
        
    }

    /**
     * This action adds information in the screen when the user
     * clicks a button
     */
    public function clickAction() {
        
    }

    /**
     * This action adds information in the screen when an event
     * occurs (the mouse is over the button)
     */
    public function eventAction() {
        
    }
    /**
     * This action adds information in the screen when a message 
     * is sent to a receiver (the message is sent by the action method
     * but could be sent by another part of the code)
     */
    public function messageAction() {
        $this->callViewHelper('counter')->down('MSG1',5,'spanseconds',['test']);
    }
    
    /**
     * This action adds information in the screen when two messages 
     * are sent to receivers (the message are sent by the action method
     * but could be sent by another part of the code)
     */
    public function messagesAction(){
        // first way to call a view helper
        $this->callViewHelper('counter')->setId('counter1')->down('mes1',5,'spanseconds1',["hello"]);
        // second way more cryptic
        $this->_view->counter()->setId('counter2')->up('mes2',10,'spanseconds2',["hello","world"]);
        
    }

}
