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

    public function setType($type) {
        $this->_type = $type;
        $this->render();
    }

    /* Each subclass has its own unique instance */

    protected static $_Instance = NULL;

    protected function _provideRenderer() {
        
    }

    /**
     * 
     * @return string
     */
    public function image() {
        $args = func_get_args();
        $fileName = array_shift($args);
        // If file name begins by /, use /images folder
        if ($fileName[0] != '/') {
            $src = 'src="/images/' . $fileName . '" ';
        }
        else {
            $src = 'src="http://' . $fileName . '" ';
        }
        list($label, $url, $tooltip, $class) = $this->_normalize($args);
        $this->callViewHelper('styleLoader', 'nojsbutton', 'span.btnlabel{padding:0 0 20px 20px}');
        $image = $this->callViewHelper('image', $fileName, $label, $tooltip, \NULL, 'btnlabel');
        $helperName = $this->_type;
        return $this->$helperName($image, $url, $tooltip, $class);
    }

    //public function autoRender($args){ // is in tAutoRenderer

    
    /**
     * 
     * @param array $args
     * @return boolean
     */
    protected function _dontRender($args){
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
        if (is_null($label)) {
            return $this;
        }
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
        if (is_null($label)) {
            return $this;
        }
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
    protected function _javascriptButton($label, $url, $tooltip, $class) {
        $attributes = $this->_getAttributes($tooltip, $class);
        $onclick = is_null($url) ? '' : "onclick=\"javascript:location.href='$url'\"";
        return "<button $attributes $onclick>$label</button>\n";
    }

    /**
     * Displays a HTML button encapsulated in a link &lt;a> tag (if JS not active)
     * @param string $label The label of the button
     * @param string $href The href attribute (or empty string)
     * @param string $tooltip The optional tooptip
     * @param string $class The optional class
     * @return string
     */
    protected function _linkButton($label, $href, $tooltip, $class) {
        $attributes = $this->_getAttributes($tooltip, $class);
        // Button in a link
        return "<a $href>" .
                "<button $attributes>$label</button></a>\n";
    }

    protected function _simulatedButton($label, $href, $tooltip, $class) {
        $class .= ' old_nav';
        $attributes = $this->_getAttributes($tooltip, $class);
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

    public static $NoJavaForce = \FALSE;
    public static $OldBrowser = \FALSE;
    protected $_attributes = array();

    /**
     * A special array corresponding to a non existent button/link
     * @var array
     */
    public static $NoLink = array('!!!!NONE!!!!', '', '');

    protected function _getAttributes($tooltip, $class) {
        $html = '';
        foreach ($this->_attributes as $name => $value) {
            $html .= "$name=\"$value\" ";
        }
        $html .= $this->_standardAttributes($tooltip, $class);
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
     * Modifies the array so that <ul>
     * <li> it has 4 elements
     * <li> if the first is an array, ignores the rest
     * </ul>
     * 
     * @param array $args
     * @return type
     */
    protected function _normalize($args) {
        if (is_array($args[0])) {
            $data = $args[0];
        }
        else {
            $data = $args;
        }
        while (count($data) < 4) {
            $data[] = \NULL;
        }
        return $data;
    }

    public function addAttribute($name, $value) {
        $this->_attributes[$name] = $value;
        return $this;
    }

    public function removeAttribute($name){
        if(isset($this->_attributes[$name])){
            unset($this->_attributes[$name]);
        }
    }
    
}
