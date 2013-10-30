<?php

namespace Dojo\Forms;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * 
 */
trait tDojoDijit {

    private $_dijitType = \NULL;
    private $_dijitAttributes = [];

    public function getDijitType() {
        return $this->_dijitType;
    }

    /**
     * 
     * @param type $dijitType
     * @return \Iris\Forms\_Element
     */
    public function setDijitType($dijitType) {
        $this->_dijitType = $dijitType;
        return $this;
    }

    /**
     * 
     * @param type $name
     * @param type $value
     * @return static
     */
    public function addDijitAttribute($collection, $name, $value) {
        $this->_dijitAttributes[collection][$name] = $value;
        return $this;
    }

    protected function _renderAttributes() {
        $text = '';
        if (!is_null($this->_dijitType)) {
            $text = sprintf('data-dojo-type="%s" ', $this->_dijitType);
        }
        $dojoProps = '';
        foreach ($this->_dijitAttributes as $attr => $value) {
            if ($value != []) {
                if (is_array($value)) {
                    $composedValues = implode("','",$value);
                    $dojoProps .= "$attr : ['$composedValues']";
        //if(stop) iris_debug($dojoProps);
                }
                else {
                    $dojoProps .= "$attr='$value' ";
                }
            }
        }
        if ($dojoProps != '') {
            $text .= sprintf('data-dojo-props = "%s"', $dojoProps);
        }
        return $text . parent::_renderAttributes();
    }

}

