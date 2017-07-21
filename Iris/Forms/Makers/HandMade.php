<?php

namespace Iris\Forms\Makers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * A form factory using data in a list
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
class HandMade extends \Iris\Forms\_FormMaker {

    public static $Mode = self::MODE_HANDMADE;

    /**
     * 
     * @param string[] $parameter a list of field description
     * @param type $factoryType
     * @param type $formName
     */
    public function __construct($parameter, $factoryType = \NULL, $formName = 'form') {
        $this->_initFactory($factoryType);
        $this->setFormName($formName);
        $this->scanList($parameter);
    }

    /**
     * Converts the list of field definition to an AutoListElement and stores it
     * in an array
     * 
     * @param string[] type $list
     */
    public function scanList($list) {
        $this->_specifySource(self::MODE_HANDMADE);
        $position = 0;
        foreach ($list as $line) {
            $features = $this->_scanLine($line);
            $features['P'] = $position;
            $fieldName = $features['N'];
            $autoElement = new ElementMaker($features);
            $this->_fieldList[$fieldName] = $autoElement;
            $this->_fieldOrder[$position++] = $fieldName;
        }
    }

//    protected function _insertElements_() {
//        $form = $this->getForm();
//        $formFactory = $form->getFormFactory();
//        AutoEntityElement::Prepare($formFactory, $form);
//        i_n(8, $this->_form);
////        $form = $this->getForm();
////        $formFactory = $form->getFormFactory();
//        foreach ($this->_fieldOrder as $fieldName) {
//            $field = $this->_fieldList[$fieldName];
//            $this->_insertField($formFactory, $form, $field);
//        }
//    }

    protected function _insertElements() {
        ElementMaker::Prepare($this->_form);
        foreach ($this->_fieldOrder as $position => $fieldName) {
            $field = $this->_fieldList[$fieldName];
            $field->addElement();
        }
    }

    /**
     * 
     * @param \Iris\Forms\_FormFactory $formFactory
     * @param \Iris\Forms\_Form $form
     * @param ElementMaker $field
     */
    protected function _insertField($formFactory, $form, $field) {
        i_d($field);
        $parameters = explode('!', $field);
        $method = "create" . ucfirst(array_shift($parameters));
        $i = 1;
        while ($i < count($parameters)) {
            $parameter = $parameters[$i - 1];
            $value = $parameters[$i];
            switch (strtoupper($parameter)) {
                case 'N':
                case 'NAME':
                    $element = $formFactory->$method($value);
                    break;
                case 'L':
                case 'LABEL':
                    $element->setLabel($value);
                    break;
                case 'T':
                case 'TITLE':
                    $element->setTitle($value);
                    break;
            }
            $i += 2;
        }
        $form->addElement($element);
    }

    public function parseFields() {
        
    }

}
