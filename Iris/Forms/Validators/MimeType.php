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
 * Validator for the MimeType for a file input field
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MimeType extends _Validator {

    protected $_expectedTypes = array();

    public function __construct($type) {
        if (is_array($type)) {
            $this->_expectedTypes = $type;
        }
        else {
            $this->_expectedTypes = array($type);
        }
    }

    protected function _localValidate(&$dummmy) {
        if (count($this->_expectedTypes)) {
            $type = $_FILES[$this->_element->getName()]['type'];
            if (in_array($type, $this->_expectedTypes) === FALSE) {
                $this->_element->setError($this->_('The file corresponds to none of the expected MIME types',TRUE));
                return FALSE;
            }
        }
        return TRUE;
    }

}


