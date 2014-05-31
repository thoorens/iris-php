<?php
namespace Iris\Forms\Elements;


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
 * A single checkbox in a form
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Checkbox extends \Iris\Forms\_Element implements iAidedValue{

    public function __construct($name, $options = array()) {
        parent::__construct($name, 'input', $options);
        $this->setLabel($name);
        $this->_subtype = 'checkbox';
        $this->_labelPosition = self::AFTER + self::INNER;
        $this->_checkable = TRUE;
    }

    /**
     * Checkbox are not present in POST when not checked
     * 
     * @param type $data
     * @return boolean 
     */
//    public function gggetValue($data=NULL) {
//        if($this->_value!==''){
//            return $this->_value;
//        }
//        if (is_null($data)) {
//            $data = \Iris\Engine\Superglobal::GetPost();
//        }
//        $value = FALSE;
//        if (isset($data[$this->getName()])) {
//            $value = TRUE;
//        }
//        $this->setValue($value);
//        return $value;
//    }

    public function compileValue(&$data) {
        $value = FALSE;
        if (isset($data[$this->getName()])) {
            $value = TRUE;
        }
        $this->setValue($value);
        return $value;
    }

   

    
}

