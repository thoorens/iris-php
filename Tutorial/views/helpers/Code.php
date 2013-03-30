<?php

namespace Tutorial\views\helpers;

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
 * An internal stack
 */
class Stack {

    private $_internalArray = [];
    private $_underflowMessage;

    public function __construct($underflowMessage, $initialValue = \NULL) {
        $this->_underflowMessage = $underflowMessage;
        if (!is_null($initialValue)) {
            $this->_internalArray = [$initialValue];
        }
    }

    public function push($element) {
        array_unshift($this->_internalArray, $element);
    }

    public function pop() {
        if (count($this->_internalArray) == 0)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return array_shift($this->_internalArray);
    }

    public function peek($level = 0) {
        if (count($this->_internalArray) < $level + 1)
            throw new \Iris\Exceptions\InternalException($this->_underflowMessage);
        return $this->_internalArray[$level];
    }

    public function isEmpty() {
        return count($this->_internalArray) == 0;
    }

}

/**
 * A helper for code display
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Code extends \Dojo\views\helpers\_Sequence {
    /**
     * In automode no synchro is used, time is relative to page loading
     */

    const AUTOMODE = \FALSE;
    /**
     * In managed mode, the system uses an external synchro
     */
    const MANAGEDMODE = \TRUE;

    /**
     * The helper is a singleton
     * @var boolean
     */
    protected static $_Singleton = \TRUE;

    /**
     * The id of the previous zone (used in end comment)
     * @var Stack 
     */
    private $_previous;

    /**
     * The tags used in the rendering of the text
     * 
     * @var Stack
     */
    private $_tags;

    /**
     * The user mode
     * 
     * @var Stack 
     */
    private $_userMode;
    
    /**
     *
     * @var int
     */
    private $_delay = 500;

    /**
     *
     * @var type 
     */
    private $_mode = self::MANAGEDMODE;

    private $_closed = \TRUE;
    
    
    /**
     * Use _animateSynchro only once if necessary 
     * 
     * @staticvar boolean $done To text previous execution
     */
    protected function _animateSynchro() {
        static $done = \FALSE;
        if (!$done) {
            parent::_animateSynchro();
            $done = \TRUE;
        }
    }

    protected function _subclassInit() {
        $this->_tags = new Stack('Inconsistency in tag management in a Tutorial_Code helper');
        $this->_previous = new Stack('Inconsistency in sequence management in a Tutorial_Code helper');
        $this->_userMode = new Stack('Inconsistency in sequence management in a Tutorial_Code helper');
    }

    /**
     * Begins a new fragment of code in root mode
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment
     * @param boolean $mode if false use tutorial_turn as timing reference 
     * @return string
     */
    public function startRoot($id, $startTime, $mode = \NULL) {
        $this->_userMode->push('shellroot');
        return $this->_start($id, $startTime, $mode);
    }

    public function startFile($id, $startTime, $mode = \NULL){
        $this->_userMode->push('filecontent');
        return $this->_start($id, $startTime, $mode);
    }
    
    /**
     * Begins a new fragment of code in user mode
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment 
     * @param boolean $mode if false use tutorial_turn as timing reference 
     * @return string
     */
    public function startUser($id, $startTime, $mode = \NULL) {
        $this->_userMode->push('shelluser');
        return $this->_start($id, $startTime, $mode);
    }

    
    /**
     * 
     * @return type
     */
    public function end() {
        $html = $this->_close();
        $this->_tags->pop();
        $this->_userMode->pop();
        return $html;
    }

    /**
     * 
     * @param type $clean
     * @return type
     */
    private function _close($level = 0) {
        if($this->_closed){
            return '';
        }
        $previous = $this->_previous->pop();
        $tag = $this->_tags->peek($level);
        $htlm = "</$tag> <!-- $previous -->\n";
        $this->_closed = \TRUE;
        return $htlm;
    }

    /**
     * The common part of all the starting contexts 
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment 
     * @param boolean $mode if false use tutorial_turn as timing reference 
     * @param type $style
     * @return string
     */
    private function _start($id, $startTime, $mode) {
        $html = $this->_close(1);
        if ($this->_tags->isEmpty()) {
            $this->_tags->push('pre');
        }
        if (is_null($mode)) {
            $mode = $this->_mode;
        }
        else {
            $this->_mode = $mode;
        }
        return $html.$this->_open($id, $startTime);
    }

    private function _open($id, $startTime) {
        $this->_computeStartTime($startTime);
        if ($this->_mode == self::AUTOMODE) {
            $this->_animateSynchro();
            $html = $this->callViewHelper('dojo_animation')->waitIn($id, $startTime, $this->_delay);
        }
        else {
            $html = $this->callViewHelper('dojo_turn')->on($id, $startTime, $this->_delay);
        }
        $tag = $this->_tags->peek();
        $style = $this->_userMode->peek();
        $html .= "<$tag class=\"$style\" id=\"$id\">\n";
        $this->_previous->push($id);
        $this->_closed = \FALSE;
        return $html;
    }

    /**
     * 
     * @param type $id
     * @param type $startTime
     * @param type $style
     * @return type
     */
    public function next($id, $startTime) {
        $this->_computeStartTime($startTime);
        $htlm = $this->_close();
        $htlm .= $this->_open($id, $startTime);
        $this->_previous->push($id);
        return $htlm;
    }

    /**
     * 
     * @param type $tag
     * @return \Tutorial\views\helpers\Code for fluent interface
     */
    public function setTag($tag) {
        $this->_tags->push($tag);
        return $this;
    }

    /**
     * 
     * @param type $delay
     * @return \Tutorial\views\helpers\Code for fluent interface
     */
    public function setDelay($delay) {
        $this->_delay = $delay;
        return $this;
    }

}

