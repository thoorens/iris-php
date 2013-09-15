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
 * An exception thrown when an error in a view occurs (evaluation of
 * a script)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class ViewException extends _Exception {

    private $_fileName;
    private $_lineNumber;
    private $_errorMessage;
    private $_viewType;

    public function setViewType($viewType) {
        $this->_viewType = $viewType;
    }

    public function setErrorMessage($message) {
        $this->_errorMessage = $message;
    }

    public function getExceptionName() {
        return "View exception";
    }

    public function setFileName($fileName) {
        $this->_fileName = $fileName;
    }

    public function setLineNumber($lineNumber) {
        $this->_lineNumber = $lineNumber;
    }

    public function __toString() {
        $format = "Error in a %s : %s <br>File : %s<br>Line : %s <br>\n";
        $message = sprintf($format, $this->_viewType, $this->_errorMessage, 
                $this->_fileName, $this->_lineNumber);
        return $message;
    }

    /**
     * Acessor get for the file name containing the error
     * 
     * @return string
     */
    public function getFileName() {
        return $this->_fileName;
    }

    /**
     * Accessor get for the line number
     * 
     * @return int
     */
    public function getLineNumber() {
        return $this->_lineNumber;
    }

    /**
     * Accessor get for the error message
     * 
     * @return string
     */
    public function getErrorMessage() {
        return $this->_errorMessage;
    }

    /**
     * Acessor get for the view type
     * 
     * @return string
     */
    public function getViewType() {
        return $this->_viewType;
    }

}


