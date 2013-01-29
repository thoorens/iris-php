<?php

namespace Iris\Time;

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
 * A tool to measure time duration (it provides specific methods to measure
 * the execution time of the main controller but may be used for other goals)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class StopWatch {
    use \Iris\Translation\tSystemTranslatable;
    
    /**
     * If TRUE, disable the execution time display
     * 
     * @var boolean
     */
    protected static $_DisplayRunTimeDuration = FALSE;

    /**
     * The time at which the stopwatch has been started or restarted
     * @var string
     */
    protected $_startTime;

    /**
     * Creates a new stopwatch
     * @param string $startTime the optional initial time
     * @param boolean $runTimeSW if TRUE, the stopwatch measures execution time
     */
    public function __construct($startTime=NULL, $runTimeSW = FALSE) {
        // if execution time measure, try to find the best startTime from context
        if ($runTimeSW) {
            $startTime = $this->_runTimeCorrection($startTime);
        }
        // if not time available, get current
        if (is_null($startTime)) {
            $startTime = microtime();
        }
        $this->_startTime = $startTime;
    }

    /**
     * Get a temporary duration (split time
     * @return float
     */
    public function split() {
        return self::ComputeInterval($this->_startTime, microtime());
    }

    public static function ComputeInterval($timeFrom, $timeTo) {
        $seconds = self::_GetSeconds($timeTo) - self::_GetSeconds($timeFrom);
        $seconds += (self::_GetMicroseconds($timeTo) - self::_GetMicroseconds($timeFrom));
        return sprintf('%0.4F',$seconds);
    }

    /**
     * If convenient (development and no disabling of function), 
     * replace a component (identified by its id) by a execution time duration display
     * 
     * @param string $componentId The empty component to be replaced by a 
     * execution time display
     * 
     * @return string  
     */
    public function jsDisplay($componentId = 'iris_RTD') {
        $javascriptCode = '';
        // Display may be disabled in special controller (loader, subcontroller...)
        if (self::$_DisplayRunTimeDuration and \Iris\Engine\Mode::IsDevelopment()) {
            $duration = $this->ComputeInterval($this->_startTime, microtime());
            $javascriptCode = $this->_defaultDisplay($duration, $componentId);
        }
        return $javascriptCode;
    }

    /**
     * A default javascript routine to 
     * @param string $duration
     * @return string
     */
    protected function _defaultDisplay($duration, $componentId) {
        $timeLabel = $this->_('Execution time');
        $javascriptCode = <<< JS
<script>
    exectime = document.getElementById('$componentId');
    exectime.innerHTML = "$timeLabel : <b>$duration</b>";
</script>

JS;
        return $javascriptCode;
    }

    /**
     * Disable the final javascript exec time routine
     * (RunTime Duration)
     * This is the default state
     * 
     * @param boolean $value 
     */
    public static function DisableRTDDisplay() {
        self::$_DisplayRunTimeDuration = \FALSE;
    }

    /**
     * Enable the final javascript exec time routine
     * (RunTime Duration)
     * No effect in production.
     * 
     * @param boolean $value 
     */
    public static function EnableRTDDisplay() {
        self::$_DisplayRunTimeDuration = \TRUE;
    }

    /**
     * Replace a given starttime (may be NULL) by a better one
     * possibly already recorded in IRIS_STARTTIME
     * 
     * @param string $startTime
     * @return string
     */
    protected function _runTimeCorrection($startTime) {
        if (defined('IRIS_STARTTIME')) {
            $startTime = IRIS_STARTTIME;
        }
        return $startTime;
    }

    /**
     * Get the int part of microtime
     * 
     * @param string $microTime
     * @return int 
     */
    private static function _GetSeconds($microTime) {
        list($micro, $seconds) = explode(' ', $microTime);
        return $seconds;
    }

    /**
     * Get the float part of microtime
     * 
     * @param string $microTime
     * @return float (<0) 
     */
    private static function _GetMicroseconds($microTime) {
        list($micro, $seconds) = explode(' ', $microTime);
        return $micro;
    }

}

?>
