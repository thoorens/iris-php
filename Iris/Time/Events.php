<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Iris\Time;

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
 * @copyright 2012 Jacques THOORENS
 */


/**
 * Description of Events
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Events {
    
    /**
     *
     * @var iEvent[]
     */
    private $_events = array();
    
    /**
     *
     * @var Date
     */
    private $_date = \NULL;
    
    /**
     *
     * @var type 
     */
    private $_rendering = \NULL;
    
    /**
     * 
     * @param iEvent $newEvent
     */
    public function addEvent($newEvent, $date){
        // an event can't be add twice so we use its id to store it
        $this->_events[$newEvent->getId()] = $newEvent;
        if(is_null($this->_date)){
            $this->_date = clone $date;
        }
    }
    
    public function showMonth($renderer){
        $eventCounter = $this->eventNumber();
        if($eventCounter < 2){
            $uniqueEvent = array_shift($this->_events);
            return $renderer->eventDisplay($uniqueEvent);
        }
        else{
            return $renderer->collision($this);
        }
        
    }
    
    public function eventNumber(){
        return count($this->_events);
    }
    
    public function showWeek($renderer){
        return $this->showMonth($renderer);
    }
    
    public function render(){
        return $this->_rendering;
    }
    
    public function hasRendering(){
        return ! is_null($this->_rendering);
    }
    
    public function setEmpty($date, $defaultText){
        $this->_date = clone $date;
        $this->_rendering = $defaultText;
    }
    
    public function isEmpty(){
        return $this->eventNumber() == 0;
    }
    
    public function getDate() {
        return $this->_date;
    }


}

