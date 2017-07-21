<?php
namespace modules\ajax\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This controller creates different actions to test various Ajax mode.
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
        $this->callViewHelper('counter')->setId('counter2')->up('mes2',10,'spanseconds2',["hello","world"]);
        
    }

    
    public function zonesAction(){
        $this->__functionCode1 = <<<JS1
                alert('YES');
   domClass.add('zone1','yellowcolor');
JS1;
        $this->__functionCode2 = <<<JS2
   domClass.add('zone2','bluecolor');
JS2;
        $this->__functionCode3 = <<<JS3
   domClass.add('zone3','greencolor');
JS3;
        $this->__functionCode4 = <<<JS4
   domClass.add('zone4','pinkcolor');
JS4;
    }
}
