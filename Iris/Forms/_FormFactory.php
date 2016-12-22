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

    public static function __ClassInit() {
        $formClassName = get_called_class();
        $nameChunks = explode('\\', $formClassName);
        array_pop($nameChunks);
        static::$_Library = implode('\\', $nameChunks);
    }

    /**
     *
     * @return _FormFactory
     */
    public static function GetDefaultFormFactory() {
        $defaultClassName = \Iris\SysConfig\Settings::$DefaultFormClass;
        /* @var $ff _FormFactory */
        $ff = new $defaultClassName();
        return new $defaultClassName();
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
            $name = substr($function, 9);
            return $this->_validator($name, $params);
        }
        throw new \Iris\Exceptions\NotSupportedException("Element type unknown $function");
    }

    /**
     * 
     * @param type $type
     * @param type $params
     * @return \Iris\Forms\class
     */
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
    public function createSubmit($name, $options = []) {
        //print('html submit<br>');
        return $this->_createInput($name, 'submit', $options);
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @throws Iris\Exceptions\FormException
     */
    public function createReset($name, $options = []){
        throw new Iris\Exceptions\FormException('Reset not yet implemented');
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @throws Iris\Exceptions\FormException
     */
    public function createButton($name, $options = []){
        throw new Iris\Exceptions\FormException('Button not yet implemented');
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createDateTime($name, $options = []) {
        if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Datetime not yet implemented');
         }
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
     public function createDateTimeLocal($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('DatetimeLocal not yet implemented');
         }
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createWeek($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Week not yet implemented');
         }
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createUrll($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Url not yet implemented');
         }
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createTel($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Tel not yet implemented');
         }
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createSearch($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Search not yet implemented');
         }
    }

    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createRange($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Range not yet implemented');
         }
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createNumber($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Number not yet implemented');
         }
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createMonth($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Month not yet implemented');
         }
    }
    
    /**
     * 
     * @param type $name
     * @param type $options
     * @return type
     * @throws Iris\Exceptions\FormException
     */
    public function createEmail($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Email not yet implemented');
         }
        
    }
    
    public function createColor($name, $options = []) {
         if($this->_verifyHTMLFive('1')){
             return $this->createText($name, $options);
         }
         else{
             throw new Iris\Exceptions\FormException('Color not yet implemented');
         }
    }

    protected function _verifyHTMLFive($Exclude){
        return \FALSE;
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
    public function createLink($name, $key, $options = []) {
        $class = $this->getClass('\\Elements\\Link');
        return new $class($name, $key, $this, $options);
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
    public function createText($name, $options = []) {
        return $this->_createInput($name, 'text', $options);
    }

    /**
     * Creates an element of type Password
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createPassword($name, $options = []) {
        return $this->_createInput($name, 'password', $options);
    }
    
    
    /**
     * Creates an element of type Hidden
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createHidden($name, $options = []) {
        return $this->_createInput($name, 'hidden', $options);
    }

    /**
     * Creates an element of type Date
     * 
     * @param type $name
     * @param type $options
     * @return _Element
     */
    public function createDate($name, $options = []) {
        // HTML 5
        return $this->_createInput($name, 'date', $options);
    }

    
    /**
     * Creates an element of type Time
     * 
     * @param type $name
     * @param type $options
     * @return _Element
     */
    public function createTime($name, $options = []) {
        // HTML 5
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
