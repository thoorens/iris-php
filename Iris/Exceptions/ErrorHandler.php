<?php

namespace Iris\Exceptions;

use Iris\Engine\Memory,
    Iris\Engine\Program;

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
class ErrorHandler implements \Iris\Design\iSingleton {

    protected static $_Instance = NULL;
    public static $_Trace = array();

    public function getExceptionName() {
        return "Error exception";
    }

    /**
     * Get the unique instance
     * 
     * @return ErrorHandler
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new ErrorHandler("Still undetermined error", 0, NULL);
        }
        return self::$_Instance;
    }

    public static function Trap_Error($level, $message, $file, $line) {
        $instance = self::GetInstance();
        $instance->trapError($level, $message, $file, $line);
    }

    public function trapError($level, $message, $file, $line) {
        
    }

    /**
     * This methods normally wipes out all text before renewing a complete
     * error cycle: always in production mode and unless a KEEP=1 has
     * been provided in URL during development
     */
    public static function WipeAllText() {
        if (\Iris\Engine\Mode::IsProduction()) {
            $wipe = TRUE;
        }
        else {
            $keep = \Iris\Engine\Superglobal::GetGet('KEEP', 0);
            $wipe = !$keep;
        }
        while (ob_get_level() AND $wipe) {
            ob_end_clean();
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
        $errorExc = new ErrorException($mes, $no, E_ERROR, $file, $line, $previous = NULL);
        throw $errorExc;
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
                while (ob_get_level()) {// AND \Iris\Engine\Mode::IsProduction()) {
                    ob_end_clean();
                }
                $errorExc = new ErrorException($error['message'], \NULL, $error['type'], $error['file'], $error['line']);
                Memory::Set('Exception', $errorExc);
                Memory::Set('Log', \Iris\Log::GetInstance());
                $program = new \Iris\Engine\Program(\Iris\Engine\Program::$ProgramName);
                $program->run('/ERROR');
            }
            else {
                if (\Iris\Engine\Mode::IsProduction()) {
                    header('location: /ERROR');
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
     * @return ErrorHandler (fluent interface)
     */
    public function allException($install = \TRUE) {
        if ($install) {
            set_error_handler(array($this, 'error2Exception'));
            // capture all fatal errors
            register_shutdown_function(array($this, 'captureShutdown'));
        }
        return $this;
    }

    /**
     *
     * @return ErrorHandler (fluent interface)
     */
    public function setParameters() {
        $application = \Iris\Engine\Program::$ProgramName;
        $logFile = IRIS_ROOT_PATH . '/' . $application . '/log/system.log';
        // read config file
        if (\FALSE) {
            
        }
        elseif (\Iris\Engine\Mode::IsProduction()) {
            error_reporting(E_ALL);
            ini_set('track_error', 'off');
            ini_set('display_errors', 'off');
            ini_set('log_errors', 'on');
            ini_set('error_log', $logFile);
            ini_set('log_errors_max_len', '1024');
        }
        else {
            error_reporting(E_ALL | E_STRICT);
            ini_set('track_error', 'off');
            ini_set('display_errors', 'on');
            ini_set('log_errors', 'on');
            ini_set('error_log', $logFile);
            ini_set('log_errors_max_len', '1024');
        }
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

}

?>
