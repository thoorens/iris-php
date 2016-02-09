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

defined('CRLF') or define('CRLF', "\n");
defined('BLANKSTRING') or define('BLANKSTRING','');

/**
 * First class to be called, it loads the Autoloader,
 * reads all the configuration files
 * and permits to create an instance of Program.
 *
 */

abstract class core_Bootstrap {

    /**
     * The name of the standard loader which can be replace by another
     * one written by the developper
     *
     * @var string
     */
    protected $_standardLoaderPath = 'Iris/Engine/LoadLoader.php';

    /**
     * An ordered array of config files to be read automaticaly (instead
     * of the files in /config). It is not deprecated, but /config offers
     * a more convenient way to manage parameter files.
     *
     * @var string[]
     */
    private $_configToRead = NULL;

    /**
     * Implied in the internal overridden class mechanism
     * @var string[]
     *
     * @deprecated will be soon replaced by another mechanism
     */
    public static $AlternativeClasses = array();

    /**
     * Default mode for INI parameter files in /config
     * (Note: loader not already active, so hardcoding 1
     * @var int
     */
    public static $IniMode = 1; //\Iris\SysConfig\_Parser::COPY_INHERITED_VALUES;

    public function __construct() {
        include IRIS_LIBRARY . '/' . $this->_standardLoaderPath;
        $this->init();
        $this->debug();
    }

    /**
     * this method may be overridden in main Bootstrap: for instance
     * to modify $_standardLoaderPath or add custom libraries
     */
    public function init() {
        
    }

    /**
     * This method is meant to be overridden with instructions for debugging purpose.
     * It is sometimes easier to create an application/config/00_debug.php file.
     */
    public function debug() {
        
    }

    /**
     * Create a new application and read config files
     *
     * @param string $programName The program name (by default : 'application'
     * @return Program
     */
    public function newProgram($programName = 'application') {
        // if necessary, load the overridden class names
        $overriddenClasses = "$programName/config/overridden.classes";
        if (file_exists(IRIS_ROOT_PATH . '/' . $overriddenClasses)) {
            include $overriddenClasses;
        }
        $program = new Program($programName);
        // normal case : no configToRead defined, create it
        if (!is_array($this->_configToRead) or count($this->_configToRead) == 0) {
            $this->_configToRead = array();
            $this->_takeAllConfig($programName);
        }
        // _preConfig normally do nothing and return TRUE
        if ($this->_preConfig($programName)) {
            $this->_readConfig($programName);
        }
        $this->_configureErrors();
        $this->_postConfig($programName);
        \Iris\Users\Session::GetInstance();
        return $program;
    }

    /**
     * May be overriden to make some tasks before reading config
     * and if it returns FALSE, normal _readConfig() is not called
     *
     * @param string $programName The application name
     */
    public function _preConfig($programName) {
        return \TRUE;
    }

    /**
     * May be overriden to make some adjustments after reading config
     *
     * @param string $programName The application name
     */
    public function _postConfig($programName) {
        
    }

    /**
     * Reads some files containing various parameters
     * Only considers ini and php files
     *
     * @param string[] $filess
     */
    private function _readConfig($programName) {
        sort($this->_configToRead); 
        foreach ($this->_configToRead as $filePath) {
            \Iris\Engine\Log::Debug("<b>Reading setting file: $filePath</b>", \Iris\Engine\Debug::SETTINGS);
            $ext = pathinfo($filePath, PATHINFO_EXTENSION);
            switch ($ext) {
                case 'php':
                    require $filePath;
                    break;
                case 'ini':
                    $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
                    $params = $parser->processFile($filePath, FALSE, self::$IniMode);
                    $labels = explode('_', basename($filePath, '.ini'));
                    $label = count($labels) > 1 ? $labels[1] : $labels[0];
                    if ($label == 'settings') {
                        \Iris\SysConfig\Settings::FromConfigs($params);
                    }
                    else {
                        Memory::Set('param_' . $label, $params);
                    }
                    break;
                case 'classes':
                    // has been read before
                    break;
            }
        }   
        $this->_configureMenus();
    }

    /**
     * Read all file names in {applicationName}/config for further processing.
     *
     * @param string $applicationName
     */
    private function _takeAllConfig($applicationName) {
        $dir = new \DirectoryIterator(IRIS_ROOT_PATH . '/' . $applicationName . '/config/');
        foreach ($dir as $file) {
            if ($file->isFile()) {
                $this->addConfigFile($file->getPathName());
            }
        }
    }

    /**
     * Add a file to the list of files to process at start time.
     * @param string $fileName
     */
    public function addConfigFile($fileName) {
        $index = basename($fileName);
        $this->_configToRead[$index] = $fileName;
    }

    /**
     *
     */
    protected function _configureErrors() {
        /* @var $errorHandler \Iris\Errors\Handler */
        $errorHandler = \Iris\Errors\Handler::GetInstance();
        $errorHandler->setIniParameters();
        $errorHandler->allException();
    }

    /**
     * Analyses 
     * @param string $mainMenuNames
     */
    protected function _configureMenus($mainMenuNames = \NULL) {
        if (is_null($mainMenuNames)) {
            $mainMenuNames = 'main menu';
        }
        $param_menu = \Iris\Engine\Memory::Get('param_menu', []);
        $menus = \Iris\Structure\MenuCollection::GetInstance();
        foreach ($param_menu as $name => $menu) {
            if (strpos($mainMenuNames, $name) !== \FALSE) {
                $name = '#def#';
            }
            $menus->addMenu(new \Iris\Structure\Menu($name, $menu));
        }
    }

}
