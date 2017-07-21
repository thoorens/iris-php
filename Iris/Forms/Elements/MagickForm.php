<?php

namespace Iris\Forms\Elements;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Creates a form automatically
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @deprecated since version 1.0 RC
 */
class MagickForm extends Form {

    protected $_mode = 'AB';
    protected $_fullAuto = \FALSE;

    /**
     * @var \Iris\DB\_Entity
     */
    protected $_entity;
    protected $_formFactory;

    /**
     * the name pf the file from which to get the form parameters
     * @var string 
     */
    protected $_fileName = \NULL;

    /**
     *
     * @var string[] the submit label and name
     */
    protected $_submitLabel = ['Validate', 'send'];
    /**
     *
     * @var 
     */
    protected $_fileParams;

    /**
     * 
     * @param \Iris\DB\_Entity $entity
     */
//    public function __construct($name, $action = \NULL, $method = 'post') {
//        parent::__construct($name, $action, $method);
//    }


    public function setMode($mode) {
        $this->_mode = $mode;
    }

    public function addToMode($mode) {
        $this->_mode .= $mode;
    }

    /**
     * Sets the description file name of the form
     *  
     * @param string $fileName
     */
    public function setFileName($fileName) {
        $this->_fileName = $fileName;
    }

    /**
     * 
     */
    public function prepare() {
        $this->_decode();
//        foreach ($this->_entity->getMetadata() as $field) {
//            if (!isset($this->_components[$field->getFieldName()])) {
//                $this->_addAutoField($field);
//            }
//        }
        $this->_prepareSubmit();
    }

    /**
     * Adds a submit button, with the convenient label and name
     */
    protected function _prepareSubmit() {
        if (!$this->getFormFactory()->getHasSubmit()) {
            $name = $this->_submitLabel[1];
            $this->_formFactory->createSubmit($name)
                    ->setValue($this->_submitLabel[0])
                    ->addTo($this);
        }
    }

    /**
     * 
     * @param \Iris\DB\MetaItem $field
     */
    protected function _addAutoField($field) {
        $this->_formFactory->createText($field->getFieldName())->addTo($this);
        return $this;
    }

    public function setEntity($entity) {
        $this->_entity = $entity;
        return $this;
    }

    public function render() {
        $this->prepare();
        return parent::render();
    }

    /**
     * Change the default submit label (and optionally name)
     * 
     * @param type $submitLabel label for the submit button
     * @param type $submitName name of the submit button
     * @return \Iris\Forms\Elements\MagickForm
     */
    public function setSubmitMessage($submitLabel, $submitName = \NULL) {
        if (isset($this->_components[$submitName])) {
            $this->_components[$submitName]->setName($submitName);
            $this->_components[$submitName]->setValue($submitLabel);
            $this->_submitLabel = [];
        }
        else {
            $this->_submitLabel[0] = $submitLabel;
            if ($submitName != \NULL) {
                $this->_submitLabel[1] = $submitName;
            }
        }
        return $this;
    }

    public function _decode() {
        $this->_readFile();
        foreach (str_split($this->_mode) as $char) {
            //print $char . '<br/>';
        }
        foreach ($this->_entity->getMetadata()->getFields() as $key => $item) {
            $this->_treatItem($key, $item);
        }
    }

    /**
     * 
     * @param string $itemName
     * @param \Iris\DB\MetaItem $metaItem
     */
    protected function _treatItem($itemName, $metaItem) {
        $type = $metaItem->getType();
        $openPar = strpos($type, '(');
        /* @var $fileData /Iris/\Iris\SysConfig\Config */
        if (isset($this->_fileParams[$itemName])) {
            $fileItem = $this->_fileParams[$itemName];
        }
        else {
            $fileItem = $this->_fileParams['?'];
        }
        $number = 0;
        if ($openPar === \FALSE) {
            $shortType = $type;
        }
        else {
            // extract value between (..)
            $shortType = substr($type, 0, $openPar);
            $closePar = strpos($type, ')');
            $number = substr($type, $openPar + 1, $closePar - $openPar - 1);
        }
        $defType = $this->_getParam($itemName, $metaItem, 'type');
        if ($defType != '!') {
            $shortType = $defType;
        }
        /* @var $element \Iris\Forms\_Element */
        $element = \NULL;
        //print $shortType . '<br/>';
        switch ($shortType) {
            case 'text':
                $element = $this->_formFactory->createText($itemName);
                break;
            case 'datetime':
            case 'date':
                $element = $this->_formFactory->createDate($itemName);
                break;
            case 'time':
                $element = $this->_formFactory->createTime($itemName);
                break;
            case 'int':
                if ($number == 1) {
                    $element = $this->_formFactory->createCheckbox($itemName);
                }
                else {
                    $element = $this->_formFactory->createText($itemName);
                }
                break;
            case 'tinyint':
                break;
            case 'password':
                $element = $this->_formFactory->createPassword($itemName);
                break;
            case 'text':
                break;
            case 'text':
                break;
            case 'text':
                break;
        }
        if ($element != \NULL) {
            $element->addTo($this);
            $this->_addLabel($element, $itemName, $metaItem);
            $this->_addTitle($element, $itemName, $metaItem);
        }
    }

    /**
     * 
     * @param type $elementName
     * @param \Iris\DB\MetaItem $metaItem
     * @param type $property
     * @return string
     */
    protected function _getParam($elementName, $metaItem, $property) {
        if (isset($this->_fileParams[$elementName])) {
            $fileItem = $this->_fileParams[$elementName];
        }
        else {
            $fileItem = $this->_fileParams['?'];
        }
        $value = $fileItem->$property;
        //print $property . ':'. $value .'<br/>';
        if ($value == '@') {
            $value = $metaItem->get($property, '');
        }
        if ($value == '') {
            $value = '!';
        }
        return $value;
    }
    
    /**
     * Parses the parameter file if it is defined and exists
     */
    protected function _readFile() {
        $filePath = $this->_fileName;
        if (file_exists($filePath) or $filePath != \NULL) {
            $parser = \Iris\SysConfig\_Parser::ParserBuilder('ini');
            $this->_fileParams = $parser->processFile($filePath, FALSE, \Iris\SysConfig\_Parser::NO_INHERITANCE);
        }
        else {
            $this->_fileParams['?'] = $this->_emptyConfig();
        }
    }

    protected function _emptyConfig() {
        $config = new \Iris\SysConfig\Config('?');
        $config->type = '@';
        $config->label = '';
        $config->title = '';
        //        $config-> ='';
        //        $config-> ='';
        //        $config-> ='';
        return $config;
    }

    public function _addLabel($element, $itemName, $metaItem) {
        $label = $this->_getParam($itemName, $metaItem, 'label');
        if ($label == '!') {
            $label = $itemName . ':';
        }
        $element->setLabel($label);
    }

    /**
     * 
     * @param \Iris\Forms\_Element $element
     * @param type $itemName
     * @param type $metaItem
     */
    public function _addTitle($element, $itemName, $metaItem) {
        $title = $this->_getParam($itemName, $metaItem, 'title');
        if ($title != '!') {
            $element->setTitle($title);
        }
    }

}
