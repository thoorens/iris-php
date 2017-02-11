<?php

namespace Iris\Forms\Elements;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
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
abstract class _ElementGroup extends \Iris\Forms\_Element implements iAidedValue{ //, \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    protected static $_NeedRegister = \FALSE;
    protected $_subComponents = array();
    protected static $_EndTag = \TRUE;
    protected $_perLine = 4;
    protected $_itemType = '';

    /**
     *
     * @var _FormFactory
     */
    protected $_formFactory;

    public function __construct($name, $type, $formFactory, $options = []) {
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
    public function addOptions($pairs, $valuesAsKeys = \FALSE) {
        if (is_null($this->_container)) {
            throw new \Iris\Exceptions\FormException(
            $this->_('addOption need the object to be registred before (with addTo()).', \FALSE));
        }
        if ($valuesAsKeys) {
            $pairs = array_combine($pairs, $pairs);
        }
        //iris_debug($pairs);
        foreach ($pairs as $key => $value) {
            $this->_addOption($key, $value);
        }

        return $this;
    }

    /**
     * Creates a new element in the group and returns it.
     * 
     * @param mixed $key The element key
     * @param mixed $value The element value
     * @return _ElementGroup
     */
    protected function _addOption($key, $value) {
        $innerElement = $this->_createInnerElement($this->_name, $key)
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
     * Creates an internal element belonging to the group. Some
     * element types may require a proper create method interface.
     * 
     * @param  string $name The name of the group
     * @param mixed $key The specific key value for the element
     * @return _ElementGroup
     */
    protected function _createInnerElement($name, $key){
        $createMethod = "create" . $this->_itemType;
        return $this->_formFactory->$createMethod($name.$key);
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
        $valid = \TRUE;
        foreach ($this->_subComponents as $subComponent) {
            $valid = $subComponent->validate();
        }
        return $valid;
    }

}
