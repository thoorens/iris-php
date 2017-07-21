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
 * A form factory using an ini file
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class IniFile extends \Iris\Forms\_FormMaker {

    public static $Mode = self::MODE_ENTITY;

    //protected $_fileName;

    /**
     * Construct an ini file formaker
     * 
     * @param mixed $fileName
     * @param string $factoryType
     * @throws \Iris\Exceptions\BadParameterException
     */
    public function __construct($fileName, $factoryType = \NULL, $formName = 'form') {
        $this->setFormName($formName);
        $this->_initFactory($factoryType, \FALSE); // this factory may be replaced in the header of the ini file
        $this->scanIni($fileName);
    }

      /**
     * Reads the ini file containing the form field descriptions
     * 
     * @param string $parameter
     */
    public function scanIni($parameter) {
        $this->_specifySource(self::MODE_INI);
        foreach ($this->_getIniContent($parameter) as $name => $description) {
            // Primary Treatment
            if ($this->_mode == self::MODE_INI) {
                $description['name'] = $name;
                $params = new ElementMaker($description);
                $fieldName = $params->getName();
                ////show_nd($fieldName);
                $this->_fieldList[$fieldName] = $params;
                $this->_fieldOrder[] = $fieldName;
            }
            // Complementary treatment
            else {
                $fieldName = $name;
                i_d('Complement AutoEntityElement here');
            }
        }
        die('OK');
    }
    
    /**
     * 
     */
    protected function _parseHeader() {
        $currentType = $this->_formFactory->getType();
        $factoryType = $this->_getParams('formFactory', $currentType);
        if ($currentType != $factoryType) {
            $this->_initFactory($factoryType);
        }
        $entity = $this->_getParams('entity', \NULL);
        if ($entity !== \NULL) {
            $entityName = "models\T" . ucfirst($entity);
            $entityNumber = $this->_getParams('entitynumber', 0);
            $entityClass = $entityName::GetEntity($entityNumber);
            $this->_metadata = $entityClass->getMetadata();
        }
        $formName = $this->_getParams('formname', \Iris\Forms\_FormFactory::AUTOFORMNAME);
        $this->setFormName($formName);
    }

//    public function parseFields() {
//        $formFactory = $this->getFormFactory();
//        foreach ($this->_iniParse as $name => $section) {
//            $element = $this->parseSection($section);
//        }
//    }

//    public function setFile($fileName) {
//        $parser = \Iris\SysConfig\IniParser::ParserBuilder('ini');
//        $configs = $parser->processFile($fileName, \FALSE, \Iris\SysConfig\_Parser::NO_INHERITANCE);
//        /* @var $config0 \Iris\SysConfig\Config */
//        $config0 = array_shift($configs);
//        $formName = $config0->formname;
//        if ($formName !== \NULL) {
//            $this->_form->changeFormName($formName);
//        }
//        $this->_elements = $configs;
//    }

    public function formRender() {
        ('die fr');
        return parent::formRender();
    }

//    protected function _insertElements() {die('i_em');
//        /* @var $element \Iris\SysConfig\Config */
//        foreach ($this->_elements as $name => $config) {
//            echo($name) . '*';
//            switch ($config->type) {
//                case 'hidden':
//                    $element = $this->_formFactory->createHidden($name);
//                    break;
//
//                default:
//                    $element = $this->_formFactory->createText($name);
//            }
//            $this->_form->addElement($element);
//        }
//@todo SUBMIT
//        $this->_form->addElement($this->getSubmitButton());
//    }

    public function parseSection($section) {
        $type = '';
        $label = '';
        $title = '';
    }

    protected function _insertElements() {
        ElementMaker::Prepare($this->_form);
        foreach ($this->_fieldOrder as $position => $fieldName) {
            $field = $this->_fieldList[$fieldName];
            $field->addElement();
        }
    }


}
