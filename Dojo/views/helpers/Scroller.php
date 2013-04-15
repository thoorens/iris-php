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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * Helps to scroll the screen
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Scroller extends _Sequence {

    /**
     * The mother class init is disabled since the animation is only usefull
     * for syncScroll.
     */
    protected function _init() {
    }

    /**
     * Reactivates the mother class init, in case of use of syncScroll.
     * 
     * @staticvar boolean $done The initialization must be done once
     */
    private function _init2() {
        static $done = \FALSE;
        if(! $done){
            parent::_init();
            $done = \TRUE;
        }
    }

    /**
     * Excecutes a scroll to destination at a moment (relative to a given synchro)
     * 
     * @param string $destination An object serving as an anchor
     * @param type $startTime The moment (if negative relative to previous event)
     */
    public function syncScroll($destination, $startTime) {
        static $done = \FALSE;
        if(! $done){
            $this->_animateSynchro();
            $done = \TRUE;
        }
        $this->_animatorSubhelper->addModule('dojo/window', 'win');
        $this->_computeStartTime($startTime);
        $this->_eventCreate('scroll', $destination, $startTime, 0, 0);
        $code = <<<CODE

                            if(event.dojofunction == 'scroll'){
                                win.scrollIntoView(event.node);
                            }
                
CODE;
        $this->_insertSequenceCode('scroll', $code);
    }

    /**
     * Excecutes a scroll to destination at click
     * 
     * @param type $destination An object serving as an anchor
     * @param type $trigger The object sending the click
     */
    public function scrollClick($destination, $trigger) {
        $script = <<<SCRIPT
   require(["dojo/window", "dojo/on", "dojo/dom", "dojo/dom-geometry", "dojo/domReady!"],
function(win, on, dom, domGeom){
  on(dom.byId("$trigger"), "click", function(){
    win.scrollIntoView('$destination');
  });
  });
SCRIPT;
        $this->callViewHelper('javascriptLoader', "scroll$destination", $script);
    }

    /**
     * Executes a scroll to destination after a delay
     * 
     * @param type $destination An object serving as an anchor
     * @param type $startTime The delay
     */
    public function autoScroll($destination, $startTime) {
        $this->_computeStartTime($startTime);
        $script = <<<SCRIPT
   require(["dojo/window", "dojo/dom-geometry", "dojo/domReady!"],
function(win, dolater, domGeom){
var count = 0;
setTimeout(function(){
        win.scrollIntoView('$destination');
    }, $startTime);
  });
SCRIPT;
        $this->callViewHelper('javascriptLoader', "scroll$destination", $script);
    }

    /**
     * Returns the HTML text for an empty div serving as a destination
     * for scroll or autoScroll.
     * 
     * @param string $name
     * @return string
     */
    public function scrollAnchor($name) {
        return "<div id=\"$name\"></div>\n";
    }

}
