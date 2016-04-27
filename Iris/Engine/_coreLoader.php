<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

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
abstract class _coreLoader implements \Iris\Design\iSingleton {

    use tSingleton;

    /**
     * type of class to load
     */
    const PLAIN_CLASS = 0;
    const VIEW_HELPER = 1;
    const CONTROLLER_HELPER = 2;
// Directory patterns for views/layout
    const INTERNAL = '%s/IrisInternal/%s/views/%s%s';
    const INTERNALMAIN = '%s/IrisInternal/main/views/%s%s';
    const MODULE = '%s/modules/%s/views/%s%s';
    const MAIN = '%s/modules/main/views/%s%s';
    const LIBRARY = '%s/views/%s%s';

    /**
     * An '_' separated list of standard libraries whose classes
     * may be overridden by the developper
     */
    const STANDARD_LIBRARIES = "Iris_Dojo";

// By defaut no class is traced
    protected static $_ClassesToTrace = [];

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
     * in a path computed from the class name, otherwise, the literal value
     * will be used as a path
     * 
     * @var string[]
     */
    public static $UserClasses = [];

    /**
     * Implemenation of 3 stacks to search classes into
     * 
     * @var PathArray[] 
     */
    protected $_classPath = [];

    /**
     * The stack type used during a search
     * 
     * @var int[]
     */
    protected $_stackType;

    /**
     * The name of a subdirectory shared by two or more application
     * 
     * @var string
     */
    protected $_transapplicationName = '';

    /**
     * Loaded class names and types are recorded to speed the framework
     * 
     * @var type 
     */
    protected $_loadedClasses = [
        self::VIEW_HELPER => [],
        self::CONTROLLER_HELPER => [],
    ];

    /**
     *
     * @var int[] emulates an enum of the 3 stack types 
     */
    protected $_stackList = [
        self::PLAIN_CLASS,
        self::VIEW_HELPER,
        self::CONTROLLER_HELPER,
    ];

    /**
     * Special libraries added to the search path
     * 
     * @var string[]
     */
    protected $_extensionLibraries = [];

    /**
     * Private constructor for singleton.
     */
    protected function __construct() {
        $this->_classPath = [];
        $this->library = IRIS_LIBRARY;
// First look in Iris, then in another specified library
// application path will be prepend by a Program method 
        $initial = ["$this->library/Iris/", "$this->library/"];
        foreach ($this->_stackList as $stackType) {
            $this->_classPath[$stackType] = new PathArray($initial);
        }
    }

    /**
     * Load a class whose complete name is given. This method serves
     * only for standard classes, including controllers.
     * The class may be found <ul>
     * <li>in a standard class file overriden by the developper
     * <li>in application path (example model classes)
     * <li>in library/Iris (any class from Iris kernel)
     * <li>in library (for example, system extension like Dojo classes)
     * </ul>
     * 
     * @param string type $className the full name of the class
     * @param boolean $throwException if TRUE and doesn't find, throws an exception
     * @return boolean
     * @throws Iris\Exceptions\LoaderException
     */
    public function loadClass($className, $throwException = \TRUE) {
        //echo "$className<br>";
        if ($this->_findOverriddenClass($className)) {
            $found = \TRUE;
        }
        else {
            $this->_stackType = self::PLAIN_CLASS;
            $this->_loadDebug("Searching class ", $className);
            $classPath = $this->classToPath($className);
            $found = FALSE;
            foreach ($this->_classPath[self::PLAIN_CLASS] as $basePath) {
                if ($this->_tryToLoad($basePath . $classPath)) {
                    // if necessary call the class initializer
                    if (method_exists($className, '__ClassInit')) {
                        call_user_func([$className, '__ClassInit']);
                    }
                    $found = TRUE;
                    break; //quick and dirty
                }
            }
            if (!$found and $throwException) {
                throw new \Iris\Exceptions\LoaderException("Can't find standard class $className");
            }
        }
        return $found;
    }

    /**
     * Tries to load the helper file corresponding to a class name and a type
     * 
     * @param string $className
     * @param int $helperType
     * @return boolean TRUE if the class has been loaded
     * @throws Iris\Exceptions\LoaderException
     */
    public function loadHelper($className, $helperType) {
// helpers are loaded only once
        if (isset($this->_loadedClasses[$helperType][$className])) {
            return \TRUE;
        }
        $this->_loadDebug("Searching class ", $className, \Iris\Engine\Debug::HELPER);
// if necessary tries to load an overridden class
        if ($this->_findOverriddenClass($className)) {
            return \TRUE;
        }
        $this->_stackType = $helperType;
        $classPath = $this->classToPath($className);
        $found = FALSE;
        foreach ($this->_classPath[$helperType] as $basePath) {
            if ($this->_tryToLoad($basePath . $classPath)) {
                $found = TRUE;
                break; //quick and dirty
            }
        }
        if (!$found) {
            throw new Iris\Exceptions\LoaderException("$className : can't find this class");
        }
        $this->_loadedClasses[$helperType][$className] = \TRUE;
        return $found;
    }

    /**
     * Views have a more complex way to load and deserve a special method
     * to make the searching
     * 
     * @param type $scriptName The specified script name (or empty string)
     * @param type $controllerDirectory The controller directory
     * @param Response $response The used response (main or secondary for subcontrollers)
     * @return type 
     */
    public function loadView($scriptName, $controllerDirectory, $response) {
        $module = $response->getModuleName();
        // if scriptName does not begin with / or _, prepend an _
        if (!preg_match('/[\/_]/', $scriptName)) {
            $scriptName = "_" . $scriptName;
        }
        elseif ($scriptName[0] !== '/') {
            $scriptName = '/' . $scriptName;
        }
        $program = \Iris\Engine\Program::$ProgramName;
        $libraryList = $this->_extensionLibraries; // may be 0 element
        $libraryList[] = $this->library;
        // partials or views may be in system libraries
        if (strpos($scriptName, '#')) {
            $viewFiles[] = $this->_fromSystem($scriptName, $controllerDirectory);
        }
        // views beginning with ! are to take from IrisInternal (notably layouts)
        if ($response->isInternal() or $scriptName[1] === '!') {
            if ($scriptName[1] == '!') {
                $scriptName = "_" . substr($scriptName, 2);
            }
            // search in internal module
            foreach ($libraryList as $library) {
                $viewFiles[] = sprintf(self::INTERNAL, $library, $module, $controllerDirectory, $scriptName);
            }
            // search in internal main if necessary
            if ($module != 'main') {
                foreach ($libraryList as $library) {
                    $viewFiles[] = sprintf(self::INTERNALMAIN, $library, $controllerDirectory, $scriptName);
                }
            }
        }
        else {
            // search in module
            $viewFiles[] = sprintf(self::MODULE, $program, $module, $controllerDirectory, $scriptName);
            if ($this->_transapplicationName !== '') {
                $viewFiles[] = sprintf(self::MODULE, $this->_transapplicationName, $module, $controllerDirectory, $scriptName);
            }
            // next in main module if necessary
            if ($module != 'main') {
                $viewFiles[] = sprintf(self::MAIN, $program, $controllerDirectory, $scriptName);
                if ($this->_transapplicationName !== '') {
                    $viewFiles[] = sprintf(self::MAIN, $this->_transapplicationName, $controllerDirectory, $scriptName);
                }
            }
        }
        // next in library
        foreach ($libraryList as $library) {
            $viewFiles[] = sprintf(self::LIBRARY, $library . '/modules/main', $controllerDirectory, $scriptName);
        }
        $viewFiles[] = sprintf(self::LIBRARY, $this->library . '/Iris/modules/main', $controllerDirectory, $scriptName);
        $found = FALSE;
        $index = 0;
        $this->_loadDebug("Searching view ", $scriptName, \Iris\Engine\Debug::VIEW);
        while ($index < count($viewFiles)) {
            $file = $viewFiles[$index];
            $extension = (strpos(basename($file), '.') === FALSE) ? '.iview' : '';
            $pathToViewFile = IRIS_ROOT_PATH . '/' . $file . $extension;
            $this->_loadDebug("Testing $pathToViewFile", "", \Iris\Engine\Debug::VIEW);
            if (file_exists($pathToViewFile)) {
                $this->_loadDebug("Found ", $pathToViewFile, \Iris\Engine\Debug::VIEW);
                $foundClassFile = $file;
                $found = TRUE;
                if (basename($controllerDirectory) === 'lo') {
                    \Iris\Engine\Memory::Set('LAYOUT', '/' . $file . $extension);
                }
                break;
            }
            $index++;
        }
        if (!$found) {
            $viewFile = basename($scriptName);
            $this->_loadDebug("Unable to find ", "$viewFile", \Iris\Engine\Debug::VIEW);
            $context = implode(' - ', $viewFiles);
            throw new Iris\Exceptions\LoaderException("$controllerDirectory$viewFile.iview" . " : not existing in $context");
        }
        return $foundClassFile;
    }

    /**
     * Tries to add a script from a system library
     * 
     * @param type $scriptName
     * @param type $controllerDirectory
     * @return type
     */
    protected function _fromSystem($scriptName, $controllerDirectory) {
        list($library, $scriptName) = explode('#', $scriptName);
        $library = $this->library . '/' . substr($library, 1);
        return sprintf(self::LIBRARY, $library, $controllerDirectory, $scriptName);
    }

    /**
     * A special treatment for user overridden classes
     * 
     * @param string $className
     * @return boolean 
     */
    protected function _findOverriddenClass($className) {
        $path = str_replace('\\', '/', $className);
        $pathComponents = explode('/', $path);
        // Overriden classes may only occur in standard libraries
        if ($pathComponents[0] === '' or strpos(self::STANDARD_LIBRARIES, $pathComponents[0]) === \FALSE) {
            return \FALSE;
        }
        else {
            if (isset(self::$UserClasses[$className])) {
                $alternativePath = self::$UserClasses[$className];
                $classPath = $this->classToPath($className, \FALSE);
                $corePath = $this->library . '/Core/' . $classPath;
                if (!is_array($alternativePath)) {
                    $corePath = $this->library . '/Core/' . $classPath;
                    $extensionPath = $this->library . '/Extensions/' . $classPath;
                }
                else {
                    $extensionPath = $alternativePath[0] . "/" . basename($classPath);
                    if (count($alternativePath) > 1) {
                        $corePath = $alternativePath[1] . "/" . basename($classPath);
                    }
                }
                if (!$this->_tryToLoad($corePath)) {
                    throw new \Iris\Exceptions\LoaderException("Core class $className not found");
                }
                if (!$this->_tryToLoad($extensionPath)) {
                    throw new \Iris\Exceptions\LoaderException("Extensions class $className not found");
                }

                return \TRUE;
            }
            return \FALSE;
        }
    }

    /**
     * This methods tries to load the file containing the class starting
     * from the root path
     * 
     * @param string $classPath qualified class name (with separators)
     * @return boolean TRUE if file loaded
     */
    protected function _tryToLoad($classPath, $debugMode = \Iris\Engine\Debug::LOADER) {
        $fileName = IRIS_ROOT_PATH . "/$classPath.php";
        $this->_loadDebug("Testing $fileName...", \NULL, $debugMode);
        if (!file_exists($fileName)) {
            $found = \FALSE;
        }
        else {
            $this->_loadDebug("<i>FOUND $fileName !</i>", \NULL, $debugMode);
            require_once $fileName;
            $found = \TRUE;
        }
        return $found;
    }

    /**
     * Insert a new library directory in the path list to search class files in
     * 
     * @param string $path 
     */
    public function addLibrary($path, $stackType = self::PLAIN_CLASS) {
        $this->getStack($stackType)->prepend($path . "/");
    }

    /**
     * Creates the path to search the helpers according to <ul>
     * <li>the program Name
     * <li>the optional transapplication name
     * <li>the module in use </ul>
     * 
     * @param string[] $modules
     */
    public function pathToHelpers($modules) {
        if (!is_array($modules)) {
            $modules = [$modules];
        }
// reverse the array because its elements will be prepended
        $modules = array_reverse($modules);
        if (strpos($modules[0], 'library/IrisInternal') === 0) {
            $programName = $transapplicationName = '';
        }
        else {
            $programName = \Iris\Engine\Program::$ProgramName;
            $transapplicationName = $this->_transapplicationName;
        }
        foreach ($modules as $module)
            foreach ([self::CONTROLLER_HELPER, self::VIEW_HELPER] as $helperType) {
                $transapplicationName === '' OR
                        $this->getStack($helperType)->prepend($transapplicationName . $module . "/");
                $this->getStack($helperType)->prepend($programName . $module . "/");
            }
    }

    /**
     * Sets the application name (corresponding to the directory containing all 
     * the application class)
     * 
     * @param string $application
     */
    public function setApplicationName($application) {
        $this->getStack(self::PLAIN_CLASS)->prepend($application . "/");
    }

    /**
     * Sets a transapplication directory name which is intended to contain
     * classes and views shared by various applications
     * 
     * @param string $name
     */
    public function setTransapplicationName($name) {
        $this->_classPath[self::PLAIN_CLASS]->prepend("$name/");
        $this->_transapplicationName = $name;
    }

    /**
     * Add a directory in the path to search class files in (in each type of
     * stack)
     * 
     * @param string $path 
     */
    public function insertAltPath($path) {
        $this->_extensionLibraries[] = $path;
        foreach ($this->_stackList as $stackType) {
            $this->getStack($stackType)->insertSecondLast("$path/");
        }
    }

    /**
     * Get a stack from the specified type
     * 
     * @param type $stack
     * @return PathArray 
     */
    public function getStack($stack) {
        return $this->_classPath[$stack];
    }

    /**
     * Add a class to the list of class to trace by _loadDebug
     * 
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
     * Provide information for the debugging of Loader
     * 
     * @staticvar string $memClassName
     * @param string $message the message to display
     * @param string $className the name of the class to follow
     * @param type $mode the mode corresponding to the class (has to marked for debugging)
     */
    protected function _loadDebug($message, $className = NULL, $mode = \Iris\Engine\Debug::LOADER) {
// no debugging in production
        if (!defined('IRIS_DEBUG') or \Iris\Engine\Mode::IsProduction()) {
            return;
        }
// helpers have to be identified specificly
        if ($this->_stackType != self::PLAIN_CLASS) {
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
                \Iris\Engine\Log::Debug($message, $mode);
            }
        }
    }

    /**
     * Converts fully qualified classname to path. Initial Iris is stripped out 
     * by default
     * 
     * @param string $className a className to convert to path
     * @param boolean $eraseIris if true erase the first component if it is Iris
     * @return string 
     */
    protected function classToPath($className, $eraseIris = \TRUE) {
        $parts = explode('\\', $className);
        if ($parts[0] == 'Iris' and $eraseIris) {
            array_shift($parts);
        }
        return implode('/', $parts);
    }

}
