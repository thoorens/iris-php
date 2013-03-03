<?php

namespace modules\ajax\controllers;
/*
 * This file is part of IRIS-PHP.
 * 
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2011-2013 Jacques THOORENS
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
        $this->_view->counter()->setId('counter2')->up('mes2',10,'spanseconds2',["hello","world"]);
        
    }

}
