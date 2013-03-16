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
 * A helper for image display
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Code extends \Dojo\views\helpers\_Animation {

    const AUTO = \FALSE;
    const MANAGED = \TRUE;

    protected static $_Singleton = \TRUE;
    private $_previous;
    private $_tags = ['pre'];
    private $_delay = 500;

    /**
     *
     * @var type 
     */
    private $_mode;

    public function help() {
        return $this;
    }

    public function startRoot($id, $startTime, $mode = self::AUTO) {
        $this->_mode = $mode;
        $this->_previous = $id;
        return $this->_start($id, $startTime, $mode, "shellroot");
    }

    public function nextRoot($id, $startTime) {
        return $this->_next($id, $startTime, "shellroot");
    }

    public function startUser($id, $startTime, $mode = self::AUTO) {
        $this->_mode = $mode;
        $this->_previous = $id;
        return $this->_start($id, $startTime, $mode, "shelluser");
    }

    public function nextUser($id, $startTime) {
        return $this->_next($id, $startTime, "shelluser");
    }

    public function end() {
        return $this->_end(\TRUE);
    }

    private function _end($clean) {
        $previous = $this->_previous;
        $tag = $this->getTag($clean);
        $htlm = "</$tag> <!-- $previous -->\n";
        return $htlm;
    }

    private function _start($id, $startTime, $mode, $style) {
        $this->_computeStartTime($startTime);
        if ($mode == self::AUTO) {
            $html = $this->callViewHelper('dojo_autoAnimation')->waitIn($id, $startTime, $this->_delay);
        }
        else {
            $html = $this->callViewHelper('dojo_turn')->on($id, $startTime, $this->_delay);
        }
        $tag = $this->getTag(\FALSE);
        $html .= "<$tag class=\"$style\" id=\"$id\">\n";
        return $html;
    }

    private function _next($id, $startTime, $style) {
        $this->_computeStartTime($startTime);
        $htlm = $this->_end(\FALSE);
        $htlm .= $this->_start($id, $startTime, $this->_mode, $style);
        return $htlm;
    }

    private function getTag($clean) {
        if ($clean or count($this->_tags) > 1) {
            $tag = array_shift($this->_tags);
        }
        elseif (count($this->_tags)) {
            $tag = $this->_tags[0];
        }
        else {
            throw new \Iris\Exceptions\InternalException('Incoherent tags in Code helper');
        }
        return $tag;
    }

    public function setTag($tag) {
        array_unshift($this->_tags, $tag);
        return $this;
    }

    public function setDelay($delay) {
        $this->_delay = $delay;
        return $this;
    }

}

