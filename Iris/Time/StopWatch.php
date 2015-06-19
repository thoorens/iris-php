<?php

namespace Iris\Time;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
class StopWatch {

        /**
     * The time at which the stopwatch has been started or restarted
     * @var string
     */
    protected $_startTime;
    
    /**
     * The time at which the stopwatch has benn stopped
     * @var string
     */
    private $_stopTime;
    
    /**
     * Creates a new stopwatch
     * @param string $startTime the optional initial time
     * @param boolean $runTimeSW if TRUE, the stopwatch measures execution time
     */
    public function __construct($startTime = NULL) {
        // if not time available, get current
        if (is_null($startTime)) {
            $startTime = microtime();
        }
        $this->_startTime = $startTime;
    }

    /**
     * Resets or restarts the stopwatch
     */
    public function restart(){
        $this->_startTime = microtime();
    }
    
    /**
     * Stops the stopwatch by storing the current time
     */
    public function stop(){
        $this->_stopTime = microtime();
    }
    
    /**
     * reads the duration between start and stop time, optionaly stopping
     * the watch.
     * 
     * @param boolean $stop If TRUE, stops the watch before calculating the elapsed time
     * @return string
     */
    public function duration($stop = \FALSE){
        if($stop){
            $this->stop();
        }
        return self::ComputeInterval($this->_startTime, $this->_stopTime);
    }
    /**
     * Gets a temporary duration (split time). Not used internally but 
     * 
     * @return float
     */
    public function split() {
        return self::ComputeInterval($this->_startTime, microtime());
    }

    /**
     * 
     * @param type $timeFrom
     * @param type $timeTo
     * @return string 
     */
    public static function ComputeInterval($timeFrom, $timeTo) {
        $seconds = self::_GetSeconds($timeTo) - self::_GetSeconds($timeFrom);
        $seconds += (self::_GetMicroseconds($timeTo) - self::_GetMicroseconds($timeFrom));
        return sprintf('%0.4F', $seconds);
    }

    /**
     * 
     * @param type $microTime
     * @return int
     */
    private static function _GetSeconds($microTime) {
        return explode(' ', $microTime)[1];
    }

    /**
     * Get the float part of microtime
     * 
     * @param string $microTime
     * @return float (<0) 
     */
    private static function _GetMicroseconds($microTime) {
         return explode(' ', $microTime)[0];
    }

    
}

