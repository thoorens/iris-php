<?php

namespace Dojo\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * @todo Write the description  of the trait
 */
trait tDojoDijit {

    protected $_hasDijit = \TRUE;
    
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
    public function addDijitAttribute($name, $value) {
        $this->_dijitAttributes[$name] = $value;
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
                    $dojoProps .= "$attr:'$value' ";
                }
            }
        }
        if ($dojoProps != '') {
            $text .= sprintf('data-dojo-props = "%s"', $dojoProps);
        }
        return $text . parent::_renderAttributes();
    }

}

