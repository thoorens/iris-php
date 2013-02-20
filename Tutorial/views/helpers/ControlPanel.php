<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\views\helpers;

\define('IRIS_CP', 'IrisCPINTERNAL');
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
class ControlPanel extends \Dojo\views\helpers\_DojoHelper {

    use \Tutorial\Translation\tSystemTranslatable;

    protected static $_Singleton = TRUE;

    /**
     * The name of the associated sound controller
     * 
     * @var string
     */
    private $_soundControllerId = 'tuto_sound';

    /**
     * If TRUE, the frame show starts at once
     * 
     * @var boolean
     */
    private $_autostart = \TRUE;

    /**
     * The sleep duration between to emission (in 1/1000s)
     * 
     * @var int
     */
    private $_granularity = 100;

    /**
     * The time elapsed between two refreshing of counter display
     * @var int
     */
    private $_refreshingInterval = 1000;
    private $_sound = \TRUE;

    /**
     * The helper returns its reference or starts its rendering 
     * 
     * @param int $duration The time duration of the frame
     * @param string $channel The publish/subscribe channel name (defautl IRIS_CP)
     * @return string 
     */
    public function help($duration = \NULL, $channel = IRIS_CP) {
        if (is_null($duration)) {
            return $this;
        }
        else {
            return $this->render($duration, $channel);
        }
    }

    /**
     * Does the job: html for button bar and javascript
     * 
     * @param array(int) $duration The time duration of each frame
     * @param string $channel The publish/subscribe channel name (defautl IRIS_CP)
     * @return string 
     */
    public function render($duration, $channel = IRIS_CP) {
        /* @var $button \Iris\Subhelpers\Link */
        $button = $this->_view->button();
        $controlChannel = "C$channel";
        /* @var $synchro \Iris\Ajax\_Synchro */
        $synchro = $this->_view->synchro();

        $synchro->setRefreshingInterval($this->_refreshingInterval)
                ->setGranularity($this->_granularity)
                ->setAutostart($this->_autostart)
                ->send($channel, $duration, $controlChannel);
        $html = $synchro->start($controlChannel);
        $html .= $synchro->stop($controlChannel);
        $html .= $synchro->restart($controlChannel);
        $html .= $synchro->next($controlChannel);
        if ($this->_sound) {
            $html .= $button->setId('Minus')
                    ->addAttribute('onclick', 'iris_dojo.soundminus()')
                    ->display($this->_('-'), \NULL, $this->_('Reduce volume'), '');
            //if ($this->_control & self::NOSOUND)
            $html .= $button->setId('Nosound')
                    ->addAttribute('onclick', 'iris_dojo.nosound()')
                    ->display($this->_('X'), \NULL, $this->_('Toggle sound'), '');
            //if ($this->_control & self::PLUS)
            $html .= $button->setId('Plus')
                    ->addAttribute('onclick', 'iris_dojo.soundplus()')
                    ->display($this->_('+'), \NULL, $this->_('Increase volume'), '');
            $this->_jsSound();
        }
        $html .= '<span id="counter1">-</span>';
        $synchro->counterMaxDisplay($channel, 'counter1');
        return $html;
    }

    /**
     * Sets the value of autostart
     * 
     * @param boolean $autostart If TRUE, counter will be started at once
     * @return \Tutorial\views\helpers\ControlPanel for fluent interface
     */
    public function setAutostart($autostart) {
        $this->_autostart = $autostart;
        return $this;
    }

    /**
     * Sets the id of the managed sound controller
     * 
     * @param string $soundControllerId
     * @return \Tutorial\views\helpers\ControlPanel for fluent interface
     */
    public function setSoundControllerId($soundControllerId) {
        $this->_soundControllerId = $soundControllerId;
        return $this;
    }

    /**
     * Sets the interval between two sleep time
     * 
     * @param int $granularity
     * @return \Tutorial\views\helpers\ControlPanel for fluent interface
     */
    public function setGranularity($granularity) {
        $this->_granularity = $granularity;
        return $this;
    }

    /**
     * Sets the interval between two refreshing of the counter
     * 
     * @param int $refreshingInterval
     * @return \Tutorial\views\helpers\ControlPanel
     */
    public function setRefreshingInterval($refreshingInterval) {
        $this->_refreshingInterval = $refreshingInterval;
        return $this;
    }

    public function _jsSound() {
        \Dojo\Engine\DNameSpace::addObject('oldVol')
                ->createVar(0);
        \Dojo\Engine\DNameSpace::addObject('soundController')
                ->createVar("''");
        \Dojo\Engine\DNameSpace::addObject('soundminus')
                ->createFunction('this.soundController.volume = this.soundController.volume - 0.1');
        \Dojo\Engine\DNameSpace::addObject('soundplus')
                ->createFunction("this.soundController.volume = this.soundController.volume +0.1;");
        \Dojo\Engine\DNameSpace::addObject('nosound')
                ->createFunction(<<< NOSOUND
        currentVol = this.soundController.volume;
        this.soundController.volume=this.oldVol;
        this.oldVol = currentVol;
NOSOUND
        );
        \Dojo\Engine\DNameSpace::addObject('initsound')
                ->createFunction(<<< INITSOUND
        require(['dojo'],function(dojo){
            iris_dojo.soundController = dojo.byId('tuto_sound');
        });
INITSOUND
        );
        \Dojo\Engine\Bubble::GetBubble('soundcontrol')
                ->addModule('dojo/topic','topic')
                ->defFunction(<<< JS
   
        topic.subscribe('CIrisCPINTERNAL',function(value){
            if(value=='start')
               iris_dojo.soundController.play()
            else
               iris_dojo.soundController.pause();
        });
JS
        );
        $this->_view->javascriptStarter('test', 'iris_dojo.initsound();');
    }

}
