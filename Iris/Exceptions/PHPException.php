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
 * An encapsulation of standard exceptions to mimic the _Exception 
 * behaviour.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class PHPException extends _Exception {

    /**
     * A copy of system trace for standard exceptions
     * @var Exception[]
     * @todo not read
     */
    protected $_trace = [];

    /**
     * A copy of system trace (as a string) for standard exceptions
     * @var string
     * @todo not read
     */
    protected $_traceAsString = NULL;

    /**
     *
     * @var \Exception
     */
    private $_exception;
    /**
     * A way to encapsulate all exceptions in a \Iris\Exceptions\_Exception
     * 
     * @param \Exception $exception 
     * @return _Exception 
     */
    public static function Encapsulate($exception) {
        while($exception->getPrevious()!=\NULL){
            $exception = $exception->getPrevious();
        }
        if ($exception instanceof _Exception) {
            return $exception;
        }
        else {
            return new PHPException($exception);
        }
    }

    public function __construct(\Exception $exception) {
        $this->_phpException = $exception;
        $code = $exception->getCode();
        $code = is_numeric($code) ? $code : 0;
        parent::__construct($exception->getMessage(), $code, $exception->getPrevious());
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        $this->_trace = $exception->getTrace();
        $this->_traceAsString = $exception->getTraceAsString();
    }

    /**
     * In the case of true PHP, one has to get traces from the copy.
     * 
     * @param type $mode
     * @return type 
     */
    public function getIrisTrace($mode,$lineTitle=\FALSE) {
        if($this->_exception instanceof \PDOException){
            if($mode==self::MODE_STRING){
                return $this->_exception->getTraceAsString();
            }
            else{
                return $this->_exception->getTrace();
            }
        }
        else{
            return parent::getIrisTrace($mode);
        }
    }

    public function getExceptionName() {
        return "System PHP Exception";
    }

}

