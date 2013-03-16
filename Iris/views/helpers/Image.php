<?php

namespace Iris\views\helpers;

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
 * A helper for image display
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Image extends _ViewHelper {

    protected $_folder = \NULL;
    protected $_defaultClass = '';
    protected static $_Singleton = \TRUE;

    /**
     * Creates an image tag with alt and title
     * 
     * @param string $file Image file
     * @param string $alt Alt attribute (to display if image is missing)
     * @param string $title Title attribute (tooltip)
     * @param string $folder image directory (by default images)
     * @param string $class class name for CSS
     * @param string $attributes optional attributes or javascript
     * @return string 
     */
    public function help($file = \NULL, $alt = 'Image', $title = NULL, $folder = \NULL, $class = '', $attributes = '') {
        if (is_null($file)) {
            return $this;
        }
        else {
            return $this->_renderImage($file, $alt, $title, $folder, $class, $attributes);
        }
    }

    /**
     * 
     * @param string $folderName
     * @return \Iris\views\helpers\Image for fluent interface
     */
    public function setFolder($folderName) {
        if($folderName == ''){
            $folderName = \NULL;
        }
        $this->_folder = $folderName;
        return $this;
    }

    /**
     * 
     * @param string $folderName
     * @param string $fileName
     * @return string
     */
    protected function _getFullFileName($folderName, $fileName) {
        if ((is_null($folderName) or $folderName == '')
                and($fileName[1] != '!')) {
            if(!is_null($this->_folder)){
                $folderName = $this->_folder;
            }
            else{
                $folderName =  "/images";
            }
        }
        if (substr($folderName, -1) == '/' or is_null($folderName))
            $fullName =$folderName . $fileName;
        else
            $fullName = "$folderName/$fileName";
        return $fullName;
    }

    /**
     * 
     * @param type $defaultClass
     * @return \Iris\views\helpers\Image for fluent interface
     */
    public function setDefaultClass($defaultClass) {
        $this->_defaultClass = $defaultClass;
        return $this;
    }

    protected function _renderImage($fileName, $alt, $title, $folderName, $class, $attributes) {
        // providing a default alt attribute
        if (is_null($alt)) {
            $alt = "Image $fileName";
        }
        // providing a default title attribute
        if (is_null($title)) {
            $title = $alt;
        }
        $fullName = $this->_getFullFileName($folderName, $fileName);
        // mention class if it has one
        if ($class != '') {
            $class = 'class="' . $class . '"';
        }
        return sprintf('<img src="%s" title="%s" alt="%s" %s %s/>' . "\n", $fullName, $title, $alt, $class, $attributes);
    }

}

