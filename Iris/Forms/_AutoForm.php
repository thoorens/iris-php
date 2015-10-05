<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An abstract class to be derived in AutoForm and IniForm
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _AutoForm {

    
    
    /**
     *
     * @var \Iris\DB\_Entity
     */
    protected $_entity;

    /**
     *
     * @var _FormFactory 
     */
    protected $_formFactory = \NULL;

    /**
     * The field data in an array indexed by the names
     * @var ElementSpecs[]
     */
    protected $_fields = [];

    /**
     * An simple array containing all the field names in positional order
     * 
     * @var string[]
     */
    protected $_fieldOrder = [];

    /**
     * The prepared form
     * 
     * @var  \Iris\Forms\Elements\Form
     */
    protected $_preparedForm = \NULL;

    /**
     * The label to put in submit button (by default Send)
     * @var string 
     */
    protected $_submitLabel = 'Send';

    /**
     * If necessary prepares the form and renders it
     * 
     * @return string
     */
    public function render() {
        if (is_null($this->_preparedForm)) {
            $this->prepare();
        }
        return $this->_preparedForm->render();
    }

    /**
     * Prepare an automatic form, ready to be rendered
     * or manually modified
     * 
     * @return \Iris\Forms\Elements\Form
     */
    public function prepare() {
        $formFactory = \Iris\SysConfig\Settings::$DefaultFormClass;
        $this->_formFactory = new $formFactory();
        $metadata = $this->_entity->getMetadata();
        //$this->_scanConfigFile(); // may overidde $this->_formFactory
        $this->_preparedForm = $this->_formFactory->createForm("iris_autoform_" . $metadata->getTablename());
        foreach ($this->_fieldOrder as $name) {
            $field = $this->_fields[$name];
            $this->_createElement($name, $field);
        }
        $this->_addSubmit();
        return $this->_preparedForm;
    }

    /**
     * Creates a new element using the metadata, or the ini field specifications
     * 
     * @param string $name
     * @param \Iris\DB\MetaItem $field
     */
    private function _createElement($name, $field) {
        $formFactory = $this->_formFactory;
        switch ($field->Type) {
            case 'text':
                $element = $formFactory->createText($name);
                break;
            case 'datetime':
                $element = $formFactory->createDate($name);
                break;
            case 'bool':
                $element = $formFactory->createCheckbox($name);
                break;
            default:
                $element = $formFactory->createText($name);
                break;
        }
        $this->putAttrributes($element, $field);
    }

    /**
     * If necessary, adds a submit button to the form
     */
    private function _addSubmit() {
        if (is_null($this->_preparedForm->getComponent('Submit'))) {
            $formFactory = $this->_preparedForm->getFormFactory();
            $element = $formFactory->createSubmit('Submit');
            $element->setValue($this->_submitLabel)->addTo($this->_preparedForm);
        }
    }

    /**
     * Change the default submit label
     * 
     * @param string $submitLabel
     * @return \Iris\Forms\_AutoForm
     */
    public function setSubmitLabel($submitLabel) {
        $this->_submitLabel = $submitLabel;
        return $this;
    }

    /**
     * 
     * @param _Element $element
     * @param \Iris\SysConfig\Config $field
     */
    public function putAttrributes($element, $field) {
        $element->addTo($this->_preparedForm);
        if(isset($field->Label)){
            $element->setLabel($field->Label);
        }
    }

}
