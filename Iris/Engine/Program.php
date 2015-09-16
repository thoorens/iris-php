<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
     * @var \Iris\Time\RuntimeDuration
     */
    private $_runtimeDuration;

    /**
     * Constructor for the Program instance
     * @param string $programName the program directory (not visible)
     */
    public function __construct($programName = 'program') {
        define('IRIS_PROGRAM_PATH', IRIS_ROOT_PATH . '/' . $programName);
        $this->_log($programName);
        $this->_runtimeDuration = new \Iris\Time\RuntimeDuration(NULL);
        self::$ProgramName = $programName;
        Loader::GetInstance()->setApplicationName($programName);
    }

    /**
     * If run without URI, analyses the URL and sends a page.
     * If case of untreated exception
     * If run with an error URI, tries to send an error page
     * 
     * @param string $URI
     */
    public function run($URI = \NULL) {
        try {
            ob_start();
            $dispatcher = new Dispatcher();
            echo $dispatcher->analyseRoute($URI)
                    ->prepareResponse()
                    ->preDispatch()
                    ->dispatch(); // pre and post in dispatch ??????
            $dispatcher->postDispatch();
            $done = \TRUE;
        }
        catch (\Exception $exception) {
            // RedirectException is a way to escape from the initial run method end
            // It is not properly an error exception
            if ($exception instanceof \Iris\Exceptions\RedirectException) {
                $done = \TRUE;
            }
            else {
                \Iris\Errors\Handler::GetInstance()->treatException($this, $exception, $URI);
            }
        }
        $text = ob_get_clean();
        \Iris\Subhelpers\Head::HeaderBodyTuning($text, $this->_runtimeDuration);
        echo $text;
    }

    /**
     * This method defines a standard parameter setting for the log file.
     * 
     */
    private function _log($program) {
        ini_set('log_error', \Iris\Errors\Settings::$Log);
        $logFile = \Iris\Errors\Settings::$LogFile;
        ini_set('error_log', IRIS_ROOT_PATH . "/$program/$logFile");
    }

}
