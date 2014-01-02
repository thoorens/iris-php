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
 * A double password with consistency validation
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DoublePassword extends \Iris\Forms\Elements\_ElementGroup {

    protected static $_NeedRegister = TRUE;
    protected $_perLine = 1;
    protected $_itemType = 'Password';
    protected $_readOnly = FALSE;
    protected static $_EndTag = TRUE;
    protected $_master = TRUE;
    protected $_pwd1;
    protected $_pwd2;

    public function __construct($name, $formFactory, $options = array()) {
        parent::__construct($name, 'div', $formFactory, $options);
        $this->_labelPosition = self::NONE;
        
    }

    /**
     * Overridden method to register the two components as soon as possible,
     * add them Required validator et 
     * 
     * @param iFormContainer $container
     * @return DoublePassword 
     */
    public function addTo(iFormContainer $container) {
        parent::addTo($container);
        $this->_createSubcomponents();
        return $this; // fluent interface
    }

    protected function _createSubcomponents(){
        $this->_pwd1 = $this->_addOption('_1', '')
                ->addValidator($this->_formFactory->validatorRequired());
        $this->_pwd2 = $this->_addOption('_2', '')
                ->addValidator($this->_formFactory->validatorRequired());
        $identical = $this->_formFactory->validatorIdentical();
        $identical->setElement($this);
        if (is_null($this->_validator)) {
            $this->_validator = $identical;
        }
        else {
            $this->_validator->addValidator($identical);
        }
    }
    
    public function setLabel($label) {
        $label .= "|" . $this->_("Retype password:",TRUE);
        return parent::setLabel($label);
    }

    
    
    protected function _renderComponents() {
        // if two labels provided, split them 
        // otherwise use localized "Retype password" 
        $this->_dispatchValues();
        $i = 0;
        $html = '';
        foreach ($this->_subComponents as $pw) {
            $pw->setLabel($this->getLabel($i++));
            $html .= $pw->render();
        }
        return $html;
    }

    /**
     * Each password copy receives a null string
     */
    protected function _dispatchValues() {
        foreach ($this->_subComponents as $pw) {
            $pw->setValue('');
        }
    }

    /**
     * If both password are similar, returns the common value
     * otherwise return NULL
     * 
     * @param mixed[] $data Data from form
     * @return string 
     */
    public function compileValue(&$data) {
        $value1 = $data[$this->_pwd1->getName()];
        $value2 = $data[$this->_pwd2->getName()];
        if ($value1 == $value2) {
            return $value1;
        }
        else {
            return NULL;
        }
    }

    /**
     * You can't addOptions to a DoublePassword (subcomponents are internally managed)
     */
    public function addOptions($dummy, $dummy2=FALSE) {
        throw new \Iris\Exceptions\FormException('addOptions is reserved as an internal method');
    }

    /**
     * 
     * @param type $value
     * @param type $key
     * @return Element 
     */
    protected function _addOption($key, $value) {
        if ($key != '_1' and $key != '_2') {
            throw new \Iris\Exceptions\FormException('_addOption is reserved as an internal method');
        }
        $ret =parent::_addOption($key, $value);
        return $ret;
    }

    public function getFirstComponent() {
        return $this->_pwd1;
    }


    public function validate() {
        $valid = parent::validate();
        if($valid and $this->getValue()==NULL){
            $this->_pwd1->setError($this->_('The two passwords do not match',TRUE));
            return FALSE;
        }else{
            return $valid;
        }
    }

    
}


