<?php

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
 * This class is a subhelper for the helper Link family.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Button extends \Iris\Subhelpers\_SuperLink {

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

    public function __toString() {
        if ($this->_nodisplay) {
            $this->_image = \FALSE;
            return '';
        }
        $this->_renderImage();
        $attributes = $this->_renderAttributes();
        if (!\Iris\Users\Session::JavascriptEnabled() or self::$NoJavaForce) {
            if (\Iris\System\Client::OldBrowser(self::$OldBrowser)) {
                return $this->_simulatedButton();
            }
            else {
                return $this->_linkButton();
            }
        }
        else {
            return $this->_javascriptButton();
        }
        return sprintf('<a href="%s" %s >%s</a>', $this->getUrl(), $attributes, $this->getLabel());
    }

    /**
     * Displays a standard HTML button with a javascript onclick URL
     *
     * @return string
     */
    private function _javascriptButton() {
        $attributes = $this->_renderAttributes();
        $url = $this->getUrl();
        $label = $this->getLabel();
        $onclick = is_null($url) ? '' : "onclick=\"javascript:location.href='$url'\"";
        return "<button $attributes $onclick>$label</button>\n";
    }

    /**
     * Displays a HTML button encapsulated in a link &lt;a> tag (if JS not active)
     *
     * @return string
     */
    private function _linkButton() {
        $attributes = $this->_renderAttributes();
        $url = $this->getUrl();
        $label = $this->getLabel();
// Button in a link
        return "<a href=\"$url\">" .
                "<button $attributes>$label</button></a>\n";
    }

    private function _simulatedButton() {
        $this->setClass(' old_nav');
        $attributes = $this->_renderAttributes();
        \Iris\views\helpers\StyleLoader::HelperCall('StyleLoader', ['oldbrowserbuttonn', <<<STYLE
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
        ]);
        $url = $this->getUrl();
        $label = $this->getLabel();
        return sprintf('<a href="%s" %s>&nbsp;%s&nbsp;</a>', $url, $attributes, $label);
    }

}
