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


