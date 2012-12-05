<?php

namespace Dojo\Engine;

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
                if (!$this->_default)
                    $html = 'style = "display:none"';
                else
                    $html = '';
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
            $this->itemProps[] = "splitter:'true'";
            $props = implode(',',$this->itemProps);
            $html =  sprintf(' id="%s" title="%s" data-dojo-type="dijit.layout.ContentPane" %s data-dojo-props="%s"',
                    $name, $label, $selected, $props); // $specialAttributes 
        }
        return $html;
    }

    public function addSpecialProp($itemProp) {
        $this->itemProps[] = $itemProp;
    }

}

