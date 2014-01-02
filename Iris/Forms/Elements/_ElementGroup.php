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
 * An abstract group, serving as a super class for ButtonGroup, RadioGroup... 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * An abstract group, serving as a super class for ButtonGroup, RadioGroup...
 * 
 */
abstract class _ElementGroup extends \Iris\Forms\_Element implements iAidedValue, \Iris\Translation\iTranslatable {
    
    use \Iris\Translation\tSystemTranslatable;

    protected static $_NeedRegister = FALSE;
    protected $_subComponents = array();
    protected static $_EndTag = TRUE;
    protected $_perLine = 4;
    protected $_itemType = '';

    /**
     *
     * @var _FormFactory
     */
    protected $_formFactory;

    public function __construct($name, $type, $formFactory, $options = array()) {
        $this->_formFactory = $formFactory;
        parent::__construct($name, $type, $options);
    }

    protected function _renderComponents() {
        $this->_dispatchValues();
        $html = '';
        $line = 1;
        foreach ($this->_subComponents as $key => $component) {
            $html .= $component->baseRender($key);
            if ($line++ == $this->_perLine) {
                $html .= '<br/>';
                $line = 1;
            }
        }
        return $html;
    }

    /**
     * According to group type, take the common value and dispatch it
     * in the different component (bit field or unique value). The array must
     * provide an key and a value for each element
     */
    abstract protected function _dispatchValues();

    /**
     * Permits to init the components of the widget. Returns an exception if
     * the widget is not registred in a form or subcontainer
     * 
     * @param string[] $pairs
     * @return _ElementGroup 
     * @throw FormException
     */
    public function addOptions($pairs, $valuesAsKeys = FALSE) {
        if (is_null($this->_container)) {
            throw new \Iris\Exceptions\FormException(
                    $this->_('addOption need the object to be registred before (with addTo()).',FALSE));
        }
        if ($valuesAsKeys) {
            $pairs = array_combine($pairs, $pairs);
        }
        foreach ($pairs as $key => $value) {
            $this->_addOption($key, $value);
        }

        return $this;
    }

    /**
     *
     * @param type $key
     * @param type $value
     * @return Element
     */
    protected function _addOption($key, $value) {
        $createMethod = "create" . $this->_itemType;
        $innerElement = $this->_formFactory->$createMethod($this->_name.$key)
                ->setValue($value)
                ->setLabel($value);
        $innerElement->_container = $this;
        //$this->_container->registerElement($innerElement);
        $this->_subComponents[$key] = $innerElement;
        if (static::$_NeedRegister) {
            $this->_container->registerElement($innerElement);
        }
        return $innerElement;
    }

    /**
     * Returns the actual value of the controller (as set during form creation)
     * @return mixed 
     */
    public function getValue() {
        return parent::getValue();
    }

    

    /**
     * Set the max number of item per line
     * 
     * @param int $perLine
     * @return _ElementGroup 
     */
    public function setPerLine($perLine) {
        $this->_perLine = $perLine;
        return $this;
    }

    public function addValidator($validator) {
        foreach ($this->_subComponents as $subComponent) {
            $subComponent->addValidator($validator);
        }
        return parent::addValidator($validator);
    }

    public function validate() {
        $valid = TRUE;
        foreach ($this->_subComponents as $subComponent) {
            $valid = $subComponent->validate();
        }
        return $valid;
    }

    
}

