<?php

namespace Dojo\views\helpers;

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
 * Produces a JSON string intended to turn on and out a series of object
 * at different time (used by Ajax requests or internally).  
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Sequence extends _Animation {

    /**
     * The collection of distinct events to memorize
     * @var array
     */
    private static $_Events = array();

    /**
     * If TRUE (default), render() produces a pure code JSON, otherwise
     * it inits a javascript internal variable
     *  
     * @var boolean
     */
    private $_ajaxMode = \FALSE;

    /**
     * Returns a JSON string corresponding to all the events
     * or a javascript object.
     * 
     * @return string 
     */
    public function render() {
        if ($this->_ajaxMode) {
            return json_encode(self::$_Events);
        }
        else {
            return $this->_jsRender();
        }
    }

    /**
     * Creates a javascript variable containing all the event definitions
     */
    private function _jsRender() {
        $json = json_encode(self::$_Events);
        /* @var $namespace \Dojo\Engine\NameSpaceItem */
        $namespace = \Dojo\Engine\NameSpaceItem::GetObject('sequenceEvents');
        $namespace->createVar(CRLF.$json.CRLF);
    }

    /**
     * Creates a a entry point for a sequence
     * @param string $signal
     */
    public function sequence($signal) {
        $this->_createBubble('sequence')
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "sequenceEvents",
        signal : "$signal",
        dojofunction : "sequence" ,
    }
    iris_dojo.commonAnimation(io_args); 
   
SCRIPT
        );
    }

    /**
     * Add a piece a code in the sequence part of animation manager, creating the sequence item if 
     * necessary.
     * 
     * @param string $name
     * @param string $code
     */
    protected function _insertSequenceCode($name, $code) {
        $sequenceCodeContainer = $this->_animatorSubhelper->getAnimationManager()->getPieceOfCode('sequence');
        if (!$sequenceCodeContainer->hasHeader()) {
            $header = <<< HEADER
            if(args.dojofunction=='sequence'){
                topic.subscribe(args.signal, function(time){
                    for(i in iris_dojo.sequenceEvents){
                         event = iris_dojo.sequenceEvents[i];
                         if(time==event.starttime){
HEADER;
            $tail = <<< TAIL
                         }
                    }
                });
            }   
TAIL;
            $sequenceCodeContainer->setHeader($header);
            $sequenceCodeContainer->setTail($tail);
        }
        $sequenceCodeContainer->addPieceOfCode($name, $code);
    }

    /**
     * Integrator for easy use : create a variable to manage the instance and
     * generate the sequence receiver for autoAninmation
     * 
     * @param string $varName the short cut variable name in view
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
    protected function _eventCreate($cmd, $target, $time, $duration, $opacity) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $fieldNames = ['dojofunction', 'node', 'starttime', 'duration', 'opacity'];
        $eventParam = [$cmd, $target, $time, $duration, $opacity];
        $event = array_combine($fieldNames, $eventParam);
        // if the same node has a previous event attached to it, opacity will be ignored
        foreach (self::$_Events as $ev) {
            if ($ev['node'] == $target) {
                $event['opacity'] = -1; // don't manage opacity
                break;
            }
        }
        self::$_Events[] = $event;
    }
}
