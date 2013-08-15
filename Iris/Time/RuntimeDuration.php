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
class RuntimeDuration extends StopWatch{

    
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
        if (\Iris\SysConfig\Settings::HasDisplayRuntimeDuration() and \Iris\Engine\Mode::IsDevelopment()) {
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
        $durationDisplay = \Iris\views\helpers\_ViewHelper::HelperCall('ILO_executionTime',$duration);
        $javascriptCode = <<< JS
<script>
    exectime = document.getElementById('$componentId');
    exectime.innerHTML = "<b>$duration</b>";
</script>

JS;
        return $javascriptCode;
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

    

}

