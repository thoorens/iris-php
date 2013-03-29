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
 * This helper will provides mechanisms to display or modify a text 
 * using Dojo. For now, only fadein/fadeout are implemented. These methods are
 * linked with a publish/suscribe mechanism.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Animation extends _Animation {

    // is a Singleton  
    protected static $_Singleton = \TRUE;

    /**
     * The standard transition time between two states (redefined)
     * 
     * @var int
     */
    protected $_standardDuration = 5000;
    
    /**
     * A marker for distinguish each animation effects
     * 
     * @var int
     */
    private $_id = 0;

    /**
     * For this class, it is necessary to use the Animator
     */
    protected function _subclassInit() {
        $this->_animateSynchro();
    }

        /**
     * Gets the next id
     * @return type
     */
    private function _getId() {
        return++$this->_id;
    }

    /**
     * Permits to link the apparition of an object to a synchro
     * (the helper Turn is easier to use to produce the same effect)
     * 
     * @param string $node The id of the object to show
     * @param string $signal The synchro name
     * @param int $startTime The starttime (if negative, relative to previous)
     * @param int $duration Duration of the effect
     */
    public function controlledIn($node, $signal, $startTime, $duration = \NULL) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_animatorSubhelper->addToAnimationManager('controlledIn', <<<SCRIPT
                
       if(args.dojofunction=='controlledIn'){
                topic.subscribe(args.signal, function(time){
                    if(time==args.starttime){
                       fx.fadeIn({node: args.node,duration: args.duration}).play();
                    }
                });
            }         
SCRIPT
        );
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
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Permits to link the desapparition of an object to a synchro
     * (the helper Turn is easier to use to produce the same effect)
     * 
     * @param string $node The id of the object to hide
     * @param string $signal The synchro name
     * @param int $startTime The starttime (if negative, relative to previous)
     * @param int $duration Duration of the effect
     */
    public function controlledOut($node, $signal, $startTime, $duration = \NULL) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_animatorSubhelper->addToAnimationManager('controlledOut', <<<SCRIPT
                
            if(args.dojofunction=='controlledOut'){
                topic.subscribe(args.signal, function(time){
                    if(time==args.starttime){
                       fx.fadeOut({node: args.node,duration: args.duration}).play();
                    }
                });
            }
SCRIPT
        );

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
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Shows an object when a button is clicked
     * 
     * @param type $node The id of the object to show
     * @param type $button the button to click to start fading in
     * @param type $duration the duration of the fading in
     */
    public function in($node, $button, $duration = \NULL) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_registerFadeIn();
        $this->_createBubble('fadeIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeIn" ,
        opacity : 0
        }
        iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Shows an object after a while
     * 
     * @param type $node The id of the object to show
     * @param type $startTime the time (in sec) before fading in
     * @param type $duration the duration of the fading in
     */
    public function waitIn($node, $startTime = 5000, $duration = \NULL) {
        $this->_initialOpacity();
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_computeStartTime($startTime);
        $this->_registerFadeIn();
        $this->_createBubble('waitIn' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeIn" ,
        delay : $startTime,
        opacity : 0
    }
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Puts the code for fadeIn in animation manager
     */
    private function _registerFadeIn() {
        $this->_animatorSubhelper->addToAnimationManager('fadeIn', <<<SCRIPT
                
            if(args.dojofunction=='fadeIn'){
                fx.fadeIn({node: args.node,duration: args.duration}).play();
            }
SCRIPT
        );
    }

    /**
     * Hides an object after a while
     * 
     * @param string $node The id of the object to show
     * @param int $startTime the time (in sec) before fading out
     * @param int $duration the duration of the fading out
     */
    public function waitOut($node, $startTime = 5000, $duration = \NULL) {
        $this->_initialOpacity();
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_computeStartTime($startTime);
        $this->_registerFadeOut();
        $this->_createBubble('waitOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
   
    var io_args = {
        node : "$node",
        duration : $duration,
        dojofunction : "fadeOut" ,
        delay : $startTime,
        opacity : 1
    }
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Hides an object when a button is clicked
     * 
     * @param string $node The id of the object to hide
     * @param type $button the button to click to start fading out
     * @param type $duration the duration of the fading out
     */
    public function out($node, $button, $duration = \NULL) {
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        $this->_registerFadeOut();
        $this->_createBubble('fadeOut' . $this->_getId())
                ->defFunction(<<< SCRIPT
    
    var io_args = {
        node : "$node",
        duration : $duration,
        button : "$button",
        dojofunction : "fadeOut" ,
        opacity : 1
    }
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Puts the code for fade out in animation manager
     */
    private function _registerFadeOut() {
        $this->_animatorSubhelper->addToAnimationManager('fadeOut', <<<SCRIPT
                
            if(args.dojofunction=='fadeOut'){
                fx.fadeOut({node: args.node,duration: args.duration}).play();
            }
SCRIPT
        );
    }

    /**
     * Fades in an non displayed part of the screen before fade out it again
     * 
     * @param type $node The id of the object to show and hide
     * @param type $button the button to click to start fading in
     * @param type $duration the duration of the fading in
     * @param int $duration2 the duration of the fading out
     */
    public function inOut($node, $button, $duration = \NULL, $duration2 = \NULL) {
        $this->_initialOpacity();
        if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        if (is_null($duration2))
            $duration2 = $duration;
        $this->_animatorSubhelper->addToAnimationManager('fadeInOut', <<<SCRIPT
                
            if(args.dojofunction=='fadeInOut'){
                animIn = fx.fadeIn({node: args.node,duration: args.duration});
                animOut = fx.fadeOut({node: args.node,duration: args.duration2});
                coreFx.chain([animIn, animOut]).play();
            }
SCRIPT
        );
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
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

    /**
     * Fades out an displayed part of the screen before fade in it again
     * 
     * @param string $node The id of the object to show and hide
     * @param string $button the button to click to start fading out
     * @param int $duration the duration of the fading out
     * @param int $duration2 the duration of the fading in
     */
    public function outIn($node, $button, $duration = \NULL, $duration2 = \NULL) {
        $this->_initialOpacity();          if (is_null($duration)) {
            $duration = $this->_standardDuration;
        }
        if (is_null($duration2))
            $duration2 = $duration;
        $this->_animatorSubhelper->addToAnimationManager('fadeOutIn', <<<SCRIPT
                
            if(args.dojofunction=='fadeOutIn'){
                animOut = fx.fadeOut({node: args.node,duration: args.duration});
                animIn = fx.fadeIn({node: args.node,duration: args.duration2});
                coreFx.chain([animOut, animIn]).play();
            }
SCRIPT
        );
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
    iris_dojo.commonAnimation(io_args); 
SCRIPT
        );
    }

}