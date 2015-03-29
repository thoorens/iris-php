<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * The abstract class for all the form factories, grouping all
 * common facilities.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _FormFactory {

    /**
     * The library in which find the elements
     *
     * @var string
     */
    protected static $_Library = null;

    /**
     * The default form factory
     *
     * @var _FormFactory
     */
    protected static $_DefaultFormFactory = NULL;

    public static function __classInit() {
        $formClassName = get_called_class();
        $nameChunks = explode('\\', $formClassName);
        array_pop($nameChunks);
        static::$_Library = implode('\\', $nameChunks);
    }

    /**
     * The current form class becomes the default one
     */
    public function setDefault() {
        self::$_DefaultFormFactory = $this;
    }

    /**
     *
     * @return _FormFactory
     */
    public static function GetDefaultFormFactory() {
        if (is_null(self::$_DefaultFormFactory)) {
            $defaultClassName = \Iris\SysConfig\Settings::GetDefaultFormClass();
            $ff = new $defaultClassName();
            $ff->setDefault();
        }
        return self::$_DefaultFormFactory;
    }

    /**
     *
     * @param type $name
     * @return _Form
     */
    public abstract function createForm($name);

    /**
     *
     * @param string $name
     * @return Element
     */
    public function __call($function, $params) {
        // createElementtype
        if (strpos(strtolower($function), 'create') === 0) {
            $subtype = strtolower(substr($function, 6));
            $name = $params[0];
            $options = count($params) > 1 ? $params[1] : [];
            return $this->_createInput($name, $subtype, $options);
        }
        // validatorValidatortype
        if (strpos(strtolower($function), 'validator') === 0) {
            $type = substr($function, 9);
            return $this->_validator($type, $params);
        }
        iris_debug($function);
        throw new \Iris\Exceptions\NotSupportedException('Element type unknown');
    }

    protected function _validator($type, $params) {
        $class = $this->getClass("\\Validators\\$type");
        // in case $params has not enough elements
        $params[] = $params[] = $params[] = NULL;
        return new $class($params[0], $params[1], $params[2]);
    }

    /**
     *
     * @param string $name
     * @param string $subtype
     * @return \Iris\Forms\Elements\InputElement
     */
    protected function _createInput($name, $subtype, $options = []) {
        $class = $this->getClass('\\Elements\\InputElement');
        return new $class($name, $subtype, $options);
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createSubmit($name, $options = []){
        return $this->_createInput($name, 'submit', $options);
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\FileElement
     */
    public function createFile($name, $options = []) {
        $class = $this->getClass('\\Elements\\FileElement');
        return new $class($name, $options);
    }

    /**
     *
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\Checkbox
     */
    public function createCheckbox($name, $options = []) {
        $class = $this->getClass('\\Elements\\Checkbox');
        return new $class($name, $options);
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\ButtonGroup
     */
    public function createButtonGroup($name, $options = []) {
        $class = $this->getClass('\\Elements\\ButtonGroup');
        return new $class($name, 'Submit', $this, $options);
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\ButtonLinkGroup
     */
    public function createButtonLinkGroup($name, $options = []) {
        $class = $this->getClass('\\Elements\\ButtonLinkGroup');
        return new $class($name, 'Submit', $this, $options);
    }
    
    /**
     * 
     * @param type $name
     * @param type $key
     * @param type $options
     * @return \Iris\Forms\Elements\Link
     */
    public function createLink($name, $key, $options = []){
        $class = $this->getClass('\\Elements\\Link');
        return new $class($name,  $key, $this, $options);
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\RadioButton
     */
    public function createRadioButton($name, $options = []) {
        $class = $this->getClass('\\Elements\\RadioButton');
        return new $class($name, $options);
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\SelectElement
     */
    public function createSelect($name, $options = []) {
        $className = $this->getClass('\\Elements\\SelectElement');
        $class = new $className($name, 'Select', $this, $options);
        return $class;
    }

    /**
     * Creates a text area
     * 
     * @param string $name Name of the element
     * @param array $options Array of optons
     * 
     * @return \Iris\Forms\Elements\AreaElement
     */
    public function createArea($name, $options = []) {
        $class = $this->getClass('\\Elements\\AreaElement');
        return new $class($name, $options);
    }

    /**
     * 
     * @param type $name
     * @param type $form
     * @return \Iris\Forms\Elements\FieldSet
     */
    public function createFieldSet($name, $form) {
        $class = $this->getClass('\\Elements\\FieldSet');
        return new $class($name, $form);
    }

    /**
     * 
     * @param type $name
     * @return \Iris\Forms\Elements\DoublePassword
     */
    public function createDoublePassword($name) {
        $class = $this->getClass('\\Elements\\DoublePassword');
        return new $class($name, $this);
    }

    /**
     *
     * @param type $name
     * @return \Iris\Forms\Elements\MultiCheckbox
     */
    public function createMultiCheckbox($name, $options = []) {
        $class = $this->getClass('\\Elements\\MultiCheckbox');
        $multi = new $class($name, 'MultiCheckbox', $this, $options);
        return $multi;
    }

    /**
     * 
     * @param type $name
     * @return \Iris\Forms\Elements\Option
     */
    public function createOption($name) {
        $class = $this->getClass('\\Elements\\Option');
        return new $class($name, '');
    }

    /**
     * Creates an element of type Text
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createText($name, $options = []){
        return $this->_createInput($name, 'text', $options);
    }
    
    /**
     * Creates an element of type Hidden
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createHidden($name, $options = []){
        return $this->_createInput($name, 'hidden', $options);
    }
    
    /**
     * Creates an element of type Date
     * 
     * @param type $name
     * @param type $options
     * @return _Element
     */
    public function createDate($name, $options = []){
        return $this->_createInput($name, 'date', $options);
    }
    
    /**
     * Creates an element of type Time
     * 
     * @param type $name
     * @param type $options
     * @return _Element
     */
    public function createTime($name, $options = []){
        return $this->_createInput($name, 'time', $options);
        
    }
    
    
    /**
     * Creates an element of type Radio group
     * 
     * @param type $name
     * @return \Iris\Forms\Elements\RadioGroup
     */
    public function createRadioGroup($name, $options = []) {
        $class = $this->getClass('\\Elements\\RadioGroup');
        $group = new $class($name, 'div', $this, $options);
        return $group;
    }

    /**
     * Returns the complete name of the required class, found 
     * in a special library if it exists (ex. in Dojo)
     * or in Iris/Forms (uses the current FormFactory location).
     * 
     * @param type $qualifiedName
     * @return string
     */
    protected function getClass($qualifiedName) {
        $class = static::$_Library . $qualifiedName;
        $path = IRIS_ROOT_PATH . '/library/' . str_replace('\\', '/', $class) . ".php";
        if (file_exists($path)) {
            return $class;
        }
        else {
            return '\\Iris\\Forms' . $qualifiedName;
        }
    }

}

