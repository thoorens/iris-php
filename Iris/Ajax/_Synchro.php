<?php

namespace Iris\Ajax;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */            

/**
 * An Ajax provider written in Dojo
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _Synchro extends \Iris\Subhelpers\_Subhelper {

    const SEQUENCE = 1;
    const REPLACE = 2;
    const MINUTE = 6000;

    /**
     * a special URL no to go to (to disable a button)
     */
    const NO_URL = '**NoURL**';
    protected static $_Instance = \NULL;

    /**
     * The max time for synchro (default 1 minute)
     * @var int
     */
    protected $_max;
    /**
     * The number of milliseconds between two refreshing of the synchro display
     * @var int
     */
    protected $_refreshingInterval = 1000;
    /**
     * The number of milliseconds between each pulse 
     * @var int
     */
    protected $_granularity = 100;
    /**
     * If true (by default), the synchro start by itself
     * @var boolean
     */
    protected $_autostart = \TRUE;

    /**
     * An array of 3 URL for previous, current and next frames
     * @var string[]
     */
    protected $_context = [self::NO_URL, self::NO_URL, self::NO_URL];
    
    /**
     * Creates a scheduler, optionally controlled by another message transmitter
     * 
     * @param string $messageName The message name
     * @param int $interval
     * @param int $max Duration of the sender (by default 3600 sec)
     * @param string $externalSignal Optional signal to control the sender
     */
    public abstract function send($messageName, $max = self::MINUTE, $externalSignal = \NULL);

    /**
     * A scheduler state display, accumulating &lt;li> line (for testing purpose)
     * 
     * @param string $messageName
     * @param string $targetId
     * @param int $num Sequential number in case of various displays
     * @return string
     */
    public function liDisplay($messageName, $targetId, $num = 1) {
        $refreshingInterval = $this->_refreshingInterval;
        $code = "'<li>'+time/$refreshingInterval+'</li>'";
        return $this->_display($num, $messageName, $targetId, self::SEQUENCE, $code);
    }

    /**
     * A scheduler state display, 
     */
    public function counterDisplay($messageName, $targetId, $num = 1) {
        $refreshingInterval = $this->_refreshingInterval;
        $code = "'<span id=\"$targetId\">'+time/$refreshingInterval+'</span>'";
        return $this->_display($num, $messageName, $targetId, self::REPLACE, $code);
    }

    /**
     * 
     */
    public function counterMaxDisplay($messageName, $targetId, $num = 1) {
        $refreshingInterval = $this->_refreshingInterval;
        $code = "'<span id=\"$targetId\">'+time/$refreshingInterval+'/'+max/$refreshingInterval+'</span>'";
        return $this->_display($num, $messageName, $targetId, self::REPLACE, $code);
    }

    protected abstract function _display($messageName, $targetId, $mode, $htmlCode, $num);

    protected abstract function _genericReceiver($id, $messageName, $treatment, $requisites);

    private function _button($name) {
        list($signal, $mess) = explode('_', $name);
        $functionCode = "topic.publish('$mess','$signal')";
        $this->callViewHelper('eventManager')->addModules(['topic' => 'dojo/topic'])->onclick($name, $functionCode);
        return $this->callViewHelper('button');
    }

    
    
    /**
     * Creates a start button sending a signal
     * 
     * @param string $signal The name of the signal which the button is going to send
     * @param string $tooltip The tooltip text
     * @return string The code for the button
     */
    public function start($signal, $tooltip = \NULL) {
        if (is_null($tooltip))
            $tooltip = $this->_('Start again');
        $name = "start_$signal";
        return $this->_button($name)->setId($name)->image(self::MediaIcon('start', $tooltip));
    }

    /**
     * Creates a restart button sending a signal
     * 
     * @param string $signal The name of the signal which the button is going to send
     * @param string $tooltip The tooltip text
     * @return string The code for the button
     */
    public function restart($signal, $tooltip = \NULL) {
        if (is_null($tooltip))
            $tooltip = $this->_('Restart at beginning');
        $name = "restart_$signal";
        return $this->_button($name)->setId($name)->image(self::MediaIcon('restart', $tooltip));
    }

    /**
     * Creates a stop button sending a signal
     * 
     * @param string $signal The name of the signal which the button is going to send
     * @param string $tooltip The tooltip text
     * @return string The code for the button
     */
    public function stop($signal, $tooltip = \NULL) {
        if (is_null($tooltip))
            $tooltip = $this->_('Stop');
        $name = "stop_$signal";
        return $this->_button($name)->setId($name)->image(self::MediaIcon('pause', $tooltip));
    }

    /**
     * Creates a prevous button  sending a signal
     * 
     * @param string $signal The name of the signal which the button is going to send
     * @param string $tooltip The tooltip text
     * @return string The code for the button
     */
    public function previous($signal, $tooltip = \NULL) {
        if (is_null($tooltip))
            $tooltip = $this->_('Go to previous');
        $name = "previous_$signal";
        return $this->_button($name)->setId($name)->image(self::MediaIcon('previous', $tooltip));
    }

    /**
     * Creates a next button  sending a signal
     * 
     * @param string $signal The name of the signal which the button is going to send
     * @param string $tooltip The tooltip text
     * @return string The code for the button
     */
    public function next($signal, $tooltip = \NULL) {
        if (is_null($tooltip))
            $tooltip = $this->_('Go to next');
        $name = "next_$signal";
        return $this->_button($name)->setId($name)->image(self::MediaIcon('next', $tooltip));
    }

    
    public function setRefreshingInterval($refreshingInterval) {
        $this->_refreshingInterval = $refreshingInterval;
        return $this;
    }

    public function setGranularity($granularity) {
        $this->_granularity = $granularity;
        return $this;
    }

    public function setAutostart($autostart) {
        $this->_autostart = $autostart;
        return $this;
    }

    /**
     * 
     * @param string $type
     * @param string $tooltip
     * @param string $URL The optiion 
     * @return string
     */
    public static function MediaIcon($type, $tooltip, $URL = \NULL) {
        switch($type){
            case 'eject':
                $image = "media-eject.png";
                break;
            case 'start':
                $image = "media-playback-start.png";
                break;
            case 'pause':
                $image = "media-playback-pause.png";
                break;
            case 'stop':
                $image = "media-playback-stop.png";
                break;
            case 'record':
                $image = "media-record.png";
                break;
            case 'previous':
                $image = "media-skip-backward.png";
                break;
            case 'next':
                $image = "media-skip-forward.png";
                break;
            case 'restart':
                $image = "x.png";
                break;
            case 'plus':
                $image = "player-volume-plus.png";
                break;
            case 'minus':
                $image = "player-volume-minus.png";
                break;
            case 'mute':
                $image = "media-mute.png";
                break;
        }
        $fullImagePath = "/!documents/file/images/mediaicons/$image";
        return [$fullImagePath, $type, \NULL, $tooltip];
    }

    public function setContext($context) {
        $this->_context = $context;
    }
    
}

