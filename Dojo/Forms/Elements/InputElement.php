<?php

namespace Dojo\Forms\Elements;

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
 * Dojo version of InputElement
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class InputElement extends \Iris\Forms\Elements\InputElement {

    use \Dojo\Forms\tDojoDijit;

    /**
     * Dojo uses some different names for classes of elements
     * @var string[]
     */
    protected $_equivalence = array(
        'text' => 'TextBox',
        'date' => 'DateTextBox',
        'time' => 'TimeTextBox',
        'password' => 'TextBox',
        'radio' => 'RadioBox',
        'checkbox' => 'CheckBox',
        'currency' => 'CurrencyTextBox',
    );

    /**
     *
     * @param string $name name of the element
     * @param string $type HTML type (input) or subtype (date)
     * @param type $options any option to add to HTML code
     */
    public function __construct($name, $type, $options = array()) {
        parent::__construct($name, $type, $options);
        if (isset($this->_equivalence[$type])) {
            $dojoName = $this->_equivalence[$type];
            $this->setDijitType("dijit.form.$dojoName");
            $dojoManager = \Dojo\Manager::GetInstance();
            $dojoManager->addRequisite("$dojoName", 'dijit/form/' . $dojoName);
        }
    }

    public function setSize($size) {
        if (isset($this->_attributes['style'])) {
            $oldStyle = $this->_attributes['style'];
        }
        else {
            $oldStyle = '';
        }
        $this->_attributes['style'] = $oldStyle . " width:" . $size . "em;";
        return $this;
    }

    
}


