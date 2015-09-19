<?php

namespace Dojo;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

define('URL_DOJO_GOOGLE', "https://ajax.googleapis.com/ajax/libs/dojo/");
define('URL_DOJO_YANDEX', "http://yandex.st/dojo");
// AOL disappeared
//define('URL_DOJO_AOL', "http://o.aolcdn.com/dojo/");

/**
 * This class is used internally by all Dojo helpers to manage the
 * components to load.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Manager implements \Iris\Design\iSingleton {

    use \Iris\Engine\tSingleton;

    const LOCAL = 0;
    const GOOGLE = 1;
    //const AOL = 2; disappeared
    const YANDEX = 3;

    /**
     * Part of the singleton mechanism
     * @var Manager
     */
    protected static $_Instance = NULL;

    /**
     * The JS init code for the dojo witgets used in the program
     * @var string[]
     */
    protected $_initCode = array();

    /**
     * The style file used by Dojo
     *
     * @var string[]
     */
    protected $_styleFiles = array();

    /**
     * when _dojoActive is false, no dojo tags nor attributes
     * are displayed in view or template
     *
     * @var boolean
     */
    protected static $_DojoActive = FALSE;

    public static function __ClassInit() {
        Engine\Head::GetInstance();
    }

    /**
     * The constructor is private (singleton). It inits the basic CSS
     * and debug mode (according to server type)
     */
    private function __construct() {
        self::SetActive();
        $this->addStyle("/!documents/file/css/dojo.css");
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            $this->addStyle('/!documents/files/css/iris_nojs.css');
        }
        // init dojo_head to register it in Iris\Subhelpers\Head
        //Engine\Head::GetInstance();
    }

    /**
     * When set to true, all Dojo stuffs are loaded
     */
    public static function SetActive() {
        self::$_DojoActive = \TRUE;
        Engine\Head::GetInstance();
    }

    /**
     * Tests the necessity to load Dojo
     * @return type
     */
    public static function IsActive() {
        return self::$_DojoActive;
    }

    /**
     * Returns the source URL for the main script of dojo,
     * whose name is not the same in local or remote URL.
     *
     * @return string
     */
    public function getScript() {
        $source = $this->getURL();
        if ($source == self::LOCAL) {
            return "$source/dojo/dojo.js";
        }
        else {
            return "$source/dojo/dojo.xd.js";
        }
    }

    /**
     * Creates the URL from where to load the first file of the
     * Dojo framework
     *
     * @return string
     */
    public function getURL() {
        $version = \Dojo\Engine\Settings::$Version;
        switch (\Dojo\Engine\Settings::$Source) {
            case self::GOOGLE:
                $source = URL_DOJO_GOOGLE . $version;
                break;
//            AOL disappeared
//            case self::AOL:
//                $source = URL_DOJO_AOL . $version;
//                break;
            case self::YANDEX:
                $source = URL_DOJO_YANDEX . $version;
                break;
            default:
                $source = '/js';
                break;
        }
        return $source;
    }

    

    /**
     * Add a style for Dojo
     *
     * @param string $file
     * @return Manager (for fluent interface)
     */
    public function addStyle($file) {
        $this->_styleFiles[$file] = 0;
        return $this;
    }

    /**
     * Add an init code for Dojo
     * @param string $index unique label to avoid duplicates
     * @param string $content
     * @return Manager (for fluent interface)
     */
    public function addInitCode($index, $content) {
        if (!isset($this->_initCode[$index])) {
            $this->_initCode[$index] = $content;
        }
        return $this;
    }

    /**
     * Accessor get for initcode
     * *
     * @return array
     */
    public function getInitCode() {
        return $this->_initCode;
    }

    /**
     * Accessor get for style files
     *
     * @return array
     */
    public function getStyleFiles() {
        return $this->_styleFiles;
    }

}
