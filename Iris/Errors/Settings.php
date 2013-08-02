<?php

namespace Iris\Errors;

use Iris\Engine\Memory;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * Thank you to Eric Daspet and Cyril Pierre de Geyser
 * for their clear explanations in their book
 * "PHP avancé" (Editions Eyrolles)
 */

/**
 * This class groups all the error parameters
 * 
 */
class Settings implements \Iris\Design\iSingleton {

    use \Iris\Engine\tInitedSingleton;

    const TYPE_STANDARD = 1;
    const TYPE_PRIVILEGE = 2;
    const TYPE_FATAL = 3;



    /**
     * Determines if the handler keep the emited text
     */
    const KEEP = 1;

    /**
     * Determines if the handler uses a log file
     */
    const LOG = 2;

    /**
     * Determines if the handler sends emails
     */
    const MAIL = 4;

    /**
     * Determines if the handler hangs on error (only for test)
     */
    const HANG = 8;

    /**
     * Determines if a final fatal error must be display
     */
    const FATAL = 16;
    
    /**
     * Determines if it is convenient to display flags in error toolbar
     */
    const FLAGS = 32;

    public static $InitialFlags = \NULL;

    /**
     * The error flags (inited from self::$InitialFlags
     * @var int
     */
    private $_Errorflags;

    /**
     * The default error controller
     * @var string
     */
    protected $_defaultController = "/Error";

    /**
     * The log file name
     * @var string
     */
    protected $_logFile = "/log/iris.log";

    /**
     * A default title for error screen
     * 
     * @var string
     */
    protected $_title = "Iris - Error";
    protected $_mailSender = \FALSE;

    /**
     * TRUE if in production
     * 
     * @var boolean
     */
    private $_isProduction;

    /**
     * The level of stack to display
     * @var int
     */
    private $_errorStackLevel = \NULL;

    /**
     * Initialises the flags from the static $InitialFlags and
     * from URL variables
     */
    protected function _init() {
        $this->_isProduction = \Iris\Engine\Mode::IsProduction();
        if (is_null(self::$InitialFlags)) {
            // by default the only set flag is FATAL
            self::$InitialFlags = self::FATAL;
        }
        $this->_Errorflags = self::$InitialFlags;
        $this->_forceSettings();
    }

    /*
     * =========================================================
     * Setters and getters
     * =========================================================
     */

    /**
     * Set or resets a bit or various bit in the flags
     * 
     * @param type $bit
     * @param type $set
     */
    private function _fixBit($bit, $set = \TRUE) {
        if ($set) {
            $this->_Errorflags |= $bit;
        }
        else {
            $bits = -1 ^ $bit;
            $this->_Errorflags &= $bits;
        }
    }

    /**
     * Gets from URL optional ERROR parameters and
     * modifies the settings accordingly
     */
    private function _forceSettings() {
        if (isset($_GET['ERROR'])) {
            $errorValue = $_GET['ERROR'];
            if (is_numeric($errorValue)) {
                $this->_fixBit($errorValue);
            }
            else {
                switch ($errorValue) {
                    case 'HANG':
                        $this->setHang();
                        break;
                    case 'KEEP':
                        $this->setKeep();
                        break;
                    case 'LOG':
                        $this->setLog();
                        break;
                    case 'MAIL':
                        $this->setMail();
                        break;
                    case 'NOFATAL':
                        $this->unsetFatal();
                }
            }
        }
        if (isset($_GET['ERRORSTACK'])) {
            $this->setErrorStackLevel($_GET['ERRORSTACK']);
        }
    }

    /**
     * Test if the echoed text must be kept and not erased before 
     * displaying the error messages
     * 
     * @return boolean
     */
    public function hasKeep() {
        return $this->_Errorflags & self::KEEP;
    }

    /**
     * Specifies that all echoed text must be kept before 
     * displaying the error messages
     */
    public function setKeep() {
        if (!$this->_isProduction) {
            $this->_fixBit(self::KEEP);
        }
    }

    /**
     * Specifies that all echoed text must be erased before 
     * displaying the error messages
     */
    public function unsetKeep() {
        $this->_fixBit(self::KEEP, \FALSE);
    }

    /**
     * Returns TRUE if the Handler uses a log file
     * 
     * @return booelan
     */
    public function hasLog() {
        return $this->_Errorflags & self::LOG;
    }

    /**
     * Requires the use of a log file
     */
    public function setLog() {
        $this->_fixBit(self::LOG);
    }

    /**
     * Disables the use of a log file
     */
    public function unsetLog() {
        $this->_fixBit(self::LOG, \FAlse);
    }

    /**
     * If true sends a mail on error
     * @return boolean 
     */
    public function hasMail() {
        return $this->_Errorflags & self::MAIL;
    }

    /**
     * Orders the handler to send a email to the administrator
     */
    public function setMail() {
        $this->_fixBit(self::MAIL);
    }

    /**
     * Forbits the handler from sending an email to the administrator
     */
    public function unsetMail() {
        $this->_fixBit(self::MAIL, \FALSE);
    }

    /**
     * Determines if the program need not to transform errors to exception
     * @return boolean
     */
    public function hasHang() {
        return $this->_Errorflags & self::HANG;
    }

    /**
     * Disables the error to exception transformation (only in development)
     * 
     */
    public function setHang() {
        if (!$this->_isProduction) {
            $this->_fixBit(self::HANG);
        }
    }

    /**
     * Enables the error to exception transformation
     */
    public function unsetHang() {
        $this->_fixBit(self::HANG, \FALSE);
    }

    /**
     * Determines if a final fatal message must be sent in case of an error
     * not managed
     * 
     * @return boolean
     */
    public function hasFatal() {
        return $this->_Errorflags & self::FATAL;
    }

    /**
     * Orders to fire a final fatal message if necessary
     */
    public function setFatal() {
        $this->_fixBit(self::FATAL);
    }

    /**
     * Prohibits to fire any final fatal message
     */
    public function unsetFatal() {
        $this->_fixBit(self::FATAL, \FALSE);
    }

    public function hasFlag() {
        return $this->_Errorflags & self::FLAGS;
    }

    public function setFlags(){
        $this->_fixBit(self::FLAGS);
    }
    
    public function unsetFlags(){
        $this->_fixBit(self::FLAGS, \FALSE);
    }
    /* ---------------------------------------------------------------------------- */
    
    /**
     * Returns the internal flags (for debugging purpose)
     * @return int
     */
    public function getErrorflags() {
        return $this->_Errorflags;
    }

    /**
     * Gets the default errror controller
     * @return string
     */
    public function getDefaultController() {
        return $this->_defaultController;
    }

    /**
     * Sets the default error controller
     * 
     * @param string  $defaultController
     * @return \Iris\Errors\Handler for fluent interface
     */
    public function setDefaultController($defaultController) {
        $this->_defaultController = $defaultController;
        return $this;
    }

    /**
     * Gets the log file name (relative to application folder)
     * @return string
     */
    public function getLogFile() {
        return $this->_logFile;
    }

    /**
     * Sets the log file name
     * @param type $logFile
     */
    public function setLogFile($logFile) {
        $this->_logFile = $logFile;
    }

    /**
     * Gets the error screen title
     * 
     * @return string
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Sets the error screen title
     *  
     * @param string $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * Sets the error stack level to display (default to \NULL : no display)
     * 
     * @param int $value
     */
    public function setErrorStackLevel($value) {
        $this->_errorStackLevel = $value;
    }

    /**
     * Gets the error stack level (if \NULL : no display)
     * 
     * @return int
     */
    public function getErrorStackLevel() {
        return $this->_errorStackLevel;
    }


}

