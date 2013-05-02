<?php

namespace Iris\Subhelpers;
use Iris\System\tRepository;

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
 * An abstract Slideshow manager which uses a concrete class implementing
 * the internal engine (by default Dojo). It may be used as a singleton,
 * but if necessary, various instances can be created. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _SlideShowManager extends _Subhelper {

    use tRepository;
    
    protected static $_DefaultLibrary = '\\Dojo\\';
    
    protected $_id = "SlideShow1";

    /**
     *
     * @var type 
     */
    protected $_altImage = array();
    
    protected function __construct($_id) {
        $this->_id = $_id;
    }

    
    /**
     * The default provider (Dojo) may be changed.
     * 
     * @param string $library
     */
    public static function SetDefaultLibrary($library) {
        self::$_DefaultLibrary = $library;
    }

    /**
     * The constructor request uses the default library
     * 
     * @param type $objectName The name of the object to create
     * @return \Iris\Subhelpers\className
     */
    protected static function _New($objectName) {
        $className = self::$_DefaultLibrary . 'Subhelpers\\SlideShowManager';
        return new $className($objectName);
    }
    
    
    /**
     * Gets or creates the instance by its name.
     * 
     * @param type $objectName The name of the object to create/retrieve
     * @return static
     */
    public static function GetSlideShow($objectName){
        return self::_GetObject($objectName);
    }
    
    /**
     * 
     * @param type $start
     * @return type
     */
    public abstract function render($start = \TRUE);

    /**
     * Sets the interval between two images
     * 
     * @param int $interval
     * @return _SlideShowManager for fluent interface
     */
    public abstract function setInterval($interval);

    /**
     * Sets the width of the slide show display zone
     * 
     * @param int $width
     * @return _SlideShowManager for fluent interface
     */
    public abstract function setWidth($width);

    /**
     * Sets the height of the slide show display zone
     * 
     * @param int $height
     * @return _SlideShowManager for fluent interface
     */
    public abstract function setHeight($height);

    /**
     * Sets the file containing the JSON data for the images
     * 
     * @param string $file
     * @return _SlideShowManager for fluent interface
     */
    public abstract function setFile($file);

    /**
     * 
     * @param type $imageCount
     * @return _SlideShowManager for fluent interface
     */
    public abstract function setImageCount($imageCount);

   /**
     * Sets an alternative image to display in case javascript is disabled 
     * 
     * @param string $imageName
     * @param string $alt
     * @param string $title
     * @param string $imageDir
     * @param string $class
     * @return \Dojo\views\helpers\SlideShow for fluent interface
     */
    public function setAltImage($imageName, $alt = NULL, $title = NULL, $imageDir = '', $class = '') {
        $this->_altImage = array($imageName, $alt, $title, $imageDir, $class);
        return $this;
    }
    /**
     * Sets the autoload feature (preloads images).
     * True by default.
     * 
     * @param boolean $value
     * @return SlideShowManage for fluent interface
     */
    public abstract function setAutoload($value = \TRUE);
    
    /**
     * Sets the autostart feature (preloads images).
     * False by default.
     * 
     * @param boolean $value
     * @return SlideShowManager for fluent interface
     */
    public abstract function setAutoStart($value = \TRUE);
    
    /**
     * Sets a specific id (by default 'slideshow')
     * @param type $id
     * @return \Iris\Subhelpers\_SlideShowManager
     */
    public function set_id($id) {
        $this->_id = $id;
        return $this;
    }

    
}

