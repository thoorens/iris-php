<?php

namespace Dojo\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is used internally by all Dojo helpers to manage the
 * components to load.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Item{

    protected $_name;
    protected $_value;

    protected $itemProps = [];
    
    /**
     *
     * @var boolean 
     */
    protected $_closable = \FALSE;

    /**
     * 
     * @var boolean 
     */
    protected $_disabled = \FALSE;

    /**
     * If TRUE the item will be presented in front of the other
     * 
     * @var boolean 
     */
    protected $_default = \FALSE;
    
    
    protected $_link = NULL;
    
    /**
     * The display time in milliseconds
     * 
     * @var int
     */
    protected $_time = 0;

    /**
     * 
     * @param string $name The item will be identified by its name
     * @param mixed $value
     */
    function __construct($name, $value) {
        $this->_name = $name;
        $this->_value = $value;
    }
    
    public function getLink() {
        return $this->_link;
    }

    public function setLink($link) {
        $this->_link = $link;
        return $this;
    }

    

    public function getClosable() {
        return $this->_closable;
    }

    public function setClosable($closable = \TRUE) {
        $this->_closable = $closable;
    }

    public function getDisabled() {
        return $this->_disabled;
    }

    public function setDisabled($disabled = \TRUE) {
        $this->_disabled = $disabled;
    }

    public function getName($marker = \FALSE) {
        $name = $this->_name;
        if($marker){
            $name = sprintf("'%s'", $name);
        }
        return $name;
    }

    public function getValue() {
        return $this->_value;
    }

    /**
     * 
     * @return boolean
     */
    public function isDefault() {
        return $this->_default;
    }

    /**
     * Marks the item as the default one
     * 
     * @param boolean $default
     */
    public function setDefault($default = \TRUE) {
        $this->_default = $default;
    }

    /**
     * Gives the final render of an item according to the
     * Javascript status in the first parameter
     * 
     * @param boolean $JS
     * @param \Dojo\views\helpers\_Container $container The container in which the item is placed
     * @return string
     */
    public function itemRender($JS, $container) {
        $name = $this->getName();
        $label = $this->getValue();
        if(!is_string($label)){
            $label = $name;
        }
        if (!$JS) {
            // none of the items are displayed except the selected one
            if ($container->getAltNodisplay()) {
                if (!$this->_default){
                    $html = 'style = "display:none"';
                }
                else{
                    $html = '';
                }
            }else {
                // if all items are to be seen, the label is displayed as
                // a title. The div
                $html = "><h5>$label</h5><br";
            }
        }
        // javascript mode: dojo attributes are inserted in the div
        else {
            if ($this->_default) {
                $selected = ' data-dojo-selected = "true"';
            }
            else {
                $selected = '';
            }
            //@todo splitter ain't allways true
            if($container->getSplitter()){
                $this->itemProps[] = "splitter:'true'";
            }
            $props = implode(',',$this->itemProps);
            $html =  sprintf(' id="%s" title="%s" data-dojo-type="dijit.layout.ContentPane" %s data-dojo-props="%s"',
                    $name, $label, $selected, $props); // $specialAttributes 
            if(!is_null($this->_link)){
                $html .= sprintf(' href="%s" ',$this->_link);
            }
        }
        return $html;
    }

    /**
     * Adds a special prop to the item
     * 
     * @param string $itemProp
     */
    public function addSpecialProp($itemProp) {
        $this->itemProps[] = $itemProp;
    }

    /**
     * Getter for the display time of the item
     * 
     * @return int
     */
    public function getTime() {
        return $this->_time;
    }

    /**
     * Setter for display time of the item
     * 
     * @param int $time The time in milliseconds
     * @return \Dojo\Engine\Item for fluent interface
     */
    public function setTime($time) {
        $this->_time = $time;
        return $this;
    }


}

