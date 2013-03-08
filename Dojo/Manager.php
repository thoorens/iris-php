<?php

namespace Dojo;

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
define('URL_DOJO_GOOGLE', "https://ajax.googleapis.com/ajax/libs/dojo/");
define('URL_DOJO_AOL', "http://o.aolcdn.com/dojo/");
define('URL_DOJO_YANDEX', "http://yandex.st/dojo");

/**
 * This class is used internally by all Dojo helpers to manage the
 * components to load.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Manager {

    use \Iris\Engine\tSingleton;

    const LOCAL = 0;
    const GOOGLE = 1;
    const AOL = 2;
    const YANDEX = 3;

    /**
     * Part of the singleton mechanism
     * @var Manager
     */
    protected static $_Instance = NULL;

    /**
     * The JS prerequisites for the dojo witgets used in the program
     * 
     * @var array 
     * @deprecated
     */
    protected $_requisites = array();

    /**
     * The JS init code for the dojo witgets used in the program
     * @var array 
     */
    protected $_initCode = array();

    /**
     * The style file used by Dojo
     * 
     * @var array 
     */
    protected $_styleFiles = array();

    /**
     * when _dojoActive is false, no dojo tags nor attributes
     * are displayed in view or template
     * 
     * @var boolean  
     */
    protected $_dojoActive = FALSE;

    /**
     * The dojo style used in the program among the 4 standard styles <ul>
     * <li>nihilo
     * <li>soria
     * <li>tundra
     * <li>claro
     * </ul>
     * @var string
     */
    protected $_style = 'nihilo'; // soria/tundra/claro/nihilo

    /**
     * the source directory/url where dojo can be find
     * 
     * @var string 
     */
    protected $_source = self::GOOGLE;

    

    /**
     * By default Dojo is parsed on page load
     * 
     * @var string 
     */
    protected $_parseOnLoad = 'true';

    /**
     * Debug info is set according to site type (in construct)
     * 
     * @var string 
     */
    protected $_debug; //true in development / false in production

    /**
     * Version number correspond to the version used to write Iris-PHP.
     * This number is only used in remote URL. With local source, only
     * one version is supposed to installed in public/js folder.
     * 
     * @var string 
     */
    protected $_version = '1.8.0';

    /**
     * The constructor is private (singleton). It inits the basic CSS
     * and debug mode (according to server type)
     */
    private function __construct() {
        $this->addStyle("/!documents/file/resource/css/dojo.css");
        if (\Iris\Engine\Mode::IsDevelopment()) {
            $this->setDebug('true');
        }
        else {
            $this->setDebug('false');
        }
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            $this->addStyle('/!documents/file/resources/css/iris_nojs.css');
        }
        // init dojo_head to register it in Iris\Subhelpers\Head
        views\helpers\Head::GetInstance();
    }

    /**
     * Accessor for $_version
     * 
     * @return string
     */
    public function getVersion() {
        return $this->_version;
    }

    /**
     * Accessor for $_version
     * 
     * @param string $version 
     */
    public function setVersion($version) {
        $this->_version = $version;
    }

    /**
     * Accessor for $_debug
     * 
     * @return boolean
     */
    public function getDebug() {
        $this->setActive();
        return $this->_debug;
    }

    /**
     * Accessor for $_debub
     * 
     * @param string $debug 
     */
    public function setDebug($debug) {
        $this->setActive();
        $this->_debug = $debug;
    }

    /**
     * Accessor for $_parseOnLoad
     * 
     * @param string $parseOnLoad 
     */
    public function setParseOnLoad($parseOnLoad) {
        $this->setActive();
        $this->_parseOnLoad = $parseOnLoad;
    }

    /**
     * Accessor for $_parseOnLoad
     * 
     * @return string 
     */
    public function getParseOnLoad() {
        $this->setActive();
        return $this->_parseOnLoad;
    }

    /**
     *
     * @param type $value 
     */
    public function setActive($value = TRUE) {
        $this->_dojoActive = $value;
    }

    /**
     *
     * @return type 
     */
    public function isActive() {
        return $this->_dojoActive;
    }

    /**
     *
     * @param type $style 
     */
    public function setStyle($style) {
        $this->setActive();
        $this->_style = $style;
    }

    /**
     *
     * @return type 
     */
    public function getStyle() {
        return $this->_style;
    }

    /**
     *
     * @param int $source 
     */
    public static function SetSource($source) {
        self::GetInstance()->_source = $source;
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
     *
     * @return string 
     */
    public function getURL() {
        $version = $this->getVersion();
        switch ($this->_source) {
            case self::GOOGLE:
                $source = URL_DOJO_GOOGLE . $version;
                break;
            case self::AOL:
                $source = URL_DOJO_AOL . $version;
                break;
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
     * Add a requisite for Dojo (using a Bubble)
     * 
     * @param type $index
     * @param type $name
     * @return Manager (for fluent interface)
     */
    public function addRequisite($index, $name = NULL) {
        if (is_null($name)) {
            $requisite = $index;
        }
        elseif (is_array($name)) {
            $requisite = '"';
            $requisite .= implode('","', $name);
            $requisite .= '"';
            //iris_debug($requisite);
        }
        else {
            $requisite = $name;
        }
//        if (!isset($this->_requisites[$index])) {
//            $this->_requisites[$index] = $requisite;
//        }
        $bubble = \Dojo\Engine\Bubble::getBubble($index);
        $bubble->addModule($name);
        return $this;
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
     * Accessor get for prerequisites
     * 
     * @return array 
     */
    public function getRequisites() {
        return $this->_requisites;
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
