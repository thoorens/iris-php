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

    const FADEIN = 0;
    const FADEOUT = 1;

    private $_functionData = [
        self::FADEIN => ['fadeIn', 0],
        self::FADEOUT => ['fadeOut', 1],
    ];

    /**
     * The last event time from beginning of frame
     * 
     * @var type 
     */
    private $_lastTime = 0;

    /**
     * Makes an object appears slowly (or not) after some seconds. The starting method is registred
     * for later use in $_jobs (see subscribe()).
     * 
     * @param string $objectId Object id
     * @param int $delay milliseconds before fadin
     * @param int $duration transition duration
     * @param mixed $start bolean to auto start or array (with commander controller and event)
     * @return type
     */
    public function fadeIn($objectId, $delay = 0, $duration = 5000, $start = \TRUE) {
        $this->_jobs[] = $objectId;
        return $this->_fadeX(self::FADEIN, $objectId, $delay, $duration, $start);
    }

    /**
     * Makes an object disappears slowly (or not) after some seconds. The starting method is registred
     * for later use in $_jobs (see subscribe()).
     * 
     * @param string $objectId Object id
     * @param int $delay milliseconds before fadin (negative is relative to previous)
     * @param int $duration transition duration
     * @param mixed $start bolean to auto start or array (with commander controller and event)
     * @return type
     */
    public function fadeOut($objectId, $delay = 0, $duration = 5000, $start = \TRUE) {
        $this->_jobs[] = $objectId;
        return $this->_fadeX(self::FADEOUT, $objectId, $delay, $duration, $start);
    }

    public function fadeInOut($objectId, $delay = [0, 10000], $duration = 5000, $start = \TRUE) {
        $this->_jobs[] = $objectId;
        if (!is_array($delay)) {
            $delay1 = $delay;
            $delay2 = 2 * $delay;
            $delay = [$delay1, $delay2];
        }
        return $this->_fadeX([self::FADEIN, self::FADEOUT], $objectId, $delay, $duration, $start);
    }

    /**
     * 
     * @param int $function animation to realize : 0 for FADIN, 1 for 
     * @param type $objectId
     * @param type $delay
     * @param type $duration
     * @param type $start
     * @return type
     * @throws \Iris\Exceptions\HelperException
     */
    private function _fadeX($function, $objectId, $delay = 0, $duration = 5000, $start = \TRUE) {
        if (is_array($delay)) {
            $delay0 = array_shift($delay);
            array_unshift($delay, 0);
        }
        else {
            $delay0 = $delay;
        }
        // in case of negative delay, the previous animation time is added to the delay
        // making a relative delay (for at once select -1)
        if ($delay0 < 0) {
            $startingTime = $this->_lastTime - $delay0;
        }
        else {
            $startingTime = $delay0;
        }
        // no starter code : the animation is run by hand
        if ($start === \FALSE) {
            $starterCode = '';
        }
        // un controller name and an event are provided in an array
        elseif (is_array($start)) {
            list($controller, $event) = $start;
            $starterCode = <<<STARTER
"var $controller = dojo.byId(\"$controller\");";
dojo.connect($controller,'$event', 'restart$objectId');
STARTER;
        }
        // default starter code : after loading the page
        elseif ($start === \TRUE) {
            $starterCode = "dojo.connect(null,'onload','restart$objectId');";
        }
        // no other way to start
        else {
            throw new \Iris\Exceptions\HelperException('Parameter "start" of fadeIn/fadeOut helpers must be boolean or an array');
        }
        // is the one or many actions to do
        $multi = \FALSE;
        if (is_array($function)) {
            $functions = $function;
            $function = $functions[0];
            $multi = \TRUE;
        }
        // define the initial state of opacity
        $opacity = $this->_functionData[$function][1];
        $opacityCode = "  dojo.style('$objectId', \"opacity\", \"$opacity\");\n";
        // the script start here
        $script = '<script type="text/javascript">' . "\n";
        $script .= "  function restart$objectId(){\n";
        $script .= $opacityCode;
        // in case of more than one effect, use dojo.fx
        if ($multi) {
            $this->_manager->addRequisite('"dojo.fx"');
            $num = 0;
            foreach ($functions as $function) {
                $startingTime = $num == 0 ? $startingTime : $delay[$num];
                $animName = "animated$num";
                $animations[] = $animName;
                $functionName = $this->_functionData[$function][0];
                $script .= "  var $animName = dojo.$functionName({node:$objectId,duration:$duration,delay:$startingTime});\n";
                $this->_lastTime += abs($delay[$num]) + $duration;
                $num++;
            }
            $animArray = "[" . implode(',', $animations) . "]";
            $script .= "dojo.fx.chain($animArray).play();\n";
        }
        // if only one effect, run it at once
        else {
            $functionName = $this->_functionData[$function][0];
            $script .= "  var animated = dojo.$functionName({node:$objectId,duration:$duration,delay:$startingTime});\n";
            $script .= "  animated.play(0,true);\n";
            $this->_lastTime = $startingTime + $duration;
        }
        $script .= "  }\n";
        $script .= "  $starterCode\n";
        $script .= "</script>\n";
        return $script;
    }

    public function in($node, $button, $duration = 5000) {
        $this->commonFade();
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
        $this->_view->javascriptLoader('fadeIn', $script);
    }

    public function waitIn($node, $delay = 5000, $duration = 5000) {
        $this->commonFade();
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
        $this->_view->javascriptLoader('waitIn', $script);
    }

    public function waitOut($node, $delay = 5000, $duration = 5000) {
        $this->commonFade();
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
        $this->_view->javascriptLoader('waitOut', $script);
    }

    public function out($node, $button, $duration = 5000) {
        $this->commonFade();
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
        $this->_view->javascriptLoader('fadeOut', $script);
    }

    public function inOut($node, $button, $duration = 5000) {
        $this->commonFade();
        $script = <<< SCRIPT
      require(["dojo/domReady!"], function(){
        var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeInOut" ,
        opacity : 0
        }
        commonFade(io_args); 
});
SCRIPT;
        $this->_view->javascriptLoader('fadeInOut', $script);
    }
    
    public function commonFade() {
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
            alert('in out');
                animIn = coreFx.fadeIn({node: args.node,duration: args.duration});
                animOut = coreFx.fadeOut({node: args.node,duration: args.duration});
                coreFx.combine([animIn, animOut]).play();
            }
            else if(args.dojofunction=='fadeOutIn'){
            
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

}