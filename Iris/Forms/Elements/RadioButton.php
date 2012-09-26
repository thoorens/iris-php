<?php

namespace Iris\Forms\Elements;

use Iris\Forms as ifo;

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
 * An unique radio button in a form (seldom used)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class RadioButton extends \Iris\Forms\_Element {

    protected $_contantValue = \NULL;

    /**
     * Radio box share a common name
     * 
     * @var string
     */
    protected $_commonName = '';

    public function __construct($name, $options = array()) {
        parent::__construct($name, 'input', $options);
        $this->_subtype = 'radio';
        $this->setLabel($name);
        $this->_labelPosition = self::AFTER + self::INNER;
        $this->_checkable = TRUE;
    }

    public function baseRender($key = \NULL) {
        $this->_value = $key;
        return parent::baseRender($key);
    }

    protected function _renderName() {
        $id = $this->getName();
        $name = $this->_commonName;
        return " name=\"$name\" id=\"$id\" ";
    }

    /**
     * The radio button value is relative to its group
     * 
     * @return int
     */
    public function getValue() {
        return parent::getValue();
    }

    public function setCommonName($commonName) {
        $this->_commonName = $commonName;
    }

}

