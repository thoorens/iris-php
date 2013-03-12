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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Dojo has its own version of various validators. While maintaining
 * a server side validation, they provide a previous client side 
 * validation and error display.
 * 
 */
abstract class _Validator implements \Iris\Translation\iTranslatable{

    use \Dojo\Translation\tSystemTranslatable;
    
    /**
     *
     * @var \Iris\Forms\_Element 
     */
    protected $_element;

    /**
     *
     * @var _Validator 
     */
    protected $_previous = NULL;

    public function prepareValue($value) {
        return $value;
    }

    public function validate($value) {
        if (!$this->_localValidate($value)) {
            return FALSE;
        }
        if (!is_null($this->_previous)) {
            return $this->_previous->validate($value);
        }
        return TRUE;
    }

    /**
     * @param mixed $value
     * @return boolean 
     */
    protected function _localValidate($value) {
        return TRUE;
    }

    public function addValidator(_Validator $validator) {
        $this->_previous = $validator;
    }

    public function setElement($element) {
        $this->_element = $element;
    }
    
    
    
}

