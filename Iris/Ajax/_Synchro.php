<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Ajax;

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

    protected static $_Instance = \NULL;

    /**
     * The max time for synchro (default 1 minute)
     * @var int
     */
    protected $_max;
    protected $_refreshingInterval = 1000;
    protected $_granularity = 100;
    protected $_autostart = \TRUE;

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

    public function button($name) {
        list($signal, $mess) = explode('_', $name);
        $functionCode = "topic.publish('$mess','$signal')";
        $this->callViewHelper('eventManager')->addModules(['topic' => 'dojo/topic'])->onclick($name, $functionCode);
        return $this->callViewHelper('button');
    }

    public function start($message) {
        $label = $this->_('Start');
        $description = $this->_('Description of start');
        $name = "start_$message";
        return $this->button($name)->setId($name)->button($label, \NULL, $description);
    }

    public function restart($message) {
        $label = $this->_('Restart');
        $description = $this->_('Description of restart');
        $name = "restart_$message";
        return $this->button($name)->setId($name)->button($label, \NULL, $description);
    }

    public function stop($message) {
        $label = $this->_('Stop');
        $description = $this->_('Description of stop');
        $name = "stop_$message";
        return $this->button($name)->setId($name)->button($label, \NULL, $description);
    }

    public function next($message) {
        $label = $this->_('Next');
        $description = $this->_('Description of next');
        $name = "next_$message";
        return $this->button($name)->setId($name)->button($label, \NULL, $description);
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


}

