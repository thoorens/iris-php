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
 * This class tries to store all information about an error before the
 * system restarts in error mode
 * 
 */
class ErrorInformation implements \Iris\Design\iSingleton {

    /**
     * The unique instance.
     * 
     * @var ErrorInformation
     */
    private static $_Instance = \NULL;
    
    private $_testMessage = \NULL;
    
    private $_exception = \NULL;
    
    /**
     *
     * @var string
     */
    private $_url = \NULL;
    
    /**
     *
     * @var boolean
     */
    private $_prepared = \FALSE;

    public function getTestMessage() {
        return $this->_testMessage;
    }

    public function setTestMessage($testMessage) {
        $this->_testMessage = $testMessage;
    }

    
    /**
     * Get the unique instance
     * 
     * @return ErrorInformation
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    public function prepareErrorDiplay($exception){
        $subHelper = \Iris\Subhelpers\ErrorDisplay::GetInstance();
        $this->_url = $subHelper->prepareExceptionDisplay($exception);
        //$this->_prepared = \TRUE;
    }

    /**
     *
     * @param string $initialUrl
     * @return boolean 
     */
    public function errorDisplayReady(&$initialUrl){
        $initialUrl = $this->_url;
        return $this->_prepared;
    }
    
    public function getException() {
        return $this->_exception;
    }

    public function setException($exception) {
        $this->_exception = $exception;
    }


}
