<?php

namespace Dojo\views\helpers;

defined('CRLF') or define('CRLF', "\n");
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
 * Produces a JSON string intended to turn on and out a series of object
 * at different time (used by Ajax requests) 
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Turn extends AutoAnimation {

    /**
     * This view helper is a singleton
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * The collection of distinct events to memorize
     * @var array
     */
    private $_events = array();

    /**
     * The standard transition time between two states
     * 
     * @var int
     */
    private $_standardDuration = 500;

    /**
     * If TRUE (default), render() produces a pure code JSON, otherwise
     * it inits a javascript internal variable
     *  
     * @var boolean
     */
    private $_ajaxMode = \TRUE;

    /**
     * Returns the unique instance for later use
     * @return \Tutorial\views\helpers\Turn
     */
    public function help() {
        return $this;
    }

    /**
     * Creates a ON event for an object at a given time, with an optional transition duration
     * 
     * @param string $target The affected object
     * @param int $startTime The start time
     * @param int $duration The option duration (if null, takes standard)
     */
    public function on($target, $startTime, $duration = \NULL) {
        $this->_computeStartTime($startTime);
        $this->_eventCreate('turnon', $target, $startTime, $duration, 0);
    }

    /**
     * Creates a OUT event for an object at a given time, with an optional transition duration
     * 
     * @param string $target The affected object
     * @param int $startTime The start time
     * @param int $duration The option duration (if null, takes standard)
     */
    public function out($target, $startTime, $duration = \NULL) {
        $this->_computeStartTime($startTime);
        $this->_eventCreate('turnout', $target, $startTime, $duration, 1);
    }

    /**
     * Returns a JSON string corresponding to all the events
     * 
     * @return string 
     */
    public function render() {
        if ($this->_ajaxMode) {
            return json_encode($this->_events);
        }
        else {
            return $this->jsRender();
        }
    }

    public function sequence($signal) {
        $this->_createBubble('sequence')
        ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "turnEvents",
        signal : "$signal",
        dojofunction : "sequence" ,
    }
    iris_dojo.commonFade(io_args); 
   
SCRIPT
        );
    }

    
    /**
     * Creates a javascript variable containing all the event definitions
     */
    public function jsRender() {
        $render = json_encode($this->_events);
        /* @var $namespace \Dojo\Engine\DNameSpace */
        $namespace = \Dojo\Engine\DNameSpace::GetObject('turnEvents');
        $namespace->createVar(<<<JS
$render
;
JS
        );
    }

    /**
     * Integrator for easy use : create a variable to manage the instance and
     * generate the sequence receiver for autoAninmation
     * 
     * @param string $varName the short cut variable name
     * @param string $signal The signal name
     * @param boolean $ajaxMode Select Ajax mode if necessary
     */
    public function prepare($varName, $signal, $ajaxMode) {
        $this->sequence($signal);
        $this->_view->$varName = $this;
        $this->_ajaxMode = $ajaxMode;
    }

    /**
     * Creates an event (turning on or out an object)
     * 
     * @param string $cmd The javascript function to call
     * @param string $target The target object to turn
     * @param int $time The start time of the event (if negative, relative to previous)
     * @param int $duration The optional transition duration (if unspecified, take standard)
     * @param int $opacity The initial opacity (1 or 0)
     */
    private function _eventCreate($cmd, $target, $time, $duration, $opacity) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $fieldNames = ['dojofunction', 'node', 'starttime', 'duration', 'opacity'];
        $event = [$cmd, $target, $time, $duration, $opacity];
        $event = array_combine($fieldNames, $event);
        foreach($this->_events as $ev){
            if($ev['node'] == $target){
                $event['opacity'] = -1; // don't manage opacity
                break;
            }
        }
        $this->_events[] = array_combine($fieldNames, $event);
    }

    /**
     * Sets a standard transition duration for all coming events.
     * 
     * @param type $standardDuration
     * @return \Tutorial\views\helpers\Turn for fluent interface
     */
    public function setStandardDuration($standardDuration) {
        $this->_standardDuration = $standardDuration;
        return $this;
    }

}
