<?php

namespace JQuery\views\helpers;

defined('CRLF') or define('CRLF', "\n");


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
 * A popup window
 * 
 * Based on a tutorial
 * http://sohtanaka.developpez.com/tutoriels/javascript/creez-fenetre-modale-avec-css-et-jquery/
 * 
 * @todo
 * - internal icons
 * - link to jQuery engine
 * - close button title
 */
class PopUp extends \Iris\views\helpers\_ViewHelper {

    /**
     * Popup are NOT singleton
     * 
     * @var boolean
     */
    protected static $_Singleton = \FALSE;

    /**
     * The instance counter
     * @var int
     */
    protected static $_InstanceCount = 1;

    /**
     * The instance ID
     * @var int
     */
    protected $_instanceId;

    /**
     * Each instance will have its own id
     */
    protected function _init() {
        $this->_instanceId = self::$_InstanceCount ++;
    }

    /**
     * The width of the popup window
     * @var int
     */
    protected $_width = 500;

    /**
     * The opacity of the shadow on main window (in percent)
     * 
     * @var int
     */
    protected $_shadowOpacity = 80;

    /**
     * The color of the shadow on main window
     * @var mixed
     */
    protected $_shadowColor = '#000';

    /**
     * The color of the popup background
     * @var mixed
     */
    protected $_popBackground = '#fff';

    /**
     * The border color of the popup
     * @var mixed
     */
    protected $_borderColor = '#ddd';

    /**
     * The text inside the popup. If it is null, the
     * method _prepareContent() will be called to supply
     * the text content.
     * 
     * @var mixed 
     */
    protected $_content = \NULL;

    /**
     * The tooltip text on open button
     * 
     * @var string
     */
    protected $_title = "See more details";

    /**
     * The default info icon path
     * @var string 
     */
    protected $_infoIcon = '/!documents/file/images/icons/info32.png';
    
    /**
     * The default close icon path
     * @var string 
     */
    protected $_closeIcon = '/!documents/file/images/icons/close32.png';
    /**
     * The entry point with NULL argument will return the object for
     * later management
     * 
     * @param mixec $arg This argument will be used to create the rending
     * @return \JQuery\views\helpers\PopUp (or a string)
     */
    public function help($arg = \NULL) {
        if (is_null($arg)) {
            return $this;
        }
        else {
            return $this->render($arg);
        }
    }

    /**
     * Returns the link to open the popup (a simple link or an icon link)
     *   
     * @param string $icon if NULL, the default icon, if sempty string, a simple link
     * @param string $text The texte of the link if no icon is needed (empty string)
     * @return string
     */
    public function inButton($icon = \NULL, $text = '') {
        $width = $this->_width;
        $popupId = "popup" . $this->_instanceId;
        if ($icon === '') {
            $link = "<a href=\"#\" data-width=\"$width\" data-rel=\"$popupId\" class=\"poplight\">$text</a>";
        }
        else {
            if(is_null($icon)){
                $icon = $this->_infoIcon;
            }
            $title = $this->_($this->_title, \TRUE);
            $in = $this->callViewHelper('image', $icon, $title);
            $link = "<a href=\"#\" data-width=\"$width\" data-rel=\"$popupId\" class=\"poplight\">$in</a>";
        }
        return $link;
    }

    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    public function setShadowOpacity($shadowOpacity) {
        $this->_shadowOpacity = $shadowOpacity;
        return $this;
    }

    public function setPopBackground($popBackground) {
        $this->_popBackground = $popBackground;
        return $this;
    }

    public function setShadowColor($shadowColor) {
        $this->_shadowColor = $shadowColor;
        return $this;
    }

    public function setBorderColor($borderColor) {
        $this->_borderColor = $borderColor;
        return $this;
    }

    public function setContent($content) {
        $this->_content = $content;
        return $this;
    }

    public function setTitle($title) {
        $this->_title = $title;
        return $this;
    }

    public function render($data) {
        if (is_null($this->_content)) {
            $content = $this->_prepareContent($data);
        }
        $mainOpacity = $this->_shadowOpacity;
        $mainBackground = $this->_shadowColor;
        $popupBackground = $this->_popBackground;
        $popBorder = $this->_borderColor;
        $popupId = "popup" . $this->_instanceId;
        $closeIconTitle = $this->_('Close window');
        $closeIcon = $this->_closeIcon;

        return <<< STOP
        
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

        
<div id="$popupId" class="popup_block">
        $content
</div>
<style>
#fade { 
	display: none;
	background: $mainBackground;
	position: fixed; left: 0; top: 0;
	width: 100%; height: 100%;
	opacity: .$mainOpacity;
	z-index: 9999;
}
.popup_block{
	display: none; 
	background: $popupBackground;
	padding: 20px;
	border: 20px solid $popBorder;
	float: left;
	font-size: 1.2em;
	position: fixed;
	top: 50%; left: 50%;
	z-index: 99999;
	-webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
img.btn_close {
	float: right;
	margin: -55px -55px 0 0;
}
/*--Manage fixed position for IE6--*/
*html #fade {
position: absolute;
}
*html .popup_block {
position: absolute;
}
</style>
                
<script>
    
jQuery(function($){
						   		   
	$('a.poplight').on('click', function() {
		var popID = $(this).data('rel'); 
		var popWidth = $(this).data('width'); 

		$('#' + popID).fadeIn().css({ 'width': popWidth}).prepend('<a href="#" class="close"><img src="$closeIcon" class="btn_close" title="$closeIconTitle" alt="Close" /></a>');
		
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		$('body').append('<div id="fade"></div>');
		$('#fade').css({'filter' : 'alpha(opacity=$mainOpacity)'}).fadeIn();
		
		return false;
	});
	
	
	$('body').on('click', 'a.close, #fade', function() { 
		$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	});
		
		return false;
	});

	
});

</script>                
STOP;
    }

    /**
     * Optionally prepares the content of the popup. This method should be overridden
     * in subclasses and is only called when $_content is null.
     * 
     * @param mixed $data Data to be treated and displayed in the popup
     * @return string
     */
    protected function _prepareContent($data) {
        return <<<STOP
        <h1>Popup test</h1>
        <ol>
            <li>one</li>
            <li>two</li>
            <li>three</li>
        </ol>
STOP;
    }

}
