<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class defines the proper treatment of the button links.
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

    protected static $_Type = self::BUTTON;


    /**
     * 
     * @return type
     */
    protected function _render() {
        if ($this->_empty($this->getLabel())) {
            $text = '';
        }
        elseif (!\Iris\Users\Session::JavascriptEnabled() or self::$NoJavaForce) {
            if (\Iris\System\Client::OldBrowser(self::$OldBrowser)) {
                $text = $this->_simulatedButton();
            }
            else {
                $text = $this->_linkButton();
            }
        }
        else {
            $text = $this->_javascriptButton();
        }
        return $text;
    }

    /**
     * Displays a standard HTML button with a javascript onclick URL
     *
     * @return string
     */
    protected function _javascriptButton() {
        $attributes = $this->_renderAttributes();
        $url = $this->getUrl(\TRUE);
        $label = $this->getLabel();
        if ($this->_used($url) and $url !=='!') {
            $onclick = "onclick=\"javascript:location.href='$url'\"";
        }
        else {
            $onclick = '';
        }
        return "<button $attributes $onclick>$label</button>\n";
    }

    /**
     * Displays a HTML button encapsulated in a link &lt;a> tag (if JS not active)
     *
     * @return string
     */
    protected final function _linkButton() {
        $url = $this->getUrl(\TRUE);
        $label = $this->getLabel();
        $attributes = $this->_renderAttributes();
// Button in a link
        return "<a href=\"$url\">" .
                "<button $attributes>$label</button></a>\n";
    }

    /**
     * Displays a button using a simple link plus internal style to simulate
     * a button aspect
     * 
     * @return string
     */
    protected final function _simulatedButton() {
        $this->setClass(' old_nav');
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
        $attributes = $this->_renderAttributes();
        $url = $this->getUrl();
        $label = $this->getLabel();
        return sprintf('<a href="%s" %s>&nbsp;%s&nbsp;</a>', $url, $attributes, $label);
    }

    /**
     * This method throws an exception if called with a button object
     * @throws \Iris\Exceptions\BadLinkMethodException
     */
    public function button($url = self::BLANKSTRING){
        throw new \Iris\Exceptions\BadLinkMethodException('A button cannot be transformed into a button');
    }
    
    /**
     * Transforms a button or an image to a link
     * 
     * @param type $url
     * @return \Iris\Subhelpers\Link
     */
    public function link($url = self::BLANKSTRING){
        $link = new Link([]);
        $this->_copyData($link);
        if($this->_used($url)){
            $link->setUrl($url);
        }
        return $link;
    }
}
