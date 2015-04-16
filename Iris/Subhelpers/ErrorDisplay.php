<?php

namespace Iris\Subhelpers;

use Iris\Exceptions\ExceptionContainer as EC;

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
 * This class is a subhelper for helper ErrorDisplay
 * It manages error information
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ErrorDisplay extends \Iris\Subhelpers\_LightSubhelper {

    protected static $_Instance = NULL;
    private $_title = "Fake title";
    private $_message = "Fake error message";
    private $_module = 'Fake module';
    private $_controller = "Fake controller";
    private $_action = "Fake action";
    private $_parameters = [];
    private $_trace = 'Fake system trace';
    private $_details = ["Fake details"];
    private $_comment = 'Fake comment';
    private $_systemTrace = 'Fake SystemTrace';
    private $_rawTrace;

    public function getSystemTrace() {
        return $this->_systemTrace;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function getMessage() {
        return $this->_message;
    }

    public function getModule() {
        return $this->_module;
    }

    public function getController() {
        return $this->_controller;
    }

    public function getAction() {
        return $this->_action;
    }

    public function getParameters() {
        return $this->_parameters;
    }

    public function getTrace() {
        return $this->_trace;
    }

    public function getComment() {
        return $this->_comment;
    }

    public function getDetails() {
        return $this->_details;
    }

    /**
     * Computes and stores all debugging values internally and 
     * returns the original URL
     * 
     * @return string
     */
    public function prepareExceptionDisplay($exception) {
        $this->_systemTrace = \Iris\Errors\Handler::GetSystemTrace();
        if (is_null($exception)) {
            $this->_errorMessage = 'No message';
            $this->_title = $this->_("Unkwown fatal error");
            $this->_trace = [];
            // the remaining variables are empty
            $this->_errorComment = $this->_firstModule = $this->_firstController =
                    $this->_firstAction = $this->_firstParameters = $this->_systemTrace = '';
            $firstURL = '???';
        }
        else {
            $exceptionContainer = new \Iris\Exceptions\ExceptionContainer($exception);
            $this->_rawTrace = $exceptionContainer->getTrace();
            $this->_message = $exceptionContainer->getMessage();
            if (strpos($this->_message, 'SQL') !== FALSE) {
                $this->_message.= "<br>" . \Iris\DB\Dialects\MyPDOStatement::$LastSQL;
            }
            $this->_title = $exceptionContainer->getTitle();
            $this->_trace = $exceptionContainer->getIrisTrace(EC::MODE_STRING);
            $stackLevel = \Iris\Errors\Settings::$StackLevel;
            if ($stackLevel == -1)
                $this->_details = $exceptionContainer->getIrisTrace(EC::MODE_BOTH);
            elseif (is_numeric($stackLevel))
                $this->_details = $exceptionContainer->getIrisTrace(EC::MODE_ARRAY);
            $this->_module = $exceptionContainer->getModule();
            $this->_controller = $exceptionContainer->getController();
            $this->_action = $exceptionContainer->getAction();
            $systemTrace = $this->_systemTrace;
            if (count($systemTrace) == 0) {
                $this->_parameters = [];
            }
            else {
                $this->_parameters = $systemTrace[0]['PARAMETERS']; //$exception->getParameters();
            }
            $firstURL = $exceptionContainer->getFirstURL();
        }
        return $firstURL;
    }

    public function __get($name) {
        $function = "get" . ucfirst($name);
        return $this->$function();
    }

    public function __call($name, $arguments) {
        call_user_func_array([$this->_renderer, $name], $arguments);
    }

    protected function _getRenderer() {
        return $this->_renderer;
    }

    public function addMessage($fileInfo) {
        $line = "";
        $trace = $this->_rawTrace;
        if (isset($trace[0]['args'][3])) {
            $line = " line : " . $trace[0]['args'][3];
        }
        $this->_message .= $fileInfo . $line;
    }

}

