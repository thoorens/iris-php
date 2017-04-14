<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * A abstract class for the form layout. Three concrete formlayout are
 * designed, but the developer may creates his own thanks to the facilites
 * defined here.
 * A factory has been added to create a form layout according to the one specified in settings
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _FormLayout {

    /**
     * The tag used at the beginning and end of the form (e.g table or dl)
     * @var string 
     */
    protected $_main;

    /**
     * The tag used at the beginning and end of a group (e.g. nothing or dl)
     * @var string
     */
    protected $_group;

    /**
     * 
     * @var mixed[]
     */
    protected $_before;

    /**
     *
     * @var mixed[]
     */
    protected $_middle;

    /**
     *
     * @var mixed[]
     */
    protected $_after;

    /**
     *
     * @var string
     */
    protected $_attributePosition = 1;
    /**
     *
     * @var _Element 
     */
    protected $_currentElement = NULL;

    /**
     * A factory which creates a layout instance from the provided name
     * or according to the default form layout specified in settings
     * 
     * @param string $layoutName
     * @return \Iris\Forms\_FormLayout
     */
    public static function Factory($layoutName = \NULL){
        if($layoutName == \NULL){
            $layoutName = \Iris\SysConfig\Settings::$DefaultFormLayout;
        }
        switch($layoutName){
            case 'TabLayout':
                $layout = new TabLayout();
                break;
            case 'NoLayout':
                $layout = new NoLayout();
                break;
            case 'DefLayout':
            default:
                $layout = new DefLayout();
                break;
        }
        return $layout;
    }
    
    /**
     * Creates a opening tag for the form (with the help of $_main value)
     * 
     * @param string $attributes Optional attributes
     * @return string 
     */
    public function getEntry($attributes='') {
        return $this->_getExtern($this->_main, FALSE, $attributes);
    }

    /**
     * Creates a closing tag for the form (with the help of $_main value)
     * @return string 
     */
    public function getExit() {
        return $this->_getExtern($this->_main, TRUE);
    }

    /**
     * Generates the opening or closing tag for a form/group
     * with attribute on the opening. This corresponds to
     * getXXEntry(), getXXExit() methods where XX is '' or Group 
     * 
     * @param string $tag
     * @param boolean $closing TRUE if closing
     * @param string $attributes The attributes in text form
     * @return type 
     */
    protected function _getExtern($tag, $closing, $attributes='') {
        $text = '';
        if ($tag != '!') {
            if ($closing) {
                $tag = "/" . $tag;
            }
            $text = "<$tag  $attributes >";
        }
        return $text;
    }

    /**
     * Renders the opening separator of an element
     * 
     * @param string $attributes Optional attributes as a text
     * @return string 
     */
    public function initialSeparator($attributes='') {
        return $this->_renderSeparator($this->_before, $attributes);
    }

    /**
     * Renders an internal separator of an element, typically between
     * the label and the graphic control
     * 
     * @param string $attributes Optional attributes as a text
     * @return string 
     */
    public function innerSeparator($attributes='') {
        return $this->_renderSeparator($this->_middle, $attributes);
    }

    /**
     * Render the closing separator of an element 
     * 
     * @param string $attributes Optional attributes as a text
     * @return string 
     */
    public function finalSeparator($attributes='') {
        return $this->_renderSeparator($this->_after, $attributes);
    }

    /**
     * Renders a separator initial/inner/final using data provided.
     * The data are an array having two parts: <ul>
     * <li> a number indicating in which component the atttributes have to
     * be put in
     * <li> a text grouping 1 or usually 2 component separed by '|', each
     * of which is rendered as an htmm tag 
     * </ul>
     * 
     * @see TabLayout for en example
     * 
     * @param string[] $sepArray
     * @param string $attributes Optional attributes as a text
     * @return string 
     */
    protected function _renderSeparator($sepArray, $attributes='') {
        list($attributePosition, $tags) = $sepArray;
        $text = '';
        if ($tags != '!') {
            foreach (explode('|', $tags) as $key => $tag) {
                if ($key == $attributePosition) {
                    $text .= "\t<$tag $attributes>\n";
                }
                else {
                    $text .="\t<$tag>\n";
                }
            }
        }
        return $text;
    }

    /**
     * Generates the opening tag (if any) of a group
     * @param string $attributes Optional attributes as a text
     * @return string
     */
    public function getGroupEntry($attributes='') {
        return $this->_getExtern($this->_group, FALSE, $attributes);
    }

    /**
     * Generates the closing tag (if any) of a group
     * @return string
     */
    public function getGroupExit() {
        return $this->_getExtern($this->_group, TRUE);
    }

    public function setCurrentElement($elementName) {
        $this->_currentElement = $elementName;
    }

}


