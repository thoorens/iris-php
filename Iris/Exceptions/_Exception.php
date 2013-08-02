<?php

namespace Iris\Exceptions;

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
 * An extension of \Exception having more methods.
 * getTrace and getTraceAsString are deprecated (as final they can't be
 * overridden: use getIrisTrace(boolean $asString) instead).
 * This method violates the MVC paradigm, but we are preparing
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Exception extends \Exception {
    /**
     * This mode corresponds to getTraceAsString from \Exception
     */

    const MODE_STRING = 1;
    /**
     * In this mode, each trace is placed as a string in an array
     */
    const MODE_ARRAY = 2;
    /**
     * This mode combines both to be displayed with tooltips
     */
    const MODE_BOTH = 3;

    /**
     * To avoid memory problems, the size of the trace string is limited
     * to 5 000 characters (only the beginning is interesting)
     */
    const MAX_TRACE_STRING_SIZE = 5000;

    /**
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous 
     */
    public function __construct($message, $code = 0, $previous = NULL) {
        \Iris\Log::Save(); // all output are going to be wiped out
        parent::__construct($message, NULL, $previous);
    }

    /**
     * A substitute for getTrace and getTraceAsString.
     * @param int $mode
     * @return type 
     */
    public function getIrisTrace($mode, $lineTitle = FALSE) {
        switch ($mode) {
            // in this mode, we have a single string prepared by the
            // classical method
            case self::MODE_STRING:
                $trace = $this->getTraceAsString();
                break;
            // in this mode, the trace consists in an array (whose components
            // are transformed in string with html entities)
            case self::MODE_ARRAY:
                $trace = $this->_formatedTrace($lineTitle);
                break;
            // in this mode, the array has indices which are the string found in string mode
            // and values those of array mode (intented to be displayed throug dojo_TootTips)
            case self::MODE_BOTH:
                $traceIndex = explode("\n", htmlentities($this->getTraceAsString()));
                $traceValue = $this->_formatedTrace();
                $traceValue[] = array(); // the next array misses
                $trace = array_combine($traceIndex, $traceValue);
        }
        return $trace;
    }

    /**
     * Converts the array of array of getTrace in an array of string with
     * html entities and an optional title
     * 
     * @param boolean $lineTitle
     * @return array
     */
    private function _formatedTrace($lineTitle = FALSE) {
        $trace = $this->getTrace();
        array_walk($trace, array($this, '_format'), $lineTitle);
        return $trace;
    }

    /**
     * Formats an array as a string with htmlentities and an optional
     * title line (to be used by _formatedTrace)
     * 
     * @param array $value
     * @param int $key
     * @param boolean $lineTitle 
     */
    private function _format(&$value, $key, $lineTitle) {
        if ($lineTitle) {
            $title = "<h6>Line $key</h6>";
        }
        else {
            $title = '';
        }
        $format = print_r($value, TRUE);
        $format = strlen($format) > self::MAX_TRACE_STRING_SIZE ? substr($format, 0, self::MAX_TRACE_STRING_SIZE) . CRLF . '[the rest has been skipped]' : $format;
        $value = $title . '<pre>' . htmlentities($format) . '</pre>';
    }

    /**
     * Gets the last exception thrown
     * 
     * @param \Iris\Exceptions\_Exception $exception
     * @return \Iris\Exceptions\_Exception
     */
    public static function GetLastException($exception = \NULL) {
        return \Iris\Engine\Memory::Get('untreatedException', $exception);
    }

    abstract public function getExceptionName();

    public function setComment($commentName) {
        \Iris\Engine\Memory::Set('ErrorComment', $commentName);
    }

    public function setDebugVar($name, $value) {
        
    }

    public function prepareDisplay() {

        return array($errorMessage, $trace, $title);
    }

    /**
     * By default, an exception displays its message.
     * @return string
     */
    public function __toString() {
        return $this->getMessage();
    }

    public function getTitle() {
        return $this->getExceptionName();
    }

    /**
     * This method analyses the first Router to get all its parameters
     * and keep them for later use.
     * 
     * @staticvar array $MCAPU
     * @param type $field
     * @return type 
     */
    protected function _getfirstMCAPU($field) {
        static $MCAPU = array();
        if (count($MCAPU) == 0) {
            $MCAPU = array('', '', '', '', '');
            $router = \Iris\Engine\Router::GetInstance();
            if (!is_null($router->getPrevious())) {
                $router = $router->getPrevious();
            }
            $firstUrl = $router->getAnalyzedURI();
            $MCAPU = explode('/', $firstUrl);
            $parameters = '== unavailable ==';
            $trace = \Iris\Errors\Handler::$_Trace;
            foreach ($trace as $t) {
                if ($t['MODULE'] . '/' . $t['CONTROLLER'] . '/' . $t['ACTION'] == $firstUrl) {
                    $parameters = implode('/', $t['PARAMETERS']);
                    break; // please Dijkstra, don't read this line
                }
            }
            $MCAPU[3] = $parameters;
            $MCAPU[4] = $firstUrl;
        }
        return $MCAPU[$field];
    }

    /**
     * Gets the original module name
     * 
     * @return string
     */
    public function getModule() {
        return $this->_getfirstMCAPU(0);
    }

    /**
     * Gets the original controller name
     * 
     * @return string
     */
    public function getController() {
        return $this->_getfirstMCAPU(1);
    }

    /**
     * Gets the original action name
     * 
     * @return string
     */
    public function getAction() {
        return $this->_getfirstMCAPU(2);
    }

    /**
     * Gets the original parameters (as a / separated string)
     * 
     * @return string
     */
    public function getParameters() {
        return $this->_getfirstMCAPU(3);
    }

    public function getErrorComment() {
        return 'comment';
    }

    /**
     * Gets the supposed completed original URL
     * 
     * @return string
     */
    public function getFirstURL() {
        return $this->_getfirstMCAPU(4);
    }

}

