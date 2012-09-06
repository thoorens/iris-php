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
 *  A container select for many options in a form.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class SelectElement extends \Iris\Forms\Elements\_ElementGroup {

    protected $_itemType = 'Option';
    protected $_readOnly = FALSE;
    protected $_perLine = -1; // infinite
    protected static $_EndTag = TRUE;
    protected $_master = TRUE;

    public function __construct($name, $type, $options = array()) {
        parent::__construct($name, 'select', $options);
    }

    public function setReadonly() {
        $this->_readOnly = TRUE;
    }

    protected function _dispatchValues() {
        $key = $this->getValue();
        if (isset($this->_subComponents[$key])) {
            $this->_subComponents[$key]->setChecked();
        }
    }

    public function compileValue(&$data) {
        if ($this->_readOnly) {
            $data[$this->_name] = $data['disabled_' . $this->_name];
        }
        return $data[$this->_name]; // HTTP-PHP had yet managed this value
    }

    public function setDisabled($value) {
        $hidden = $this->_formFactory->createHidden('disabled_' . $this->_name);
        $hidden->addTo($this->_container);
        $hidden->setValue($this->getValue());
        $this->setReadonly();
        parent::setDisabled($value);
    }

}

?>
