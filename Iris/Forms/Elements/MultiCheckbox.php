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
 * This group of checkbox is managed as having a unique value whose different
 * bits are assigned to each checkbox.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MultiCheckbox extends _ElementGroup {

    protected $_perLine = 4;
    protected $_itemType = 'Checkbox';

    public function __construct($name, $type, $formFactory, $options = []) {
        parent::__construct($name, 'div', $formFactory, $options);
    }

    protected function _dispatchValues() {
        $value = $this->_value;
        $key = 1;
        $count = 0;
        $max = count($this->_subComponents);
        // stopping loop on value may miss MSB
        while ($count < $max) {
            if (isset($this->_subComponents[$key])) {
                $digit = ($value % 2) ? '1' : '0';
                $this->_subComponents[$key]->setValue($key);
                if ($digit == 1) {
                    $this->_subComponents[$key]->checked = "checked";
                }
                $count++;
            }
            $value = floor($value / 2);
            $key*=2;
        }

        return $this;
    }

    /**
     * Computes the value of the components
     *  
     * @param mixed[] $data (data from POST) 
     */
    public function compileValue(&$data) {
        $sum = 0;
        $i = 1;
        foreach ($this->_subComponents as $element) {
            $dataName = $element->getName();
            if (isset($data[$dataName])) {
                $sum += $i;
            }
            $i *= 2;
        }
        return $sum;
    }

    /**
     * Permits to give a default value to the multicheckbox only if
     * no previous data can be found in $_POST
     * 
     * @param int $defaultValue A serie of bits in an integer serving of initial value
     * @param boolean $post Seeks values in $_POST, otherwise in $_GET
     */
    public function autoSet($defaultValue = 0, $post = \TRUE) {
        if ($post) {
            $data = $_POST;
        }
        else {
            $data = $_GET;
        }
        $sum = $this->compileValue($data);
        if (isset($data[$this->getName() . '0'])) {
            $this->setValue($sum);
        }
        else {
            $this->setValue($defaultValue);
        }
    }

    /**
     * Adds options to a the multicheckbox
     * 
     * @staticvar boolean $firsTime records a previous execution
     * @param mixed[] $pairs The names of the differents bits
     * @param boolean $valuesAsKeys (ignored)
     * @return \Iris\Forms\Elements\MultiCheckbox
     */
    public function addOptions($pairs, $valuesAsKeys = FALSE) {
        parent::addOptions($pairs, \FALSE);
        static $firstTime = \FALSE;
        if (!$firstTime) {
            $innerElement = $this->_formFactory->createHidden($this->_name . '0');
            $innerElement->_container = $this;
            //$this->_container->registerElement($innerElement);
            $this->_subComponents[0] = $innerElement;
            $this->_container->registerElement($innerElement);
        }
        return $this;
    }

}

