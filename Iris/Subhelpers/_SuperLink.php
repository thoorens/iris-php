<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Subhelpers;

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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * Description of tLink
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _SuperLink implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tTranslatable;

use \Iris\Engine\tSingleton;

    const NOLINK = '!!!!NONE!!!!';

    /**
     * A special array corresponding to a non existent button/link
     *
     * @var string[]
     */
    public static $NoLink = [self::NOLINK, '', ''];

    /**
     * If true, the link is an image
     * 
     * @var boolean
     */
    protected $_image = \FALSE;

    /**
     * The label (or image name)
     * 
     * @var string
     */
    private $_label;

    /**
     * The URL to point to
     * @var string
     */
    private $_url;

    /**
     * The tooltip
     * 
     * @var string
     */
    private $_tooltip;

    /**
     * An optional class name
     * 
     * @var string
     */
    private $_class;

    /**
     * An optional id name
     * 
     * @var string
     */
    private $_id;

    /**
     * There must js attributes
     * @var string[]
     */
    private $_attributes = [];

    /**
     * If true, the link is unnecessary
     * 
     * @var boolean
     */
    protected $_nodisplay;
    
    /**
     * Specifies an optional image folder
     * @var string
     */
    protected $_imageFolder = \NULL;

    /**
     * A temporary image folder used internally
     * @var string
     */
    protected $_internalImageFolder = \NULL;
    
    /**
     * An alt message for image
     * @var string
     */
    protected $_alt;

    /**
     * 
     * @param string $label The label (or image name)
     * @param string $url The URL to point to
     * @param string $tooltip The tooltip
     * @param string $class An optional class name
     * @param string $id An optional id name
     */
    public function autorender($label, $url = \NULL, $tooltip = \NULL, $class = \NULL, $id = \NULL) {
        $this->_initParams(\func_get_args(), \TRUE);
        $this->_nodisplay = $this->_label == self::NOLINK;
        return $this;
    }

    private function _initParams($args, $force) {
        list($label, $url, $tooltip, $class, $id) = $this->_normalize($args);
        $this->setLabel($label, $force);
        $this->setUrl($url, $force);
        $this->setTooltip($tooltip, $force);
        $this->setclass($class, $force);
        $this->setId($id, $force);
    }

    public function image() {
        $this->_initParams(\func_get_args(), \FALSE);
        $this->_image = \TRUE;
        return $this;
    }

    public function acl(){
        $acl = \Iris\Users\Acl::GetInstance();
        $acl->hasPrivilege(dirname($this->_url), basename($this->_url));
        $this->_nodisplay = !$acl->hasPrivilege(dirname($this->_url), basename($this->_url));
        return $this;
    }
    
    /*
     * The set accessor do some logics
     */

    /**
     * The label is mandatory
     * 
     * @return string
     */
    public function getLabel() {
        return $this->_($this->_label);
    }

    /**
     * In case, there is no usable URL, one must take the label
     * 
     * @return string
     */
    public function getUrl() {
        if (empty($this->_url)) {
            $url = $this->_label;
        }
        else {
            $url = $this->_url;
        }
        return $this->_($url);
    }

    public function getTooltip() {
        return $this->_tooltip;
    }

    public function getClass() {
        return $this->_class;
    }

    public function getId() {
        return $this->_id;
    }

    /**
     * Renders all attributes other than href/src
     * 
     * @return string
     */
    protected function _renderAttributes() {
        $attributes = '';
        $tooltip = $this->_tooltip;
        if (!empty($tooltip)) {
            $attributes .= ' title="'.$this->_($tooltip).'"';
        }
        $class = $this->_class;
        if (!empty($class)) {
            $attributes .= " class=\"$class\"";
        }
        $id = $this->_id;
        if (!empty($id)) {
            $attributes .= " id=\"$id\"";
            // id cannot be used twice
            $this->_id = \NULL;
        }
        foreach ($this->_attributes as $name => $value) {
            $attributes .= " $name=\"$value\"";
        }
        return $attributes;
    }

    protected function _renderImage() {
        if ($this->_image) {
            $alt = is_null($this->_alt) ? 'Image for link '.$this->getUrl() : $this->_alt;
            $args = [ $this->getLabel(), $alt = 'Image', $this->getTooltip(), $this->getImageFolder()];
            $this->_label = \Iris\views\helpers\Image::HelperCall('Image', $args);
            $this->_image = \FALSE;
        }
    }

    /**
     * Specifies an alternative folder for images
     *
     * @param string $imageFolder The image folder name
     * @return \Iris\Subhelpers\Link For fluent interface
     */
    public function setImageFolder($imageFolder) {
        $this->_imageFolder = $imageFolder;
        return $this;
    }

    /**
     * Specifies an alternative temporary internal folder for images 
     * It is erased rigth after usage.
     * 
     * @param type $internalImageFolder
     * @return \Iris\Subhelpers\Link For fluent interface
     */
    public function setInternalImageFolder($internalImageFolder) {
        $this->_internalImageFolder = $internalImageFolder;
        return $this;
    }
    
    /**
     * Get an optional image folder (managing a possible temporary one)
     * 
     * @return string 
     */
    public function getImageFolder() {
        if (!is_null($this->_internalImageFolder)) {
            $folder = $this->_internalImageFolder;
            $this->_internalImageFolder = \NULL;
        }
        else {
            $folder = $this->_imageFolder;
        }
        return $folder;
    }
    
    /*
     * Explicit set accessors 
     */

    public function setLabel($label, $force = \FALSE) {
        if (!is_null($label) or $force) {
            $this->_label = $label;
        }
        return $this;
    }

    public function setUrl($url, $force = \FALSE) {
        if (!is_null($url) or $force) {
            $this->_url = $url;
        }
        return $this;
    }

    public function setTooltip($tooltip, $force = \FALSE) {
        if (!is_null($tooltip) or $force) {
            $this->_tooltip = $tooltip;
        }
        return $this;
    }

    public function setClass($class, $force = \FALSE) {
        if (!is_null($class) or $force) {
            $this->_class = $class;
        }
        return $this;
    }

    public function setId($id, $force = \FALSE) {
        if (!is_null($id) or $force) {
            $this->_id = $id;
        }
        return $this;
    }

    public function setAlt($alt) {
        $this->_alt = $alt;
        return $this;
    }

        /**
     * Adds an attribute to the link
     *
     * @param string $name The attribute name
     * @param mixed $value The attribute value
     * @return \Iris\Subhelpers\Link for a fluent interface
     */
    public function addAttribute($name, $value) {
        $this->_attributes[$name] = $value;
        return $this;
    }

    /**
     * Normalizes the arguments : flatten the array and add necessary missing
     * elements (with null value)
     * Based on a idea from: http://stackoverflow.com/questions/1319903/how-to-flatten-a-multidimensional-array
     *  
     * @param type $args
     * @return type
     */
    protected function _normalize($args) {
        $normalized = [];
        array_walk_recursive($args, function($a) use (&$normalized) {
            $normalized[] = $a;
        });
        while (count($normalized) < 5) {
            $normalized[] = \NULL;
        }
        return $normalized;
    }

}
