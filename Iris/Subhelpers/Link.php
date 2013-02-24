<?php

namespace Iris\Subhelpers;

use \Iris\System\Client as Client;

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
 * This class is a subhelper for the helper Link family. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Link extends \Iris\Subhelpers\_Subhelper {
    use tAutoRenderer;

    /**
     * Forces a NO javascript display (essentially for test purpose)
     * @var boolean
     */
    public static $NoJavaForce = \FALSE;
    /**
     * In NoJava mode, simulate an "old" browser
     * @var boolean
     */
    public static $OldBrowser = \FALSE;
    
    /**
     * A special array corresponding to a non existent button/link
     * @var array
     */
    public static $NoLink = ['!!!!NONE!!!!', '', ''];
    
    /**
     * HTML arguments may be added and removed from here
     * @var array 
     */
    protected $_attributes = array();

    /**
     * Each subhelper class has its own unique instance
     * @var static
     */
    protected static $_Instance = NULL;

    /**
     * Specifies an optional image folder
     * @var string
     */
    protected $_imageFolder = \NULL;
    

    /**
     * Magic method which returns the HTML code for the link or button
     * accroding to the the helper used to instanciate the subhelper
     * 
     * @param string $methodName The m
     * @param array $arguments
     * @return string
     */
    public function __call($methodName, $arguments) {
        return $this->autoRender($methodName, $arguments);
    }

    public function display() {
        $arguments = func_get_args();
        $def = substr($this->_type,1);
        return $this->autorender($def, $arguments);
    }

    /**
     * Realizes a link or a button with an image inside
     * 
     * @param string $fileName
     * @param string/array $label The label of the image or an array of up to four elements
     * @param string $url The URL of the link
     * @param string $tooltip A tooltip to display
     * @param string $class An optional class for the link
     * @return type
     */
    public function image($fileName, $label, $url = \NULL, $tooltip = \NULL, $class = \NULL) {
        $args = func_get_args();
        $fileName = array_shift($args);
        list($label, $url, $tooltip, $class) = $this->_normalize($args);
        $this->callViewHelper('styleLoader', 'nojsbutton', 'span.btnlabel{padding:0 0 20px 20px}');
        $image = $this->callViewHelper('image', $fileName, $label, $tooltip, $this->_imageFolder, 'btnlabel');
        $helperName = $this->_type;
        return $this->$helperName($image, $url, $tooltip, $class);
    }

    //public function autoRender($args){ // is in tAutoRenderer

    /**
     * Stops the rendering if the link is null 
     * Overwrites the method in tAutoRenderer
     * 
     * @param array $args
     * @return boolean
     */
    protected function _dontRender($args) {
        return ($args[0] == self::$NoLink[0]);
    }

    /**
     * A simple link HTML a link with href, title and class attributes
     * 
     * @param array $args The arguments : message, url, tooltip and class
     * @return string
     */
    protected function _link() {
        $args = func_get_args();
        list($label, $url, $tooltip, $class) = $this->_normalize($args);
        $attributes = $this->_standardAttributes($tooltip, $class);
        return sprintf('<a href="%s" %s >%s</a>', $url, $attributes, $label);
    }

    /**
     * A button HTML a link with href, title and class attributes
     * 
     * @param array $args The arguments : message, url, tooltip and class
     * @return string
     */
    protected function _button() {
        $args = func_get_args();
        list($label, $url, $tooltip, $class) = $this->_normalize($args);
        if (!\Iris\Users\Session::JavascriptEnabled() or self::$NoJavaForce) {
            $href = is_null($url) ? '' : "href=\"$url\"";
            if (Client::OldBrowser(self::$OldBrowser)) {
                return $this->_simulatedButton($label, $href, $tooltip, $class);
            }
            else {
                return $this->_linkButton($label, $href, $tooltip, $class);
            }
        }
        else {
            return $this->_javascriptButton($label, $url, $tooltip, $class);
        }
    }

    /**
     * Displays a standard HTML button with a javascript onclick URL
     * 
     * @param string $label The label of the button
     * @param string $url The URL where to go
     * @param string $tooltip The optional tooptip
     * @param string $class The optional class
     * @return string
     */
    private function _javascriptButton($label, $url, $tooltip, $class) {
        $attributes = $this->_renderAttributes($tooltip, $class);
        $onclick = is_null($url) ? '' : "onclick=\"javascript:location.href='$url'\"";
        return "<button $attributes $onclick>$label</button>\n";
    }

    /**
     * Displays a HTML button encapsulated in a link &lt;a> tag (if JS not active)
     * 
     * @param string $label The label of the button
     * @param string $href The href attribute (or empty string)
     * @param string $tooltip The optional tooptip
     * @param string $class The optional class
     * @return string
     */
    private function _linkButton($label, $href, $tooltip, $class) {
        $attributes = $this->_renderAttributes($tooltip, $class);
        // Button in a link
        return "<a $href>" .
                "<button $attributes>$label</button></a>\n";
    }

    private function _simulatedButton($label, $href, $tooltip, $class) {
        $class .= ' old_nav';
        $attributes = $this->_renderAttributes($tooltip, $class);
        $this->callViewHelper('styleLoader', 'oldbrowserbuttonn', <<<STYLE
a.old_nav{
    background-color:#EEE;
    border: black outset 1px;
    text-decoration: none;
    color :black;
}

a.old_nav:hover{
    background-color:#EEE;
    border:  blue inset 1px;
}

STYLE
        );
        return "<a $href $attributes>&nbsp;$label&nbsp;</a>\n";
    }

    

    private function _renderAttributes($tooltip, $class) {
        $html = '';
        foreach ($this->_attributes as $name => $value) {
            $html .= "$name=\"$value\" ";
        }
        $html .= $this->_standardAttributes($tooltip, $class);
        // ID is wiped out after creating attribute string
        // so that it is not used twice
        $this->removeAttribute('id');
        return $html;
    }

    /**
     * Prepares the standard attributes tooltip and class
     * 
     * @param type $tooltip
     * @param type $class
     * @return type
     */
    private function _standardAttributes($tooltip, $class) {
        $html = is_null($tooltip) ? '' : " title=\"$tooltip\"";
        $html .= is_null($class) ? '' : " class=\"$class\"";
        return $html;
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
     * Removes an attribute, if it exists. May be usefull when 
     * the link singleton is used to create various links.
     * 
     * @param string $name
     * @return \Iris\Subhelpers\Link
     */
    public function removeAttribute($name) {
        if (isset($this->_attributes[$name])) {
            unset($this->_attributes[$name]);
        }
        return $this;
    }

    /**
     * Sets the ID of the current link (erased after generation of
     * the attribute string).
     * 
     * @param string $idName
     * @return \Iris\Subhelpers\Link
     */
    public function setId($idName) {
        $this->addAttribute('id', $idName);
        return $this;
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


}

