<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * The common part of 3 form factory (Entity, HandMad and IniFile
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
abstract class _FormMaker {

    const AUTOFORMNAME = 'form';
    const MODE_NONE = 0;
    const MODE_HANDMADE = 1;
    const MODE_INI = 2;
    const MODE_ENTITY = 4;
    const PAIR_SEPARATOR = '|';
    const KEYVAlUE_SEPARATOR = '!';

    protected $_mode = self::MODE_NONE;
    protected $_subMode = self::MODE_NONE;
    protected $_sourceCounter = 0;
    protected $_submode = self::MODE_NONE;

    /**
     * The inner form
     * @var _Form
     */
    protected $_form;

    /**
     * The form factory to make the elements of the form
     * 
     * @var _FormFactory
     */
    protected $_formFactory;

    /**
     * The default type of factory
     * @var string 
     */
    protected $_factoryLocked = \FALSE;

    /**
     * Text in the submit button
     * @var string
     */
    protected $_submitText = 'Send';

    /**
     * The submit button 
     * @var _Element
     */
    protected $_submitButton = \NULL;
    
    
    protected $_explicitParams = [];

    /**
     *
     * @var string
     */
    protected $_formName;

    /**
     * The metadata inserted as primary or secondary source for the form
     * 
     * @var \Iris\DB\Metadata
     */
    protected $_metadata = \NULL;

    /**
     * The elements inserted as primary or secondary source for the form
     * indexed by element names
     * 
     * @var Makers\ElementMaker[]
     */
    protected $_fieldList = [];

    /**
     * A list of the elements inserted in $_fieldList in position order
     * 
     * @var string[]
     */
    protected $_fieldOrder = [];

    public abstract function __construct($parameter, $factoryType = \NULL, $formName = 'form');
    

    
    /**
     *
     * @var type 
     */
//    protected $_iniParse;

    //public abstract function __construct($parameter, $factoryType = \NULL, $formName = 'form');

    /**
     * The scanner for entities and metadata used by IniFile and HandMake makers
     * Entity has its own version
     *  
     * @param \Iris\DB\_Entity $entity
     */
    public function scanEntity($entity){
        die('To be developped later');
    }
    
    /**
     * The scanner for ini file used by Entity and HandMake makers
     * IniFile has its own version
     * 
     * @param type $parameter
     */
    public function scanIni($parameter){
        die('To be developped later');
    }
    
    /**
     * The scanner for hand made lists used by Entity and IniFile makers
     * HandMade has its own version
     * 
     * @param type $parameter
     */
    public function scanList($parameter){
        die('To be developped later');
    }
    
     /**
     * A simple alias for scanEntity
      * 
     * @param \Iris\DB\Metadata $metadata
     */
    public function scanMetadata($metadata) {
        $this->scanEntity($metadata);
    }
  
    /**
     * 
     * @param type $parameter
     * @return type
     * @throws \Iris\Exceptions\BadParameterException
     */
    protected function _getIniContent($parameter) {
        // if the parameter is an array, no analysis is necessary
        if (is_array($parameter)) {
            $parsedData = $parameter;
        }
        // otherwise the parameter is the filename
        elseif (is_string($parameter)) {
            // a simple file name need to be search in the special form folder
            if ($parameter[0] !== '/') {
                $filename = \Iris\SysConfig\Settings::GetCompleteFormFolder() . '/' . $parameter;
            }
            // otherwise take the filename as complete
            else {
                $filename = $parameter;
            }
            $parsedData = parse_ini_file($filename, TRUE, INI_SCANNER_RAW);
        }
        else {
            throw new \Iris\Exceptions\BadParameterException('Bad ini file content');
        }
        if (isset($parsedData['@']) and $this->_mode === self::MODE_INI) {
            $this->_explicitParams = $parsedData['@'];
            $this->_parseHeader();
            unset($parsedData['@']);
        }
        return $parsedData;
    }

    protected function _parseHeader() {
        // only functional in IniFile FormMaker
    }

    

    /**
     * Converts a string to an array of arrays using special separators
     * 
     * @param string $line
     * @return type
     */
    protected function _scanLine($line) {
        $pairs = explode(self::PAIR_SEPARATOR, $line);
        foreach ($pairs as $pair) {
            list($key, $value) = explode(self::KEYVAlUE_SEPARATOR, $pair);
            $features[$key] = $value;
        }
        return $features;
    }

   

    protected function _specifySource($newMode) {
        static $all = 0;
        if (($all & $newMode) == 0) {
            if ($this->_mode === self::MODE_NONE) {
                $this->_mode = $newMode;
            }
            elseif ($this->_subMode === self::MODE_NONE) {
                $this->_subMode = $newMode;
            }
            else {
                throw new \Iris\Exceptions\FormException('A form maker cannot have 3 sources.');
            }
        }
        else {
            throw new \Iris\Exceptions\FormException('A source type cannot be repeated in a form maker.');
        }
        $all |= $newMode;
    }


    /**
     * 
     * @param String $parameter
     * @return \Iris\Forms\_FormMaker
     */
    protected static function _AnalyseString($parameter) {
        // .ini in parameter
        if (basename($parameter) !== basename($parameter, '.ini')) {
            $formMaker = new Makers\IniFile($parameter);
        }
        else {
            $formMaker = new Makers\Entity($parameter);
        }
        return $formMaker;
    }

    /**
     * Accessor set for the internal form factory
     * 
     * @param _FormFactory $factory the form factory to use
     * @return \Iris\Forms\StandardFormFactory
     */

    /**
     * 
     * @param string $factoryType type of factory used to make the form (by default AUTO)
     */
    protected function _initFactory($factoryType, $lock = \TRUE) {
        if($factoryType ===\NULL){
            $factoryType =  _FormFactory::AUTO;
        }
        if (!$this->_factoryLocked) {
            switch ($factoryType) {
                case _FormFactory::AUTO:
                    $this->_formFactory = _FormFactory::GetFormFactory();
                    break;
                case _FormFactory::HTML:
                    $this->_formFactory = new \Iris\Forms\StandardFormFactory();
                    break;
                case _FormFactory::DOJO:
                    $this->_formFactory = new \Dojo\Forms\FormFactory();
                    break;
            }
            $this->_factoryLocked = $lock;
        }
    }

    /**
     * Accessor set for the internal form
     * 
     * @param mixed $form the form object or the form name
     * @return FormMaker for fluent interface
     */
//    public function setForm($form) {
//        if ($form instanceof Elements\Form) {
//            $this->_form = $form;
//        }
//        else {
//            $this->setFormName($form);
//        }
//        return $this;
//    }

    /**
     * Creates the form or changes the existing
     * @param mixed $formName
     */
    public function setFormName($formName) {
        $this->_formName = $formName;
        if ($this->_form !== \NULL) {
            $this->_form->changeFormName($formName);
        }
    }

    /**
     * Accessor get for the internal form factory
     * 
     * @return _FormFactory
     */
    public function getFormFactory() {
        //@todo remove test
        if(!$this->_factoryLocked){
            throw new \Iris\Exceptions\FormException('Access to a factory still unlocked');
        }
        return $this->_formFactory;
    }

    /**
     * Gets the form after inserting all the elements
     * 
     * @param booelan $treat if TRUE all the elements are inserted in the form
     * @return _Form
     */
    public function getForm($treat = \TRUE) {
        if ($this->_form === \NULL) {
            //$this->setFactory($this->_factoryType);
            $this->_form = $this->_formFactory->createForm($this->_formName);
            $this->_insertElements();
        }
        $form = $this->_form;
        return $form;
    }

    /**
     * Creates and renders the form
     * 
     * @return string
     */
    public function formRender() {
        return $this->getForm()->render();
    }

    /**
     * Changes the submit button text
     * 
     * @param string $text
     */
    public function setSubmitText($text) {
        $this->_submitText = $text;
        if ($this->_submitButton !== \NULL) {
            $this->_submitButton->setValue($text);
        }
    }

    /**
     * @todo SUBMIT
     */
    protected /* abstract */ function _insertElements() {
        die('Must be overwritten');
    }

//        print('IE');
//        $SMB = $this->getSubmitButton();
//        i_d($SMB);
//        $this->_form->addElement($this->getSubmitButton());
//    }

    /**
     * Export all unknown methods to the internal form
     * 
     * @param string $methodName the name of the method
     * @param mixed $arguments the argument as an array
     */
    public function __call($methodName, $arguments) {
        i_d($methodName);
        $form = $this->getForm();
        return $form->$methodName();
    }

    /* Detailed analysis */

    protected function _getParams($name, $default = \NULL) {
        $value = \NULL;
        if (isset($this->_explicitParams[$name])) {
            $value = $this->_explicitParams[$name];
        }
        elseif ($this->_metadata !== \NULL) {
            $value = '';
        }
        if ($value === \NULL) {
            $value = $default;
        }
        return $value;
    }

}
