<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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
 */

/**
 * This is the heart of the frame control for tutorials. It displays a control bar
 * with navigation button
 * and provides publish subscribe mechanism to dialog with each frame.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class ControlPanel extends _DojoHelper {

    use \Tutorial\Translation\tSystemTranslatable;

    const FIRST = 1;
    const PREVIOUS = 2;
    const STOP = 4;
    const PLAY = 8;
    const NEXT = 16;
    const LAST = 32;
    const MINUS = 64;
    const NOSOUND = 128;
    const PLUS = 256;
    const TIME = 512;
    const STARTER = 1024;

    protected static $_Singleton = TRUE;
    protected $_control = 18; // PREVIOUS + NEXT

    /**
     * The name of the associated sound controller
     * 
     * @var string
     */
    private $_soundController = 'tuto_sound';

    /**
     * If TRUE, the frame show starts at once
     * 
     * @var boolean
     */
    private $_autostart = \TRUE;

    /**
     * The helper returns its reference or starts its rendering 
     */
    public function help($times = \NULL, $channel = 'NEXT') {
        if (is_null($times)) {
            $this->_manager->addRequisite('"dojo/dom-class", "dojo/dom-class"');
            return $this;
        }
        else {
            return $this->render($times, $channel = 'NEXT');
        }
    }

    /**
     * Does the job: html for button bar and javascript
     * 
     * @param array(int) $duration The time duration of each frame
     * @param string $channel The publish/subscribe channel name (defautl NEXT)
     * @return string 
     * @throws \Iris\Exceptions\HelperException
     */
    public function render($duration, $channel = 'NEXT') {
        $button = $this->_view->button();
        $html = '';
        if (false)
            $button->setId('List')->render($this->_('List'), '/tutorials/index/list', '', '');
        if ($this->_control & self::FIRST)
            $html .= $button->setId('First')
                    ->setOnClick('')
                    ->render($this->_('Restart'), \NULL, '', '');
        if ($this->_control & self::PREVIOUS)
            $html .= $button->setId('Prev')->setOnClick('previous()')->render($this->_('Previous'), \NULL, '', '');
        if ($this->_control & self::NEXT)
            $html .= $button->setId('Next')->setOnClick('next()')->render($this->_('Next'), \NULL, '', '');
        if ($this->_control & self::STOP)
            $html .= $button->setId('Stop')->setOnClick('stop()')->render($this->_('Stop'), \NULL, '', '');
        if ($this->_control & self::PLAY)
            $html .= $button->setId('Play')->setOnClick('start()')->render($this->_('Play'), \NULL, '', '');
        if ($this->_control & self::MINUS)
            $html .= $button->setId('Minus')->setOnClick('minus()')->render($this->_('-'), \NULL, $this->_('Reduce volume'), '');
        if ($this->_control & self::NOSOUND)
            $html .= $button->setId('Nosound')->setOnClick('nosound()')->render($this->_('X'), \NULL, $this->_('Toggle sound'), '');
        if ($this->_control & self::PLUS)
            $html .= $button->setId('Plus')->setOnClick('plus()')->render($this->_('+'), \NULL, $this->_('Increase volume'), '');
        if ($this->_control & self::TIME) {
            $class = \Iris\Engine\Mode::IsDevelopment() ? '' : 'class="tuto_hidden"';
            $html .= "<span id=\"tuto_seconds\" $class>0</span>/";
            $html .= "<span id=\"tuto_maxseconds\" $class>0</span> &diams; ";
            $html .= "<span id=\"tuto_totalseconds\" $class>0</span>";
        }
        $this->_prepareScript($duration, $channel);
        return $html;
    }

    /**
     * Completes the button bar with javascript code
     *  
     * @param array(int) $duration The time duration of each frame
     * @param string $channel The publish/subscribe channel name (defautl NEXT)
     */
    private function _prepareScript($duration, $channel) {
        $this->_view->styleLoader('tuto_style', '.tuto_hidden{display:none;}');
        $array = '[' . implode(',', $duration) . ']';
        $accumulator = 0;
        $max = count($duration);
        $soundController = $this->_soundController;
        $autostart = $this->_autostart ? 'true' : 'false';
        $continueLabel = $this->_('Continue');
        $script = '';
        if ($this->_control & self::STARTER)
            $script = <<<STOP
    timing = $array;
    max= $max;
    current = 0;
    sec = 0;
    running = $autostart;
    oldVol = 0;
    soundController = '';

    function tuto_init(){
        dojo.byId('tuto_maxseconds').innerHTML=timing[current];
        //frames=dojo.getObject('frames');
        soundController = dojo.byId('$soundController');
        if(running){
            soundController.play();
            dojo.addClass('Play','tuto_hidden');
        }
        else{
            dojo.addClass('Stop','tuto_hidden');
        };
        domClass.remove('tuto_container','loading_image');
        domClass.remove('tuto_internal','tuto_hidden');
        tuto_timer();
    }
    
    function tuto_timer(){
        if(running){
            dojo.byId('Play').innerHTML = '$continueLabel';
            counter = dojo.byId('tuto_seconds');
            seconds = 1*counter.innerHTML;
            counter.innerHTML = seconds+1;
            counter2 = dojo.byId('tuto_totalseconds');
            seconds2 = 1*counter2.innerHTML;
            counter2.innerHTML = seconds2+1;
            sec++;
            if(sec>=timing[current]){
                next();
            }
        }
        compte=setTimeout('tuto_timer()',1000) 
    }
    
    function reset(){
        frames.selectChild('label1');
        dojo.byId('tuto_seconds').innerHTML=0;
        current = 0;
        sec = 0;
        soundController.currentTime=0;
        soundController.play();
        dojo.addClass('Play','tuto_hidden');
        dojo.removeClass('Stop','tuto_hidden');
    }

STOP;
        if ($this->_control & self::PREVIOUS)
            $script .= <<<PREVIOUS

    function previous(){
        frames.back();
        current--;
        if(current<0){
            current = max-1;
        }
        dojo.publish('$channel', [current,soundController]);
        _updateTimer();
    }
PREVIOUS;
        if ($this->_control & self::NEXT)
            $script .= <<<NEXT
    function next(){
        frames.forward();
        sec=0;
        current++;
        if(current == max){
             current=0;
        }
        dojo.publish('NEXT', [current]);
        _updateTimer();
    }
    
NEXT;
        if ($this->_control & self::STOP)
            $script .= <<<STOP

    function stop(){
        dojo.byId('Play').innerHTML = '$continueLabel';
        running = false;
        dojo.removeClass('Play','tuto_hidden');
        dojo.addClass('Stop','tuto_hidden');
        soundController.pause();
    }
STOP;
        if ($this->_control & self::PLAY)
            $script .= <<<PLAY
    function start(){
        running = true;
        dojo.addClass('Play','tuto_hidden');
        dojo.removeClass('Stop','tuto_hidden');
        soundController.play();
    }
    
PLAY;
        if ($this->_control & self::MINUS)
            $script .= <<<MINUS
    function minus(){
        soundController.volume = controller.volume - 0.1;    
    }
    
MINUS;

        if ($this->_control & self::NOSOUND)
            $script .= <<< NOSOUND
    function nosound(){
        currentVol = soundController.volume;
        soundController.volume=oldVol;
        oldVol = currentVol;
    }
    
NOSOUND;

        if ($this->_control & self::PLUS)
            $script .= <<<PLUS
    function plus(){
        soundController.volume = soundController.volume +0.1;    
    }
    
PLUS;
        if (false)
            $script .= <<<DISPLAY
    function _updateTimer(){
        dojo.byId('tuto_maxseconds').innerHTML=timing;
        dojo.byId('tuto_seconds').innerHTML=0;
    }
   
DISPLAY;
        $this->_view->javascriptLoader(99, $script);
        if ($this->_control & self::STARTER)
            $this->_view->javascriptStarter('tuto', 'tuto_init()');
    }

    public function setAutostart($autostart) {
        $this->_autostart = $autostart;
        return $this;
    }

    public function setSoundController($soundController) {
        $this->_soundController = $soundController;
        return $this;
    }

}
