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
class Item {

    protected $_name;
    protected $_value;

    protected $itemProps = array();
    
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
     *
     * @var boolean 
     */
    protected $_default = \FALSE;
    
    
    protected $_link = NULL;
    
    public function getLink() {
        return $this->_link;
    }

    public function setLink($link) {
        $this->_link = $link;
        return $this;
    }

    function __construct($name, $value) {
        $this->_name = $name;
        $this->_value = $value;
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

    public function getName() {
        return $this->_name;
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
     * 
     * @param boolean $default
     */
    public function setDefault($default = \TRUE) {
        $this->_default = $default;
    }

    public function render($JS) {
        $label = $this->getValue();
        $name = $this->getName();
        if (!$JS) {
            // none of the items are displayed except the selected one
            if ($this->_altNodisplay) {
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
                $selected = ' selected = "true"';
            }
            else {
                $selected = '';
            }
            //@todo splitter ain't allways true
            $this->itemProps[] = "splitter:'true'";
            $props = implode(',',$this->itemProps);
            $html =  sprintf(' id="%s" title="%s" data-dojo-type="dijit.layout.ContentPane" %s data-dojo-props="%s"',
                    $name, $label, $selected, $props); // $specialAttributes 
            if(!is_null($this->_link)){
                $html .= sprintf(' href="%s" ',$this->_link);
            }
        }
        return $html;
    }

    public function addSpecialProp($itemProp) {
        $this->itemProps[] = $itemProp;
    }

}

