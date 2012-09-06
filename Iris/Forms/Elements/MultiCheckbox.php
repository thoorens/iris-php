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
 * This group of checkbox is managed as having a unique value whose different
 * bits are assigned to each checkbox.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MultiCheckbox extends _ElementGroup{

    protected $_perLine = 4;
    protected $_itemType = 'Checkbox';
    
    public function __construct($name, $type, $formFactory, $options = array()) {
        parent::__construct($name, 'div', $formFactory, $options);
    }
    
    protected function _dispatchValues() {
        $value = $this->_value;
        $key = 1;
        $count = 0;
        $max = count($this->_subComponents);
        // stopping loop on value may miss MSB
        while ($count<$max) {
            if (isset($this->_subComponents[$key])) {
                $digit = ($value % 2) ? '1' : '0';
                $this->_subComponents[$key]->setValue($digit);
                $count++;
            }
            $value = floor($value/2);
            $key*=2;
        }

        return $this;
    }
    
    /**
     *
     * @param array $data (data from POST) 
     */
    public function compileValue(&$data=NULL) {
        $sum = 0;
        $i = 1;
        foreach ($this->_subComponents as $element) {
            $dataName = $element->getName();
            if (isset($data[$dataName])) {
                $sum += $i;
            }
//            if($element->getValue()){
//                $sum += $i;
//            }
            $i *= 2;
        }
        return $sum;
    }

    


    

}

?>
