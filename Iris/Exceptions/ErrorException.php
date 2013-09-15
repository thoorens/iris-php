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
 * An exception thrown by the Handler
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class ErrorException extends _Exception {

    private $_severity;

    public function __construct($message, $code, $severity, $filename, $lineno, $previous=\NULL) {
        $this->message = $message;
        $this->code = $code;
        $this->_severity = $severity;
        $this->file = $filename;
        $this->line = $lineno;
        $this->previous = $previous;
    }

    /**
     * The only missing accessor in _Exception (for compatibility with \ErrorException).
     * Returns the severity level
     * 
     * @return int Severity level
     */
    public function getSeverity() {
        return $this->_severity;
    }

    public function getExceptionName() {
        return "Error exception";
    }

    public function prepareDisplay() {
        parent::prepareDisplay();
        $message = $this->getMessage();
        $message .= '<br>in file '.$this->getFile().' line '.$this->getLine();
        $this->__errorMessage = $message;
    }

}


