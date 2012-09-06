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

/**
 * This class is used internally by all Dojo helpers to manage the
 * components to load.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Manager implements \Iris\Design\iSingleton {
    const GOOGLE="https://ajax.googleapis.com/ajax/libs/dojo/";
    const AOL ="http://o.aolcdn.com/dojo/";
    const YANDEX = "http://yandex.st";


    /**
     * Part of the singleton mechanism
     * @var Manager
     */
    protected static $_Instance = NULL;

    /**
     * The JS prerequisites for the dojo witgets used in the program
     * 
     * @var array 
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
     * The dojo style used in the program among standard 4
     * 
     * @var string
     */
    protected $_style = 'nihilo'; // soria/tundra/claro/nihilo

    /**
     * the source directory/url where dojo can be find
     * 
     * @var string 
     */
    protected $_source = '/js';

    /**
     *
     * @var boolean
     */
    private $_local = TRUE;

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
     * Version number correspond to the version used to write Iris-PHP
     * 
     * @var string 
     */
    protected $_version = '1.7.0';

    /**
     * Obtaining the unique instance
     * 
     * @return Manager
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = new self();
        }
        return self::$_Instance;
    }

    /**
     * The constructor is private (singleton). It inits the basic CSS
     * and debug mode (according to server type)
     */
    private function __construct() {
        $this->addStyle("/!documents/file/resource/css/dojo.css");
        if (\Iris\Engine\Program::IsDevelopment()) {
            $this->setDebug('true');
        }
        else {
            $this->setDebug('false');
        }
        if (!\Iris\Users\Session::JavascriptEnabled()) {
            $this->addStyle('/!documents/file/resources/css/iris_nojs.css');
        }
        // init dojo_head to register it in autoResource
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
    public function setActive($value=TRUE) {
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
     * @param type $source 
     */
    public function setSource($source) {
        $this->setActive();
        $this->_source = $source;
    }

    /**
     *
     * @return type 
     */
    public function getScript() {
        $source = $this->getSource();
        if ($this->_local) {
            return "$source/dojo/dojo.js";
        }
        else {
            return "$source/dojo/dojo.xd.js";
        }
    }

    /**
     *
     * @return type 
     */
    public function getSource() {
        $version = $this->getVersion();
        $this->_local = FALSE;
        switch ($this->_source) {
            case 'Google':
                $source = self::GOOGLE . $version;
                break;
            case 'AOL':
                $source = self::AOL . $version;
                break;
            case 'Yandex':
                $source = self::YANDEX . $version;
                break;
            default:
                $source = $this->_source;
                $this->_local = TRUE;
                break;
        }
        return $source;
    }

    /**
     * Add a requisite for Dojo
     * 
     * @param type $index
     * @param type $name
     * @return Manager (for fluent interface)
     */
    public function addRequisite($index, $name=NULL) {
        if (is_null($name)) {
            $name = $index;
        }
        if (!isset($this->_requisites[$index])) {
            $this->_requisites[$index] = $name;
        }
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

?>
