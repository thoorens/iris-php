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
 *
 */

/**
 * This helper will provides basic mechanisms for animation in Dojo
 * in interaction with other script
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Animation extends _DojoHelper implements \Dojo\Engine\iLateScriptProvider {

    protected static $_Singleton = \TRUE;

    /**
     * The last event time from beginning of screeen (shared by all animation classes)
     * 
     * @var type 
     */
    private static $_LastTime = 0;

    /**
     * The standard transition time between two states (to be redefined in daughter classes)
     * 
     * @var int
     */
    private $_standardDuration = 0;

    /**
     *
     * @var \Dojo\Subhelpers\Animator
     */
    protected $_animatorSubhelper;

    /**
     *
     * @var \Dojo\Engine\CodeContainer
     */
    protected $_codeContainer = \NULL;
    
    
    /**
     * Returns the unique instance for later use
     * (each subclass has its proper instance)
     * 
     * @return static
     */
    public function help() {
        return $this;
    }
    

    /**
     * This method may initialise the Animator subhelper
     * and its CodeContainer if necessary
     */
    protected function _animateSynchro() {
        $this->_animatorSubhelper = \Dojo\Subhelpers\Animator::GetInstance();
        \Dojo\Engine\NameSpaceItem::GetObject('commonAnimation')->createLateFunction($this, 'args');
        $this->_codeContainer = $this->_animatorSubhelper->getCodeContainer();
    }

    /**
     * Converts relatives time (negative) to absolute if necessary
     * 
     * @param int $startTime the starting time (if <0, considered as relative)
     * @return int
     */
    protected function _computeStartTime(&$startTime) {
        if ($startTime < 0) {
            $startTime = self::$_LastTime - $startTime;
        }
        self::$_LastTime = $startTime;
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

    /**
     * Common part of all the method: a namespace wrapped javascript function which does all the job.
     * 
     * @param string $bubbleName
     * @return \Dojo\Engine\Bubble
     */
    protected function _createBubble($bubbleName) {
        $bubble = \Dojo\Engine\Bubble::GetBubble($bubbleName);
        $bubble->addModule("dojo/domReady!", '');
        return $bubble;
    }

    

    /**
     * Generates the javascript code for all subroutines required by
     * the helpers which uses the Animator subhelper.
     * 
     * @return string Javascript code for all animation classes
     */
    public function getLateScript() {
        return $this->_animatorSubhelper->getCodeContainer()->render();
    }
    
    /**
     * Some animation effects need an initialization. This code contains
     * references to the iris_dojo namespace and to the sequenceEvents
     * mechanism in subclass _Sequence
     * 
     * @staticvar type $done This code is only executed once.
     */
    protected function _initialOpacity() {
        static $done = \FALSE;
        if (!$done) {
            $done = \TRUE;
            $prefixCode = $this->_animatorSubhelper->getPrefixCode();
            $prefixCode->addPieceOfCode('opacity', <<< SCRIPT
                    
        // Style the dom node to opacity 0 or 1;
        if(args.node != 'sequenceEvents'){
            style.set(args.node, "opacity", args.opacity);
        }
        else{
            for(var i in iris_dojo.sequenceEvents){
                event = iris_dojo.sequenceEvents[i];
                if(event.opacity != -1){    
                    style.set(event.node, "opacity", event.opacity);
                }
            }
        }

SCRIPT
            );
        }
    }

}

