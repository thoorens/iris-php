<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
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
    protected static $_Library = NULL;

    /**
     *
     * @var boolean
     */
    protected $_serverValidation = \FALSE;

    /**
     * At class loading, stores the class name in $_Library
     */
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
     * @param string $name The name of the form
     * @return _Form
     */
    public abstract function createForm($name);

    /**
     * 
     * @param type $function
     * @param type $params
     * @return _Element
     * @throws \Iris\Exceptions\NotSupportedException
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
     * @param string $type
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
     * Creates an element of type input
     * 
     * @param string $name The name of the element
     * @param string $subtype The subtype of the element
     * @return \Iris\Forms\Elements\InputElement
     */
    protected function _createInput($name, $subtype, $options = []) {
        $class = $this->getClass('\\Elements\\InputElement');
        return new $class($name, $subtype, $options);
    }

    /**
     * Creates an element of type Submit
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createSubmit($name, $options = []) {
        return $this->_createInput($name, 'submit', $options);
    }

    /**
     * Creates an element of type  Reset
     * @param string $name The name of th element
     * @param mixed $options Optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createReset($name, $options = []) {
        return $this->_createInput($name, 'reset', $options);
    }

    /**
     * Creates an element of type  Button
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @throws \Iris\Exceptions\FormException
     */
    public function createButton($name, $options = []) {
        throw new \Iris\Exceptions\FormException('Button not yet implemented');
    }

    /**
     * Creates an element of type file
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\FileElement
     */
    public function createFile($name, $options = []) {
        $class = $this->getClass('\\Elements\\FileElement');
        return new $class($name, $options);
    }

    /**
     * Creates an element of type checkbox
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\Checkbox
     */
    public function createCheckbox($name, $options = []) {
        $class = $this->getClass('\\Elements\\Checkbox');
        return new $class($name, $options);
    }

    /**
     * Creates an element of type ButtonGroup
     * 
     * @param string $name The name of the group
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\ButtonGroup
     */
    public function createButtonGroup($name, $options = []) {
        $class = $this->getClass('\\Elements\\ButtonGroup');
        return new $class($name, 'Submit', $this, $options);
    }

    /**
     * Creates an element of type ButtonLinkGroup
     * 
     * @param string $name The name of the group
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\ButtonLinkGroup
     */
    public function createButtonLinkGroup($name, $options = []) {
        $class = $this->getClass('\\Elements\\ButtonLinkGroup');
        return new $class($name, 'Submit', $this, $options);
    }

    /**
     * Creates an element of type Link
     * 
     * @param string $name The name of the element
     * @param string $key
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\Link
     */
    public function createLink($name, $key, $options = []) {
        $class = $this->getClass('\\Elements\\Link');
        return new $class($name, $key, $this, $options);
    }

    /**
     * Creates an element of type RadioButton
     * 
     * @param string $name The name of the element
     * @param string $options
     * @return \Iris\Forms\Elements\RadioButton
     */
    public function createRadioButton($name, $options = []) {
        $class = $this->getClass('\\Elements\\RadioButton');
        return new $class($name, $options);
    }

    /**
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
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
     * @param array $options Array of options
     * 
     * @return \Iris\Forms\Elements\AreaElement
     */
    public function createArea($name, $options = []) {
        $class = $this->getClass('\\Elements\\AreaElement');
        return new $class($name, $options);
    }

    /**
     * 
     * @param string $name The name of the group
     * @param type $form
     * @return \Iris\Forms\Elements\FieldSet
     */
    public function createFieldSet($name, $form) {
        $class = $this->getClass('\\Elements\\FieldSet');
        return new $class($name, $form);
    }

    /**
     * Creates an element of type DoublePassword
     * 
     * @param string $name
     * @return \Iris\Forms\Elements\DoublePassword
     */
    public function createDoublePassword($name) {
        $class = $this->getClass('\\Elements\\DoublePassword');
        return new $class($name, $this);
    }

    /**
     * Creates an element of type Multicheckbox
     * 
     * @param string $name The name of the group
     * @return \Iris\Forms\Elements\MultiCheckbox
     */
    public function createMultiCheckbox($name, $options = []) {
        $class = $this->getClass('\\Elements\\MultiCheckbox');
        $multi = new $class($name, 'MultiCheckbox', $this, $options);
        return $multi;
    }

    /**
     * Creates an element of type Option
     * 
     * @param string $name The name of the element
     * @return \Iris\Forms\Elements\Option
     */
    public function createOption($name) {
        $class = $this->getClass('\\Elements\\Option');
        return new $class($name, '');
    }

    /**
     * Creates an element of type Text
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createText($name, $options = []) {
        return $this->_createInput($name, 'text', $options);
    }

    /**
     * Creates an element of type Password
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createPassword($name, $options = []) {
        return $this->_createInput($name, 'password', $options);
    }

    /**
     * Creates an element of type Hidden
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createHidden($name, $options = []) {
        return $this->_createInput($name, 'hidden', $options);
    }

    /**
     * Creates an element of type Radio group
     * 
     * @param string $name
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
     * @param string $qualifiedName
     * @return string
     */
    protected function getClass($qualifiedName) {
        $class = static::$_Library . $qualifiedName;
        $path = IRIS_ROOT_PATH . '/library/' . str_replace('\\', '/', $class) . ".php";
        if (!file_exists($path)) {
            $class = '\\Iris\\Forms' . $qualifiedName;
        }
        return $class;
    }

    /*
     * 
     * SPECIAL ELEMENTS FOR HTML5
     * 
     * 
     */

    /**
     * Creates an element of type  input (subtype datetime
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createDateTime($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];
        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'datetime', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\DateTime());
        }
        return $element;
    }

    /**
     * Creates an element of type Date
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return _Element
     */
    public function createDate($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];
        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'date', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Date());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype  )
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createDateTimeLocal($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];
        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'datetime-local', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\DateTime());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype week )
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createWeek($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'week', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Week());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype URL )
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createUrl($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'url', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\URL());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Tel )
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createTel($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'tel', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Tel());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Search)
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createSearch($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'search', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Search());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Range)
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createRange($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'range', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Search());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Number)
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createNumber($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'number', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Number());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Month)
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createMonth($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'month', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Number());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Email)
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createEmail($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'email', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Email());
        }
        return $element;
    }

    /**
     * Creates an element of type input (subtype Color)
     * 
     * @param type $name
     * @param type $options
     * @return \Iris\Forms\Elements\InputElement
     * @throws Iris\Exceptions\FormException
     */
    public function createColor($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];

        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'color', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Color());
        }
        return $element;
    }

    /**
     * Creates an element of type Time
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return _Element
     */
    public function createTime($name, $options = []) {
        $rules = [
            'Trident' => '',
            'Edge' => '',
            'Gecko' => '',
            'WebKit' => '',
            'Presto' => '',
                // 'Blink' => '',
        ];
        if ($this->_inHTML5([])) {
            $element = $this->_createInput($name, 'time', $options);
        }
        else {
            $element = $this->_createInput($name, 'text', $options);
        }
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Color());
        }
        return $element;
    }

    public function _inHTML5($rules) {
        list($engine, $version) = \Iris\System\Browser::GetBrowser();
        $engineNames = [
            'Presto', // In old Opera
            // 'Blink' in Chrome (no information)
            'Edge',
            'WebKit',
            'Gecko',
            'Trident',
        ];
        while (count($rules) < count($engineNames)) {
            $rules[] = -1;
        }
        $namedRules = array_combine($engineNames, $rules);
        return $namedRules[$engine] >= $version ? \FALSE : \TRUE;
    }

}
