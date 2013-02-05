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
 * A series of counters, wich may be linked to dom object where to display
 * their values and send a message in a bottle.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _Counter extends \Iris\Subhelpers\_Subhelper {

    protected static $_Instance = \NULL;
    protected $_counterName = 'counter';

    const DOWN = 0;
    const UP = 1;

    /**
     * Required by the master class, but not used
     * @return \Iris\views\helpers\_ViewHelper
     */
    protected function _provideRenderer() {
        return \Iris\MVC\_Helper::HelperCall('counter');
    }

    
    /**
     * Permits to give a name to the counter (in case there is many). The name defaults to 
     * "counter". It corresponds to a javascript fonction name.
     * 
     * @param string $counterName
     * @return \Iris\Ajax\_Counter for fluent interface
     */
    public function setId($counterName) {
        $this->_counterName = $counterName;
        return $this;
    }
    
    /**
     * A count down counter from max to 0. May be link to an DOM object.
     * 
     * @param string $messageId The message Id
     * @param int $max the max value to start from
     * @param string $objectName The optional DOM object to serve as display zone
     * @param array $args The argument to be send with the message
     */
    public function down($messageId, $max, $objectName = \NULL, $args = array()) {
        if (!is_null($objectName)) {
            $this->_linkedCounter(self::DOWN, $messageId, $max, $objectName, $args);
        }
        else {
            $this->_unlinkedCounter(self::DOWN, $messageId, $max, $args);
        }
        $counterName = $this->_counterName;
        $this->_renderer->javascriptStarter("$counterName", "$counterName($max)");
    }

    /**
     * A count up counter from 0 to max. May be link to an DOM object.
     * 
     * @param string $messageId The message Id
     * @param int $max the max value to grow to from 0
     * @param string $objectName The optional DOM object to serve as display zone
     * @param array $args The argument to be send with the message
     */
    public function up($messageName, $max, $objectName = \NULL, $args = array()) {
        if (!is_null($objectName)) {
            $this->_linkedCounter(self::UP, $messageName, $max, $objectName, $args);
        }
        else {
            $this->_unlinkedCounter(self::UP, $messageName, $max, $args);
        }
        $counterName = $this->_counterName;
        $this->_renderer->javascriptStarter("$counterName", "$counterName(-1)");
    }

    /**
     * The variable parts of the implicit loop<ul>
     * <li> the operator (++ or --)
     * <li> the test (==max or ==0)
     * </ul>
     * 
     * @param int $mode UP or DOWN
     * @param int $max The max value (it is incremented or decremented by 1 to compensate
     * @return array(operator + test)
     */
    protected function _engine($mode, &$max) {
        if ($mode == self::UP) {
            $operator = '++';
            $test = "== $max";
            $max--;
        }
        else {
            $operator = '--';
            $test = "== 0";
            $max++;
        }
        return [$operator, $test];
    }
    
    /**
     * An inner function, implementation dependent, for a counter linked to a DOM object
     */
    protected abstract function _linkedCounter($mode, $messageName, &$max, $objectName, $args);

    /**
     * An inner function, implementation dependent, for a counter without link to a DOM object
     */
    protected abstract function _unlinkedCounter($mode, $messageName, &$max, $args);

    

    /**
     * A layout for the arguments (implementation dependent) 
     * @param array $args
     * @return string
     */
    protected abstract function _implodeArgs(array $args);

}