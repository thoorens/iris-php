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
abstract class _Exception extends \Exception implements iException{
    
    /**
     *
     * @var int The default error code for this class
     */
    public static $ErrorCode = 11111111;
    
    
    
    /**
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous 
     */
    public function __construct($message, $code = \NULL, $previous = \NULL) {
        \Iris\Log::Save(); // all output are going to be wiped out
        if(is_null($code)){
            $code = static::$ErrorCode;
        }
        parent::__construct($message, $code, $previous);
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

    
    
}

