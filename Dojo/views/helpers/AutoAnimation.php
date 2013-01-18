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
 * @copyright 2012 Jacques THOORENS
 *
 */

/**
 * This helper will provides mechanisms to display or modify a text 
 * using Dojo. For now, only fadein/fadeout are implemented. These methods are
 * linked with a publish/suscribe mechanism.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class AutoAnimation extends _DojoHelper {

    protected static $_Singleton = \TRUE;

    /**
     * The last event time from beginning of screeen
     * 
     * @var type 
     */
    private $_lastTime = 0;
    private $_id = 0;

    public function help() {
        return $this;
    }

    public function getId() {
        return++$this->_id;
    }

    /**
     * 
     * @param type $node node name
     * @param type $button the button to click to start fading in
     * @param type $duration the duration of the fading in
     */
    public function in($node, $button, $duration = 5000) {
        $this->_commonFade();
        $script = <<< SCRIPT
   require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeIn" ,
        opacity : 0
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('fadeIn' . $this->getId(), $script);
    }

    /**
     * 
     * @param type $node the node name
     * @param type $delay the time (in sec) before fading in
     * @param type $duration the duration of the fading in
     */
    public function waitIn($node, $delay = 5000, $duration = 5000) {
        $this->_commonFade();
        $delay = $this->_delay($delay);
        $script = <<< SCRIPT
   require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeIn" ,
        delay : $delay,
        opacity : 0
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('waitIn' . $this->getId(), $script);
    }

    /**
     * 
     * @param string $node the node name
     * @param int $delay the time (in sec) before fading out
     * @param int $duration the duration of the fading out
     */
    public function waitOut($node, $delay = 5000, $duration = 5000) {
        $this->_commonFade();
        $delay = $this->_delay($delay);
        $script = <<< SCRIPT
   require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeOut" ,
        delay : $delay,
        opacity : 1
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('waitOut' . $this->getId(), $script);
    }

    /**
     * Fades out a displayed part of the screen
     * 
     * @param string $node node name
     * @param type $button the button to click to start fading out
     * @param type $duration the duration of the fading out
     */
    public function out($node, $button, $duration = 5000) {
        $this->_commonFade();
        $script = <<< SCRIPT
      require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeOut" ,
        opacity : 1
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('fadeOut' . $this->getId(), $script);
    }

    /**
     * Fades in an non displayed part of the screen before fade out it again
     * 
     * @param type $node the node name
     * @param type $button the button to click to start fading in
     * @param type $duration the duration of the fading in
     * @param int $duration2 the duration of the fading out
     */
    public function inOut($node, $button, $duration = 5000, $duration2 = \NULL) {
        if (is_null($duration2))
            $duration2 = $duration;
        $this->_commonFade();
        $script = <<< SCRIPT
      require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        duration2 : $duration2,    
        button : "$button",
        dojofunction : "fadeInOut" ,
        opacity : 0
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('fadeInOut' . $this->getId(), $script);
    }

    /**
     * Fades out an displayed part of the screen before fade in it again
     * 
     * @param string $node the node name
     * @param string $button the button to click to start fading out
     * @param int $duration the duration of the fading out
     * @param int $duration2 the duration of the fading in
     */
    public function outIn($node, $button, $duration = 5000, $duration2 = \NULL) {
        if (is_null($duration2))
            $duration2 = $duration;
        $this->_commonFade();
        $script = <<< SCRIPT
      require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        duration2 : $duration2,
        button : "$button",
        dojofunction : "fadeOutIn" ,
        opacity : 1
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('fadeOutIn' . $this->getId(), $script);
    }

    /**
     * Common part of all the method: a javascript function who does all the job.
     * @staticvar type $loaded
     */
    protected function _commonFade() {
        // Test loaded to gain time
        static $loaded = \FALSE;
        if (!$loaded) {
            $script = <<< SCRIPT
    function commonFade(args){
    require(["dojo/dom", "dojo/_base/fx", "dojo/on", "dojo/dom-style","dojo/fx", "dojo/domReady!"],
    function(dom, fx, on, style, coreFx){
        // Style the dom node to opacity 0 or 1;
        style.set(args.node, "opacity", args.opacity);
        
        // Function linked to the button to trigger the fade.
        function fadeIt(){
            style.set(args.node, "opacity", args.opacity);
            if(args.dojofunction=='fadeIn'){
                fx.fadeIn({node: args.node,duration: args.duration}).play();
            }
            else if(args.dojofunction=='fadeOut'){
                fx.fadeOut({node: args.node,duration: args.duration}).play();
            }
            else if(args.dojofunction=='fadeInOut'){
                animIn = fx.fadeIn({node: args.node,duration: args.duration});
                animOut = fx.fadeOut({node: args.node,duration: args.duration2});
                coreFx.chain([animIn, animOut]).play();
            }
            else if(args.dojofunction=='fadeOutIn'){
                animOut = fx.fadeOut({node: args.node,duration: args.duration});
                animIn = fx.fadeIn({node: args.node,duration: args.duration2});
                coreFx.chain([animOut, animIn]).play();
            }
        }
        if(args.button == null){
            setTimeout(fadeIt,args.delay); 
        }
        else{
            on(dom.byId(args.button), "click", fadeIt);
        }
    })
    };
SCRIPT;
            $this->_view->javascriptLoader('commonFade', $script);
            $loaded = \TRUE;
        }
    }

    public function scroll($destination, $trigger) {
        $script = <<<SCRIPT
   require(["dojo/window", "dojo/on", "dojo/dom", "dojo/dom-geometry", "dojo/domReady!"],
function(win, on, dom, domGeom){
  on(dom.byId("$trigger"), "click", function(){
    win.scrollIntoView('$destination');
  });
  });
SCRIPT;
        $this->_view->javascriptLoader('scroll', $script);
    }

    public function autoScroll($destination, $delay) {
        $delay = $this->_delay($delay);
        $script = <<<SCRIPT
   require(["dojo/window", "dojo/dom-geometry", "dojo/domReady!"],
function(win, dolater, domGeom){
var count = 0;
setTimeout(function(){
        win.scrollIntoView('$destination');
    }, $delay);
  });
SCRIPT;
        $this->_view->javascriptLoader('scroll', $script);
    }

    /**
     * Returns the HTML text for an empty div serving as a destination
     * for scroll or autoScroll.
     * 
     * @param string $name
     * @return string
     */
    public function scrollMarker($name){
        return "<div id=\"$name\"></div>\n";
    }
    
    /**
     * Converts relatives time to absolute
     * @param int $delay the starting time (if <0, considered as relative)
     * @return int
     */
    private function _delay($delay) {
        if ($delay > 0) {
            $startingTime = $delay;
        }
        else {
            $startingTime = $this->_lastTime - $delay;
        }
        $this->_lastTime = $startingTime;
        return $startingTime;
    }

    
    
}