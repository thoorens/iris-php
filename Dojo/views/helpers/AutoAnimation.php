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
class AutoAnimation extends _Animation {

     // is a Singleton (from 
    protected static $_Singleton = \TRUE;

    
    private $_id = 0;

    public function help() {
        return $this;
    }

    private function _getId() {
        return++$this->_id;
    }

    
    public function controlledIn($node, $signal, $startTime, $duration = 5000) {
        $this->_createBubble('controlledIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        signal : "$signal",
        starttime : $startTime,    
        dojofunction : "controlledIn" ,
        opacity : 0
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    public function controlledOut($node, $signal, $startTime, $duration = 5000) {
        $this->_createBubble('controlledOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        signal : "$signal",
        starttime : $startTime,    
        dojofunction : "controlledOut" ,
        opacity : 1
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    /**
     * 
     * @param type $node node name
     * @param type $button the button to click to start fading in
     * @param type $duration the duration of the fading in
     */
    public function in($node, $button, $duration = 5000) {
        $this->_createBubble('fadeIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeIn" ,
        opacity : 0
        }
        iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    /**
     * 
     * @param type $node the node name
     * @param type $startTime the time (in sec) before fading in
     * @param type $duration the duration of the fading in
     */
    public function waitIn($node, $startTime = 5000, $duration = 5000) {
        $this->_computeStartTime($startTime);
        $this->_createBubble('waitIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeIn" ,
        delay : $startTime,
        opacity : 0
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    /**
     * 
     * @param string $node the node name
     * @param int $startTime the time (in sec) before fading out
     * @param int $duration the duration of the fading out
     */
    public function waitOut($node, $startTime = 5000, $duration = 5000) {
        $this->_computeStartTime($startTime);
        $this->_createBubble('waitOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeOut" ,
        delay : $startTime,
        opacity : 1
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    /**
     * Fades out a displayed part of the screen
     * 
     * @param string $node node name
     * @param type $button the button to click to start fading out
     * @param type $duration the duration of the fading out
     */
    public function out($node, $button, $duration = 5000) {
        $this->_createBubble('fadeOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
    
    var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeOut" ,
        opacity : 1
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
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
        $this->_createBubble('inOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
    
    var io_args = {
        node : "$node",
        duration : $duration,
        duration2 : $duration2,    
        button : "$button",
        dojofunction : "fadeInOut" ,
        opacity : 0
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
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
        $this->_createBubble('outIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        duration2 : $duration2,
        button : "$button",
        dojofunction : "fadeOutIn" ,
        opacity : 1
    }
    iris_dojo.commonFade(io_args); 
SCRIPT
        );
    }

    /**
     * Common part of all the method: a namespace wrapped javascript function which does all the job.
     * @staticvar type $loaded
     * @todo verificate line after function fadeIt() 273
     */
    protected function _createBubble($bubbleName) {
        // Test loaded to gain time
        static $loaded = \FALSE;
        if (!$loaded) {
            $script = <<< SCRIPT
    require(["dojo/dom", "dojo/_base/fx", "dojo/on", "dojo/dom-style","dojo/fx", "dojo/topic", "dojo/domReady!"],
    function(dom, fx, on, style, coreFx, topic){
        // Style the dom node to opacity 0 or 1;
        if(args.node != 'turnEvents'){
            style.set(args.node, "opacity", args.opacity);
        }
        else{
            for(var i in iris_dojo.turnEvents){
                event = iris_dojo.turnEvents[i];
                if(event.opacity != -1){    
                    style.set(event.node, "opacity", event.opacity);
                }
            }
        }
        // Function linked to the button to trigger the fade.
        function fadeIt(){
            //style.set(args.node, "opacity", args.opacity);
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
            else if(args.dojofunction=='controlledIn'){
                topic.subscribe(args.signal, function(time){
                    if(time==args.starttime){
                       fx.fadeIn({node: args.node,duration: args.duration}).play();
                    }
                });
            }
            else if(args.dojofunction=='controlledOut'){
                topic.subscribe(args.signal, function(time){
                    if(time==args.starttime){
                       fx.fadeOut({node: args.node,duration: args.duration}).play();
                    }
                });
            }
            else if(args.dojofunction=='sequence'){
                topic.subscribe(args.signal, function(time){
                    for(i in iris_dojo.turnEvents){
                         event = iris_dojo.turnEvents[i];
                         if(time==event.starttime){
                            if(event.dojofunction == 'turnon'){
                                fx.fadeIn({node: event.node,duration: event.duration}).play();
                            }
                            else{
                                fx.fadeOut({node: event.node,duration: event.duration}).play();
                            }
                         }
                    }
                });
            }
        }
        if(args.button == null){
            setTimeout(fadeIt,args.delay); 
        }
        else{
            on(dom.byId(args.button), "click", fadeIt);
        }
    })
SCRIPT;
            \Dojo\Engine\DNameSpace::GetObject('commonFade')->createFunction($script, 'args');
            $loaded = \TRUE;
        }
        $bubble = \Dojo\Engine\Bubble::GetBubble($bubbleName);
        $bubble->addModule("dojo/domReady!", '');
        return $bubble;
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
        $this->_view->javascriptLoader('scroll', $script);
    }

    /**
     * Returns the HTML text for an empty div serving as a destination
     * for scroll or autoScroll.
     * 
     * @param string $name
     * @return string
     */
    public function scrollMarker($name) {
        return "<div id=\"$name\"></div>\n";
    }

    

}