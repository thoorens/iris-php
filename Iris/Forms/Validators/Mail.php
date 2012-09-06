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
 * Validator to check a email name structure
 * (does not verify the existence of the address)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Mail extends _Validator {

    protected $_pattern = "/^[a-zA-Z]([.]?([a-zA-Z0-9_-]+)*)?@([a-zA-Z0-9\-_]+\.)+[a-zA-Z]{2,4}$/";
    

    protected function _localValidate(&$value) {
        $found = array();
        preg_match($this->_pattern,$value,$found);
        if(count($found)>0 ){
            $found = $found[0];
        }
        if($value != $found){
            $this->_element->setError($this->_('Invalid email adresse',TRUE));
            return FALSE;
        }else{
            return TRUE;
        }
    }

}

?>
