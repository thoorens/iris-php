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
 * @copyright 2011-13 Jacques THOORENS
 */

/**
 * This helper permits to turn on or out some parts of the screen
 * by producing code in AnimationManager and a string of parameters
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Turn extends _Sequence {

    /**
     * This view helper is a singleton
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * The standard transition time between two states (redefined)
     * 
     * @var int
     */
    protected $_standardDuration = 500;

    /**
     * For this class, it is necessary to use the Animator
     */
    protected function _subclassInit() {
        $this->_animateSynchro();
        $this->_animatorSubhelper->addModule("dojo/_base/fx", "fx");
        $this->_animatorSubhelper->addModule("dojo/dom-style", "style");
    }

    /**
     * Creates a ON event for an object at a given time, with an optional transition duration
     * 
     * @param string $target The affected object
     * @param int $startTime The start time
     * @param int $duration The option duration (if null, takes standard)
     */
    public function on($target, $startTime, $duration = \NULL) {
        $this->_initialOpacity();
        $this->_computeStartTime($startTime);
        $this->_eventCreate('turnon', $target, $startTime, $duration, 0);
        $code = <<<CODE

                            if(event.dojofunction == 'turnon'){
                                fx.fadeIn({node: event.node,duration: event.duration}).play();
                            }
                
CODE;
        $this->_insertSequenceCode('turnon', $code);
    }

    /**
     * Creates a OUT event for an object at a given time, with an optional transition duration
     * 
     * @param string $target The affected object
     * @param int $startTime The start time
     * @param int $duration The option duration (if null, takes standard)
     */
    public function out($target, $startTime, $duration = \NULL) {
        $this->_initialOpacity();
        $this->_computeStartTime($startTime);
        $this->_eventCreate('turnout', $target, $startTime, $duration, 1);
        $code = <<<CODE

                            if(event.dojofunction == 'turnout'){
                                fx.fadeOut({node: event.node,duration: event.duration}).play();
                            }
                
CODE;
        $this->_insertSequenceCode('turnout', $code);
    }

}

