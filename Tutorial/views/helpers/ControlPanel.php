<?php

namespace Tutorial\views\helpers;
use Iris\Ajax\_Synchro;

//\define('IRIS_CP', 'IrisCPINTERNAL');
/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
 * @deprecated since version 2015
 */
class ControlPanel extends \Dojo\views\helpers\_DojoHelper{

    

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
    
    private $_context = "/* non context */";

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
     * @param int[] $duration The time duration of each frame
     * @param string $channel The publish/subscribe channel name (defautl IRIS_CP)
     * @return string 
     */
    public function render($duration, $channel = IRIS_CP) {
        /* @var $button \Iris\Subhelpers\Link */
        $button = $this->callViewHelper('button');
        $controlChannel = "C$channel";
        /* @var $synchro \Iris\Ajax\_Synchro */
        $synchro = $this->callViewHelper('synchro');
        $synchro->setContext($this->_context);
        $synchro->setRefreshingInterval($this->_refreshingInterval)
                ->setGranularity($this->_granularity)
                ->setAutostart($this->_autostart)
                ->send($channel, $duration, $controlChannel);
        $html = $synchro->previous($controlChannel, $this->_('Previous screen'));
        $html .= $synchro->start($controlChannel, $this->_('Stop'));
        $html .= $synchro->stop($controlChannel, $this->_('Stop'));
        $html .= $synchro->restart($controlChannel, $this->_('Start again'));
        $html .= $synchro->next($controlChannel, $this->_('Next screen'));
        if ($this->_sound) {
            $html .= $button->setId('Minus')
                    ->addAttribute('onclick', 'iris_dojo.soundminus()')
                    ->image(_Synchro::MediaIcon('minus', $this->_('Reduce volume')));
            //if ($this->_control & self::NOSOUND)
            $html .= $button->setId('Nosound')
                    ->addAttribute('onclick', 'iris_dojo.nosound()')
                    ->image(_Synchro::MediaIcon('mute',$this->_('Increase volume')));
            //if ($this->_control & self::PLUS)
            $html .= $button->setId('Plus')
                    ->addAttribute('onclick', 'iris_dojo.soundplus()')
                    ->image(_Synchro::MediaIcon('plus',$this->_('Increase volume')));
            $this->_jsSound();
        }
        $html .= '<span id="counter1" style="color=#white">-</span>';
        $synchro->counterMaxDisplay($channel, 'counter1');
        return $html;
    }

    public static function getButton($id, $event, $eventManager, $icon, $title){
        
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

    private function _jsSound() {
        \Dojo\Engine\NameSpaceItem::AddObject('oldVol')
                ->createVar(0);
        \Dojo\Engine\NameSpaceItem::AddObject('soundController')
                ->createVar("''");
        \Dojo\Engine\NameSpaceItem::AddObject('soundminus')
                ->createFunction('this.soundController.volume = this.soundController.volume - 0.1');
        \Dojo\Engine\NameSpaceItem::AddObject('soundplus')
                ->createFunction("this.soundController.volume = this.soundController.volume +0.1;");
        \Dojo\Engine\NameSpaceItem::AddObject('nosound')
                ->createFunction(<<< NOSOUND
        currentVol = this.soundController.volume;
        this.soundController.volume=this.oldVol;
        this.oldVol = currentVol;
NOSOUND
        );
        \Dojo\Engine\NameSpaceItem::AddObject('initsound')
                ->createFunction(<<< INITSOUND
        require(['dojo'],function(dojo){
            iris_dojo.soundController = dojo.byId('tuto_sound');
        });
INITSOUND
        );
        $synchro = 'C'.IRIS_CP;
        \Dojo\Engine\Bubble::GetBubble('soundcontrol')
                ->addModule('dojo/topic','topic')
                ->defFunction(<<< JS
   
        topic.subscribe('$synchro',function(value){
            if(value=='start')
               iris_dojo.soundController.play()
            else
               iris_dojo.soundController.pause();
        });
JS
        );
        $this->callViewHelper('javascriptStarter','test', 'iris_dojo.initsound();');
    }

    
    public function getTranslator() {
        static $translator = NULL;
        if (is_null($translator)) {
            $translator = \Tutorial\Translation\SystemTranslator::GetInstance();
        }
        return $translator;
    }

    public function defineContext($context) {
        $this->_context = $context;
    }
    
    
}
