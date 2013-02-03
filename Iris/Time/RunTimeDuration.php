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
 * the execution time of the main controller but may be used for other goals).
 * It may also be used as a standard stopwatch
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class RunTimeDuration extends StopWatch {

    use \Iris\Translation\tSystemTranslatable;

    /**
     * If TRUE, enables the execution time display (no effect in 
     * production environment)
     * 
     * @var boolean
     */
    protected static $_DisplayRunTimeDuration = FALSE;

    /**
     * The display mode is either INNERCODE (javascript code contained in
     * the page) or AJAX (managed by the Ajax version of the admin tool bar)
     * 
     * @var int
     */
    public static $DisplayMode = self::INNERCODE;

    /**
     * Values for DisplayMode
     */

    const INNERCODE = 1;
    const AJAX = 2;

    public function __construct($startTime = NULL) {
        // try to find the best startTime from context
        $startTime = $this->_runTimeCorrection($startTime);
        parent::__construct($startTime);
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
            if (self::$DisplayMode == self::INNERCODE) {
                $javascriptCode = $this->_defaultDisplay($duration, $componentId);
            }
            else {// self::AJAX
                $_SESSION['PreviousTime'] = $duration;
            }
        }
        return $javascriptCode;
    }

    /**
     * A default javascript routine to display 
     * @param string $duration The execution time 
     * @param string $componentId The id of the component to write in
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
     * Sets the display mode of run time duration either to INNERCODE
     * or AJAX. In AJAX mode, the RTD is taken from a session variable
     * and does not count the admintoolbar display time.
     * 
     * @param int $displayMode (AJAX or INNERCODE)
     */
    public static function setDisplayMode($displayMode) {
        self::$DisplayMode = $displayMode;
    }

}

