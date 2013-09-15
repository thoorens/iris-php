<?php

namespace Iris\Forms\Validators;

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
 * This validator checks if both password fields has same content
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Identical extends _Validator {

    protected function _localValidate(&$value) {
        echo "Validating required $value<br/>";
        die($this->_element->getName());
        if ($value === NULL) {
            $this->_element->getFirstComponent->setError($this->_('The two passwords do not match', TRUE));
        }
        if ($value == "") {

            $this->_element->setError($this->_('This password is required', TRUE));
            return FALSE;
        }
        return TRUE;
    }

}


