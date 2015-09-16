<?php
namespace Iris\Exceptions;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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


