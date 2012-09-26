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
 * A group a radio button, managed together
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class RadioGroup extends _ElementGroup implements iAidedValue {

    protected $_perLine = 4;
    protected $_itemType = 'RadioButton';

    protected function _dispatchValues() {
        $value = $this->_value;
        /* @var $radio \Iris\Forms\Elements\RadioButton */
        foreach ($this->_subComponents as $key => $radio) {
            if ($value == $key) {
                $radio->checked = 'checked';
            }
            $radio->setValue($key);
        }
    }

    protected function _addOption($key, $value) {
        $innerElement = parent::_addOption($key, $value);
        $innerElement->setCommonName($this->getName());
        return $innerElement;
    }

    public function compileValue(&$data) {
        $value = \NULL;
        if (isset($data[$this->getName()])) {
            $value = $data[$this->getName()];
        }
        return $value;
    }

}

?>
