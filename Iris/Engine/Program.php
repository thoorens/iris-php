<?php

namespace Iris\Engine;

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
 * First class to be called, it runs the entire process
 * with a big help from Dispatcher
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */
class Program {

    /**
     * The name of the directory containing all the application
     * (by default "program"). It is a way to have more than 
     * one site in the same physical directory.
     * 
     * @var string
     */
    public static $ProgramName = 'NONE';

    /**
     *
     * @var \Iris\Time\RunTimeDuration
     */
    public $runTimeDuration;

    /**
     * Constructor for the Program instance
     * @param string $programName the program directory (not visible)
     */
    public function __construct($programName = 'program') {
        define('IRIS_PROGRAM_PATH', IRIS_ROOT_PATH . '/' . $programName);
        $this->runTimeDuration = new \Iris\Time\RunTimeDuration(NULL);
        self::$ProgramName = $programName;
        self::AutosetSiteMode();
        Loader::GetInstance()->setApplicationName($programName);
    }

    /**
     * Analyse URL and realize a web page. One can precise an error URI
     * as a recursive entry in the process. In case of an error in the
     * error treatment, no recursive call is done.
     */
    public function run($errorURI = NULL) {
        try {
            ob_start();
            $dispatcher = new Dispatcher();
            echo $dispatcher->analyseRoute($errorURI)
                    ->prepareResponse()
                    ->preDispatch()
                    ->dispatch(); // pre and post in dispatch ??????
            $dispatcher->postDispatch();
            $text = ob_get_clean();
            \Iris\views\helpers\AutoResource::HeaderBodyTuning($text, $this->runTimeDuration);
            echo $text;
        }
        catch (\Exception $exception) {
            // RedirectException is a way to escape from the initial run method end
            if ($exception instanceof \Iris\Exceptions\RedirectException) {
                $text = ob_get_clean();
                \Iris\views\helpers\AutoResource::HeaderBodyTuning($text, $this->runTimeDuration);
                echo $text;
            }
            // true error
            else {
                $this->_errorInformation($exception);
                // Clean all message in 
                \Iris\Exceptions\ErrorHandler::WipeAllText();
                // in case of error in error trapping, simple error box
                if (!is_null($errorURI)) {
                    \Iris\Engine\Debug::Kill($this->_errorBox($exception->__toString(), 'Fatal error'));
                }
                else {
                    \Iris\MVC\Layout::GetInstance()->setViewScriptName(\NULL);
                    Memory::Set('Exception', $exception);
                    Memory::Set('Log', \Iris\Log::GetInstance());
                    //Memory::SystemTrace();
                    $this->run('/ERROR');
                }
            }
        }
    }

    /**
     * Collect, if possible, all information about error before restarting system in 
     * error state.
     * 
     * @param Exception $exception 
     */
    private function _errorInformation($exception) {
        $errorInfo = \Iris\Exceptions\ErrorInformation::GetInstance();
        $errorInfo->prepareErrorDiplay($exception);
    }

    /**
     * @deprecated (use Mode::IsDevelopment() instead)
     */
    public static function IsDevelopment($site = TRUE) {
        if (!$site) {
            throw new \Iris\Exceptions\DeprecatedException('Program::IsDevelopement() had no parameter (deprecated)');
        }
        return Mode::IsDevelopment($site);
    }

    /**
     * @deprecated (use Mode::IsProduction() instead)
     */
    public static function IsProduction($site = TRUE) {
        if (!$site) {
            throw new \Iris\Exceptions\DeprecatedException('Program::IsProduction() had no parameter (deprecated)');
        }
        return Mode::IsProduction($site);
    }

    /**
     * @deprecated (use Mode::GetSiteMode() instead)
     */
    public static function GetSiteMode() {
        return Mode::GetSiteMode();
    }

    /**
     * Initialiase the mode of the server, using EXEC_MODE et APPLICATION_ENV
     * @deprecated (use Mode::AutosetSiteMode()  instead)
     */
    public static function AutosetSiteMode() {
        Mode::AutosetSiteMode();
    }

    /**
     * @deprecated (use Mode::SetSiteMode($mode) instead)
     */
    public static function SetSiteMode($mode) {
        Mode::SetSiteMode($mode);
    }

    /**
     * Error box with title and message, for error debugging purpose
     * Should not be used in a production environment. This box is used
     * when an error occurs in error processing for an cry of despair.
     * 
     * @param string $message : error description
     * @param string $title : box title
     * @return string 
     */
    protected function _errorBox($message, $title = "Unkown class") {
        $text = '<div style="background-color:#979; color:#FFFFFF; margin:10px; padding:5px\">';
        $text .= "&nbsp;<strong>ERROR : $title</strong><hr>";
        $text .= '<pre style="background-color:#DDD;color:#008;margin:10px;font-size:0.8em;">';
        $text .= $message . '</pre><p style="margin-top:-15px">&nbsp;</p></div>';
        return $text;
    }

}

?>
