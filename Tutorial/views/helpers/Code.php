<?php

namespace Tutorial\views\helpers;
use Iris\System\Stack;

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
 * A helper for code display with animation effects
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * @deprecated since version 2015
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

    /**
     * If not true, specifies that a tag has been opened, but closed
     * @var boolean
     */
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

    /**
     * Called by the constructor, initializes three stack: the tags, the previous tag name 
     * and the user mode
     */
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
     * Begins a new fragment of code in file mode
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment
     * @param boolean $mode if false use tutorial_turn as timing reference 
     * @return string
     */
    public function startFile($id, $startTime, $mode = \NULL){
        $this->_userMode->push('filecontent');
        return $this->_start($id, $startTime, $mode);
    }
    
    /**
     * Begins a new fragment of code in a special named mode
     * 
     * @param string $contextName
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment
     * @param boolean $mode if false use tutorial_turn as timing reference 
     * @return string
     */
    public function startNamedContext($contextName, $id, $startTime, $mode = \NULL){
        $this->_userMode->push($contextName);
        return $this->_start($id, $startTime, $mode);
    }
    
    
    /**
     * Terminates a sequence of code
     * @return type
     */
    public function end() {
        $html = $this->_close();
        $this->_tags->pop();
        $this->_userMode->pop();
        return $html;
    }

    /**
     * Close a opened tag (if necessary). If called by _start, a level of 1
     * is used to take the tag name previously
     * @param int $level
     * @return string
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
     * @return string
     */
    private function _start($id, $startTime, $mode) {
        // may be useful to close a previous unclosed tag
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

    /**
     * Returns the HTML code for the first tag of a sequence of code
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment 
     * @return string
     */
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
     * Begins a second segment with the same tag
     * 
     * @param string $id The id of the block to manage
     * @param int $startTime The absolute or relative (negative) moment 
     * @return string
     */
    public function next($id, $startTime) {
        $htlm = $this->_close();
        $htlm .= $this->_open($id, $startTime);
        $this->_previous->push($id);
        return $htlm;
    }

    /**
     * Specifies the tag to use (by default it is &lt;pre>)
     * 
     * @param type $tag
     * @return \Tutorial\views\helpers\Code for fluent interface
     */
    public function setTag($tag) {
        $this->_tags->push($tag);
        return $this;
    }

    /**
     * Acessor set for the length of the animation
     * @param type $delay
     * @return \Tutorial\views\helpers\Code for fluent interface
     */
    public function setDelay($delay) {
        $this->_delay = $delay;
        return $this;
    }

}

