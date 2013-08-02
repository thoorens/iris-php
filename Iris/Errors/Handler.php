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
 * This class groups everything needed to
 * treat error in an application
 * 
 */
class Handler implements \Iris\Design\iSingleton {

    use \Iris\Translation\tSystemTranslatable;

    public static $_Trace = array();

    /**
     *
     * @var Settings
     */
    private $_errorSettings;

    /**
     * Returns the unique instance or creates it if necessary.
     * 
     * @staticvar \static $Instance Serves to store the unique instance
     * @return Iris\Errors\Handler
     */
    public static function GetInstance() {
        static $Instance = \NULL;
        if (is_null($Instance)) {
            $Instance = new static();
            $Instance->_init();
        }
        return $Instance;
    }

    /**
     * The main method: what to do with an exception
     * 
     * @parap \Iris\Engine\Program $program the program singleton
     * @param Iris\Exceptions\_Exception $exception The not treated exception
     * @param string $ErrorURI The URI of the current error (NULL if first error)
     */
    public function treatException($program, $exception, $ErrorURI) {
        $errorInformation = \Iris\Errors\ErrorInformation::GetInstance();
        $errorInformation->prepareErrorDiplay($exception);
        $this->_wipeAllText();
        // First level of error
        if (is_null($ErrorURI)) {
            \Iris\Engine\Memory::Set('untreatedException', $exception);
            $program->run($this->_errorSettings->getDefaultController() . "/standard");
        }
        // There is an error in error treatment
        else {
            if (!$this->_isProduction) {
                $message = sprintf("%s <br>In <b>%s</b> (line <b>%s</b>)", $exception->getMessage(), $exception->getFile(), $exception->getLine());
                //iris_debug($exception->getTrace());
                \Iris\Engine\Debug::ErrorBoxDie($message, $exception->getTitle());
            }
            else {
                try {
                    $URI = $this->_errorSettings->getDefaultController() . "/fatal";
                    $program->run($URI);
                    return;
                }
                catch (Exception $exc) {
                    die("Sorry");
                    echo $exc->getTraceAsString();
                }
            }
        }
    }

//                    $this->_errorInformation($exception);
//                    // Clean all message in 
//                    \Iris\Errors\ErrorHandler::WipeAllText();
//                    // in case of error in error trapping, simple error box
//                    if (!is_null($URI)) {
//                        \Iris\Engine\Debug::Kill(self::ErrorBox($exception->__toString(), 'Fatal error'));
//                    }
//                    else {
//                        \Iris\MVC\Layout::GetInstance()->setViewScriptName(\NULL);
//                        Memory::Set('Exception', $exception);
//                        Memory::Set('Log', \Iris\Log::GetInstance());
//                        //Memory::SystemTrace();
//                        $URI = \Iris\Errors\ErrorHandler::GetInstance()->getDefaultController();
//                    }   





    /* ==========================================================================  */

    public function getExceptionName() {
        return "Error exception";
    }

    public static function Trap_Error($level, $message, $file, $line) {
        $instance = self::GetInstance();
        $instance->trapError($level, $message, $file, $line);
    }

    public function trapError($level, $message, $file, $line) {
        
    }

    /**
     * This methods normally wipes out all text before renewing a complete
     * error cycle: always in production mode and unless an ERROR parameter contains
     * 1 during development
     */
    private function _wipeAllText() {
        if (($this->_isProduction) or (!$this->_errorSettings->hasKeep())) {
            while (ob_get_level()) {
                ob_end_clean();
            }
        }
    }

    /**
     * 
     * @param type $no
     * @param type $mes
     * @param type $file
     * @param type $line 
     */
    public function error2Exception($no, $mes, $file, $line) {
        $errorExc = new \Iris\Exceptions\ErrorException($mes, $no, E_ERROR, $file, $line);
        //$errorExc = new \Iris\Exceptions\ErrorException($mes, $no, \E_ERROR, $file, $line, \NULL);
        if (\Iris\Engine\Mode::IsProduction()) {
            $throw = TRUE;
        }
        else {
            $error = \Iris\Engine\Superglobal::GetGet('ERROR', 0);
            $throw = !$error;
        }
        if ($throw) {
            throw $errorExc;
        }
        else {
            $errorExc->getMessage();
        }
    }

    /**
     * This method is called at the end of the program. Tests an error. If recuperable,
     * throw an ErrorException. Other wise, do nothing in development (the error message has been printed)
     * and go to ERROR in production.
     * 
     * @return boolean
     */
    public function captureShutdown() {
        $error = error_get_last();
        if ($error) {
            if ($error['type'] == E_RECOVERABLE_ERROR) {
                $this->_wipeAllText();
                $errorExc = new ErrorException($error['message'], \NULL, $error['type'], $error['file'], $error['line']);
                Memory::Set('Exception', $errorExc);
                Memory::Set('Log', \Iris\Log::GetInstance());
                $program = new \Iris\Engine\Program(\Iris\Engine\Program::$ProgramName);
                $program->run('/ERROR');
            }
            else {
                // in production, we go back to main page after 10 seconds
                if (\Iris\Engine\Mode::IsProduction()) {
                    $message = $this->_('Sorry! An error occured in error screen. Back to main page...');
                    echo \Iris\Engine\Debug::ErrorBox($message, $this->_('Fatal error'));
                    iris_debug($error);
                    header("refresh:10;url=/");
                }
            }
        }
        else {
            return true;
        }
    }

    /**
     * Install an error handler as an exception.
     * 
     * @param boolean $install if false, no error to exception is done
     * @return Handler (fluent interface)
     */

    /**
     * @return \Iris\Errors\Handler
     */
    public function allException() {
        if (!$this->_errorSettings->hasHang()) {
            set_error_handler(array($this, 'error2Exception'));
            // capture all fatal errors
            if ($this->_errorSettings->hasFatal()) {
                register_shutdown_function(array($this, 'captureShutdown'));
            }
        }
        return $this;
    }

    /**
     * Sets the standard ini parameters for error managing
     * 
     * @param Settings $settings
     * @return Handler (fluent interface)
     */
    public function setIniParameters() {
        $application = \Iris\Engine\Program::$ProgramName;
        $mustLog = $this->_errorSettings->hasLog() ? 'on' : 'off';
        $logFile = IRIS_ROOT_PATH . '/' . $application . $this->_errorSettings->getLogFile();
        if (\Iris\Engine\Mode::IsProduction()) {
            error_reporting(E_ALL);
            ini_set('track_error', 'off');
            ini_set('display_errors', 'off');
        }
        else {
            error_reporting(E_ALL | E_STRICT);
            ini_set('track_error', 'off');
            ini_set('display_errors', 'on');
        }
        ini_set('log_errors', $mustLog);
        ini_set('error_log', $logFile);
        ini_set('log_errors_max_len', '1024');
        return $this;
    }

    /**
     * A way to collect all error parameters
     * @return array
     */
    public function showParameters() {
        $parameterList = array('track_error', 'display_errors', 'log_errors', 'error_log', 'log_errors_max_len');
        $params['error_reporting'] = error_reporting();
        foreach ($parameterList as $parameterName) {
            $params[$parameterName] = ini_get($parameterName);
        }
        return $params;
    }

    /**
     * Each entry in a controller create a new system trace.
     * 0 is the main controller
     * others are the islets and subcontrollers
     * In case of error, the last is main/ERROR/index
     *
     * @param string $type The type of controller
     * @param \Iris\Engine\Response $response  The response which created the controler
     */
    public static function SystemTrace($type, $response) {
        $trace["Type"] = $type;
        $trace["MODULE"] = $response->getModuleName();
        $trace["CONTROLLER"] = $response->getControllerName();
        $trace["ACTION"] = $response->getActionName();
        $trace["PARAMETERS"] = $response->getParameters();
        self::$_Trace[] = $trace;
    }

    protected function _init() {

        $this->_isProduction = \Iris\Engine\Mode::IsProduction();
        $this->_errorSettings = \Iris\Errors\Settings::GetInstance();
    }

}

