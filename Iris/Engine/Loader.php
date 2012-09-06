<?php

/* =========================================================================
 * This file contains 3 functions
 * 
 *      - __autoload
 *      - iris_assert
 *      - iris_debug
 * 
 * and 4 classes:
 * 
 *      - Debug
 *      - Mode
 *      - PathArray
 *      - Loader
 * 
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
 * =========================================================================
 */

namespace {


    /**
     * This magic function load a class file automatically 
     * 
     * @param string $className The class to load
     */
    function __autoload($className) {
        $loader = \Iris\Engine\Loader::GetInstance();
        if (!$loader->overridden($className)) {
            $loader->doLoad($className);
        }
    }

    function iris_assert($script, $line, $message) {
        $msg = "Error in <b>$script</b> at line <b>$line</b><br/>";
        list($expression, $comment) = explode('//', $message . '//');
        $msg .= "Tested expression: <b>$expression</b><br/>";
        $msg .= "Message : $comment";
        die($msg);
    }

    function iris_debug($var, $die = TRUE, $Message=NULL) {
        if ($die) {
            \Iris\Engine\Debug::DumpAndDie($var, $Message, 1);
        }
        else {
            \Iris\Engine\Debug::Dump($var);
        }
    }

    assert_options(ASSERT_BAIL, 1);
    assert_options(ASSERT_CALLBACK, 'iris_assert');
}

namespace Iris\Engine {

    use Iris\Exceptions as IX;

/* =========================================================================
     * c l a s s      D E B U G
     * =========================================================================
     */

    /**
     * Debug offers a way to dump variable during development
     *
     * 
     * @author Jacques THOORENS (irisphp@thoorens.net)
     * @see http://irisphp.org
     * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $     * 
     * 
     */
    abstract class Debug {
        const NONE = 0;
        const ALL = -1;
        const LOADER=1;
        const ROUTE = 2;
        const DB = 4;
        const VIEW = 8;
        const HELPER = 16;
        const ACL = 32;
        const FILE = 64;

        /**
         * Display a var_dump between <pre> tags
         * @param mixed $var : a var or text to dump
         */
        public static function Dump($var) {
            echo "<pre>\n";
            var_dump($var);
            echo "</pre>\n";
        }

        /**
         * Display a var_dump between <pre> tags and die
         *
         * @param mixed $var A printable message or variable
         * @param string $dieMessage
         * @param int $traceLevel If called from iris_debug, trace level is 1 instead of 0
         */
        public static function DumpAndDie($var, $dieMessage=NULL, $traceLevel=0) {
            if (is_null($dieMessage)) {
                $trace = debug_backtrace();
                $dieMessage = sprintf('Debugging interrupt in file <b> %s </b> line %s', $trace[$traceLevel]['file'], $trace[$traceLevel]['line']);
            }
            self::Dump($var);
            self::Kill($dieMessage);
        }

        //@todo move to another class
        public static function Kill($message=NULL) {
            die($message); // the only authorized die in programm
        }

    }

    /* =========================================================================
     * c l a s s      P A T H A R R A Y
     * =========================================================================
     */

    /**
     * The path is an Object array with special methods
     * 
     * @author Jacques THOORENS (irisphp@thoorens.net)
     * @see http://irisphp.org
     * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $     * 
     */
    class PathArray extends \ArrayObject {

        public function prepend($element) {
            $old = $this->getArrayCopy();
            array_unshift($old, $element);
            $this->exchangeArray($old);
        }

        public function pop() {
            $old = $this->getArrayCopy();
            $last = array_pop($old);
            $this->exchangeArray($old);
            return $last;
        }

        public function insertSecondlast($element) {
            $last = $this->pop();
            $last0 = $this->pop();
            $this->append($element);
            $this->append($last0);
            $this->append($last);
        }

    }

    /* =========================================================================
     * c l a s s      M O D E
     * =========================================================================
     */

    /**
     * The mode class permits to know which type of site is running. 
     * It plays an important role in security by prohibiting some
     * methode activation and data display.
     */
    final class Mode {

        /**
         * This critical variable defines the way the site react in
         * case of error and manage database
         * (many values, but two main values: PRODUCTION and DEVELOPMENT)
         * 
         * @var string 
         */
        protected static $_SiteMode = NULL;

        /**
         *
         * @var string 
         */
        protected static $_ApplicationMode = NULL;

        /**
         * Check if the server is not in production. To be sure of the actual
         * mode, use getSiteMode
         * 
         * @param boolean $site : true if site mode/ false if application mode
         * @return boolean : TRUE if not in production or reception, FALSE otherwise
         * @deprecated (use Mode::IsDevelopment() instead)
         */
        public static function IsDevelopment($site=TRUE) {
            return!self::IsProduction($site);
        }

        /**
         * Determine if the server is in production mode
         * 
         * @param boolean $site true if site mode/ false if application mode
         * @return boolean : TRUE if in production or reception, FALSE otherwise
         * @todo use $site parameter or delete it
         */
        public static function IsProduction($site=TRUE) {
            $mode = self::GetSiteMode();
            if ($mode == 'production' or $mode == 'reception') {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }

        /**
         * Determine the "site mode" (for error treatment and parameters)
         * @return String : the mode name 
         */
        public static function GetSiteMode() {
            if (is_null(self::$_SiteMode)) {
                self::AutosetSiteMode();
            }
            return self::$_SiteMode;
        }

        /**
         *
         * @return string : the mode name
         */
        public static function GetApplicationMode() {
            if (is_null(self::$_ApplicationMode)) {
                self::AutosetSiteMode();
            }
            return self::$_ApplicationMode;
        }

        /**
         * Initialiase the mode of the server, using EXEC_MODE et APPLICATION_ENV
         * 
         */
        public static function AutosetSiteMode() {
            // EXEC_MODE may be defined as an environment variable or 
            // as a parametre in URL
            $envMode = getenv('EXEC_MODE');
            if (isset($_GET['EXEC_MODE'])) {
                $envMode = $_GET['EXEC_MODE'];
            }
            if (empty($envMode)) {
                $envMode = '';
            }
            // APPLICATION_ENV is defined in the Apache server
            $apacheMode = getenv('APPLICATION_ENV');
            switch ($apacheMode) {
                case 'development':
                    switch ($envMode) {
                        case 'DEV':
                            $mode = 'development';
                            break;
                        case 'PROD':
                            $mode = 'production';
                            break;
                        case 'TEST':
                            $mode = 'testing';
                            break;
                        case 'RECEPT':
                            $mode = 'reception';
                            break;
                        case 'WHAT':
                            print "Exec mode : <br/>";
                            print "DEV - TEST - RECEPT - PROD - WHAT";
                            \Iris\Engine\Debug::Kill('');
                            break;
                        case '':
                            $mode = $apacheMode;
                            break;
                        // User modes
                        default:
                            $mode = $envMode;
                            break;
                    }
                    break;
                case 'reception':
                    if ($envMode == '') {
                        $mode = $apacheMode;
                    }
                    else {
                        $mode = $envMode;
                    }
                    break;
                default:
                    $mode = 'production';
                    break;
            }
            self::$_SiteMode = $mode;
        }

        public static function SetSiteMode($mode) {
            self::$_SiteMode = $mode;
        }

        public static function SetApplicationMode($mode) {
            self::$_ApplicationMode = $mode;
        }

    }

    /* =========================================================================
     * c l a s s      L O A D E R
     * =========================================================================
     */


    // Permits overwriting of Loader
    if (!defined('IRIS_LOADER')) {

        class Loader extends core_Loader {
            
        }

    }

    /**
     * Singleton procuring ways to load all the classes
     * except itself.
     * 
     * 
     * @author Jacques THOORENS (irisphp@thoorens.net)
     * @see http://irisphp.org
     * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $     * 
     */
    abstract class core_Loader { // implements \Iris\Design\iSingleton
        /**
         * type of class to load
         */
        const PLAIN_CLASS = 1;
        const VIEW_HELPER = 2;
        const CONTROLLER_HELPER = 3;
        const VIEW_EXT = '.iview';

        // Directory patterns for views/layout
        const INTERNAL ='%s/IrisInternal/%s/views/%s%s';
        const INTERNALMAIN ='%s/IrisInternal/main/views/%s%s';
        const MODULE = '%s/modules/%s/views/%s%s';
        const DEFMODULE = '%s/modules/main/views/%s%s';
        const LIBRARY = '%s/views/%s%s';

        /**
         * An '_' separated list of standard libraries whose classes
         * may be overridden by the developper
         */
        const STANDARD_LIBRARIES = "Iris_Dojo";


        /**
         * The Loader is a singleton
         * @var Iris\Engine\Loader  
         */
        private static $_Myself = NULL;
        // By defaut no class is traced
        private static $_ClassesToTrace = array();

        /**
         * The folder name for the framework components. 
         * Possible to change, but with caution (and before any use
         * of the autoloader)
         * 
         * @var string
         */
        public $library = 'library';

        /**
         * This array contains all the pathes to user overridden classes.
         * If the path is TRUE, a search will be made in library/extensions
         * in a path computed from the class name
         * 
         * @var array
         */
        public static $UserClasses = array();

        /**
         * Implemenation of 3 stacks to search classes in
         * 
         * @var array(PathArray) 
         */
        private $_classPath = array();

        /**
         * The default stack type itself managed as a stack
         * (see reset, PushStackType, PopStackType, getCurrentStackType methods)
         * 
         * @var array(int)
         */
        private $_stackType;

        /**
         *
         * @var int (array) emulates an enum of the 3 stack types 
         */
        private $_stackList = array(
            self::PLAIN_CLASS,
            self::CONTROLLER_HELPER,
            self::VIEW_HELPER
        );
        private $_altLibrary = NULL;

        /**
         * Private constructor for singleton.
         */
        private function __construct($library) {
            $this->resetStack();
            $this->library = $library;
            $initial = array("$library/Iris/", "$library/");
            foreach ($this->_stackList as $stackType) {
                $this->_classPath[$stackType] = new PathArray($initial);
            }
        }

        /**
         * Initializes _stackType at Loader creation and 
         * retinitializes it after an error
         * (the stack may be left in a dirty state when the error occurs)
         */
        public function resetStack() {
            $this->_stackType = array(self::PLAIN_CLASS);
        }

        /**
         * A special treatment for user overridden classes
         * 
         * @param string $className
         * @return boolean 
         */
        public function overridden($className) {
            $path = str_replace('\\', '/', $className);
            $pathComponents = explode('/', $path);
            if (strpos(self::STANDARD_LIBRARIES, $pathComponents[0]) === FALSE) {
                return \FALSE;
            }
            else {
                if (isset(self::$UserClasses[$className])) {
                    $classPath = $this->classToPath($className);
                    if (!$this->_tryToLoad($classPath, $this->library . 'core')) {
                        throw new \Iris\Exceptions\LoaderException("Core class $className not found");
                    }
                    // load modified class in extension
                    if (self::$UserClasses[$className] === TRUE) {
                        if (!$this->_tryToLoad($classPath, $this->library . 'Extensions')) {
                            throw new \Iris\Exceptions\LoaderException("Core class $className not found");
                        }
                    }
                    // load modified class in explicit PATH
                    else {

                        if (!$this->_tryToLoad($classPath, $this->library . '/' . self::$UserClasses[$className] . "/")) {
                            throw new \Iris\Exceptions\LoaderException("Developper special class $className not found");
                        }
                    }
                    return \TRUE;
                }
                return \FALSE;
            }
        }

        /**
         * get the unique instance of the Loader class. A chance is given
         * to change base library path at first call. If parameter is not null, 
         * meaning an error processing is starting, reinitializes the library pathes.
         * 
         * @param string $errorPath the error path (in case of error restart)
         * @return Iris\Engine\Loader 
         */
        public static function GetInstance($errorPath=\NULL) {
            if (is_null(self::$_Myself)) {
                self::$_Myself = new Loader(IRIS_LIBRARY);
            }
//            if(!is_null($errorPath)){
//                self::$_Myself->reset();
//            }
            return self::$_Myself;
        }

        /**
         * Load the file containing the class, using different path
         * 
         * @param String $className 
         * @return boolean 
         */
        public function doLoad($className, $throwException = TRUE) {
            $this->_loadDebug("Searching class ", $className);
            $classPath = $this->classToPath($className);
            $found = FALSE;
            $stack = $this->getStack($this->getCurrentStackType());
            $i = 1;
            foreach ($stack as $path) {
                $i++;
                if ($this->_tryToLoad($classPath, $path)) {
                    $found = TRUE;
                    break; //quick and dirty
                }
            }
            if (!$found and $throwException) {
                throw new IX\LoaderException("$className : can't find this class");
            }
            //if($found)echo "$className<br>";
            return $found;
        }

        /**
         * This methods tries to load the file containing the class
         * 
         * @param string $classPath qualified class name (with separators)
         * @param string $path path to search in
         * @return boolean TRUE if file loaded
         */
        private function _tryToLoad($classPath, $path) {
            $fileName = IRIS_ROOT_PATH . "/$path$classPath.php";
            $this->_loadDebug("Testing $fileName...");
            if (!file_exists($fileName)) {
                $found = \FALSE;
            }
            else {
                $this->_loadDebug("FOUND $fileName !");
                require_once $fileName;
                $found = \TRUE;
            }
            return $found;
        }

        /**
         * Views have a more complex way to load and deserve a special method
         * to make the searching
         * 
         * @param type $scriptName
         * @param type $controllerDirectory
         * @param type $response
         * @return type 
         */
        public function loadView($scriptName, $controllerDirectory, $response) {
            if (is_null($response)) {
                $response = Response::GetDefaultInstance();
            }
            // add extension if necessary
            if (strpos($scriptName, '.') === FALSE) {
                $scriptName .= self::VIEW_EXT;
            }
            $module = $response->getModuleName();
            if (!preg_match('/[\/_]/', $scriptName)) {
                //if (strpos($scriptName, '/') === FALSE) {
                $scriptName = "_" . $scriptName;
            }
            elseif ($scriptName[0] != '/') {
                $scriptName = '/' . $scriptName;
            }
            $router = Router::GetInstance();
            $program = $router->getProgramName();
            // views beginning with ! are to take from IrisInternal (notably layouts)
            if ($response->isInternal() or $scriptName[1] == '!') {
                if ($scriptName[1] == '!') {
                    $scriptName = "_" . substr($scriptName, 2);
                }
                // search in internal module
                if (!is_null($this->_altLibrary)) {
                    $viewFile[] = sprintf(self::INTERNAL, $this->_altLibrary, $module, $controllerDirectory, $scriptName);
                }
                $viewFile[] = sprintf(self::INTERNAL, $this->library, $module, $controllerDirectory, $scriptName);
                // search in internal main if necessary
                if ($module != 'main') {
                    if (!is_null($this->_altLibrary)) {
                        $viewFile[] = sprintf(self::INTERNALMAIN, $this->_altLibrary, $controllerDirectory, $scriptName);
                    }
                    $viewFile[] = sprintf(self::INTERNALMAIN, $this->library, $controllerDirectory, $scriptName);
                }
            }
            else {
                // search in module
                $viewFile[] = sprintf(self::MODULE, $program, $module, $controllerDirectory, $scriptName);
                // next in main module if necessary
                if ($module != 'main') {
                    $viewFile[] = sprintf(self::DEFMODULE, $program, $controllerDirectory, $scriptName);
                }
            }
            // next in library
            if (!is_null($this->_altLibrary)) {
                $viewFile[] = sprintf(self::LIBRARY, $this->_altLibrary, $controllerDirectory, $scriptName);
            }
            $viewFile[] = sprintf(self::LIBRARY, $this->library . '/Iris/modules/main', $controllerDirectory, $scriptName);
            $found = FALSE;
            $index = 0;
            $this->_loadDebug("Searching view ", $scriptName, \Iris\Engine\Debug::VIEW);
            while ($index < count($viewFile)) {
                $pathToViewFile = IRIS_ROOT_PATH . '/' . $viewFile[$index];
                $this->_loadDebug("Testing $pathToViewFile", "", \Iris\Engine\Debug::VIEW);
                if (file_exists($pathToViewFile)) {
                    $this->_loadDebug("Found ", $pathToViewFile, \Iris\Engine\Debug::VIEW);
                    $foundClass = $viewFile[$index];
                    $found = TRUE;
                    break;
                }
                $index++;
            }
            //$this->setStackType(self::PLAIN_CLASS);
            if (!$found) {
                $viewFile = basename($scriptName);
                $this->_loadDebug("Unable to find ", "$viewFile", \Iris\Engine\Debug::VIEW);
                throw new IX\LoaderException("$controllerDirectory$viewFile" . " : not existing in context");
            }
            return $foundClass;
        }

        /**
         * Insert program directory in the path list to search class files in
         * 
         * @param string $path 
         */
        public function prependPath($path, $stackType=self::PLAIN_CLASS) {
            $this->getStack($stackType)->prepend($path . "/");
        }

        /**
         * Add a directory in the path to search class files in (in each type of
         * stack)
         * 
         * @param string $path 
         */
        public function insertAltPath($path) {
            $this->_altLibrary = $path;
            foreach ($this->_stackList as $stackType) {
                $this->getStack($stackType)->insertSecondlast("library/$path/");
            }
        }

        /**
         * Get a stack from the specified type
         * 
         * @param type $stack
         * @return PathArray 
         */
        protected function getStack($stack) {
            return $this->_classPath[$stack];
        }

        /**
         * Add a class to the list of class to trace by _loadDebug
         * @param type $classes 
         */
        public static function AddTrace($classes) {
            if (is_array($classes)) {
                self::$_ClassesToTrace = \array_merge(self::$_ClassesToTrace, $classes);
            }
            else {
                self::$_ClassesToTrace[] = $classes;
            }
        }

        /**
         * Provide information for debugging of loader
         * 
         * @staticvar string $memClassName
         * @param string $message the message to display
         * @param string $className the name of the class to follow
         * @param type $mode the mode corresponding to the class (has to marked for debugging)
         */
        private function _loadDebug($message, $className=NULL, $mode=\Iris\Engine\Debug::LOADER) {
            // no debugging in production
            if (!defined('IRIS_DEBUG') or \Iris\Engine\Program::IsProduction()) {
                return;
            }
            // helpers have to be identified specificly
            if ($mode == \Iris\Engine\Debug::LOADER and $this->getCurrentStackType() != self::PLAIN_CLASS) {
                $mode = \Iris\Engine\Debug::HELPER;
            }
            static $memClassName = '';
            if (!is_null($className)) {
                $memClassName = basename(str_replace('\\', '/', $className));
                $message .= $memClassName;
            }
            // Impossible to log Log search
            if ($memClassName != 'Log') {
                if (count(self::$_ClassesToTrace) == 0 or
                        array_search($memClassName, self::$_ClassesToTrace) !== FALSE) {
                    \Iris\Log::Debug($message, $mode);
                }
            }
        }

        /**
         * Converts fully qualified classname to path
         * 
         * @param string $className a className to convert to path
         * @return string 
         */
        protected function classToPath($className) {
            $parts = explode('\\', $className);
            if ($parts[0] == 'Iris') {
                array_shift($parts);
            }
            return implode('/', $parts);
        }

        /**
         * Temporarily change stack to search special type of classes
         * 
         * @param int $type 
         */
        public static function PushStackType($type) {
            $instance = self::GetInstance();
            array_unshift($instance->_stackType, $type);
        }

        /**
         * Restore stack type to precedent state
         * 
         * @param int $type 
         */
        public static function PopStackType() {
            $instance = self::GetInstance();
            array_shift($instance->_stackType);
        }

        /**
         * Returns the current stack type for class searching
         * @return int
         */
        public function getCurrentStackType() {
            return $this->_stackType[0];
        }

        /**
         * Displays the content of the search stack (for debug purpose)
         */
        public function showStacks() {
            foreach ($this->_stackList as $type) {
                echo "<h3>$type</h3><pre>";
                foreach ($this->getStack($type) as $key => $path) {
                    echo $key . " - " . $path . "\n";
                }
                echo "</pre>";
            }
        }

    }

}




