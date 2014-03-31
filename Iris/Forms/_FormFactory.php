<?php

namespace Iris\Forms;

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
            $options = count($params) > 1 ? $params[1] : array();
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
     * @return Element
     */
    protected function _createInput($name, $subtype, $options = array()) {
        $class = $this->getClass('\\Elements\\InputElement');
        return new $class($name, $subtype, $options);
    }

    public function createFile($name, $options = array()) {
        $class = $this->getClass('\\Elements\\FileElement');
        return new $class($name, $options);
    }

    /**
     *
     * @param type $name
     * @param type $options
     * @return Elements\Checkbox
     */
    public function createCheckbox($name, $options = array()) {
        $class = $this->getClass('\\Elements\\Checkbox');
        return new $class($name, $options);
    }

    public function createButtonGroup($name, $options = array()) {
        $class = $this->getClass('\\Elements\\ButtonGroup');
        return new $class($name, 'Submit', $this, $options);
    }

    public function createButtonLinkGroup($name, $options = array()) {
        $class = $this->getClass('\\Elements\\ButtonLinkGroup');
        return new $class($name, 'Submit', $this, $options);
    }
    
    public function createLink($name, $key, $options = array()){
        $class = $this->getClass('\\Elements\\Link');
        return new $class($name,  $key, $this, $options);
    }
    
    public function createRadioButton($name, $options = array()) {
        $class = $this->getClass('\\Elements\\RadioButton');
        return new $class($name, $options);
    }

    public function createSelect($name, $options = array()) {
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
    public function createArea($name, $options = array()) {
        $class = $this->getClass('\\Elements\\AreaElement');
        return new $class($name, $options);
    }

    public function createFieldSet($name, $form) {
        $class = $this->getClass('\\Elements\\FieldSet');
        return new $class($name, $form);
    }

    public function createDoublePassword($name) {
        $class = $this->getClass('\\Elements\\DoublePassword');
        return new $class($name, $this);
    }

    /**
     *
     * @param type $name
     * @return Elements\MultiCheckbox
     */
    public function createMultiCheckbox($name, $options = array()) {
        $class = $this->getClass('\\Elements\\MultiCheckbox');
        $multi = new $class($name, 'MultiCheckbox', $this, $options);
        return $multi;
    }

    public function createOption($name) {
        $class = $this->getClass('\\Elements\\Option');
        return new $class($name, '');
    }

    /**
     *
     * @param type $name
     * @return \Iris\Forms\Elements\RadioGroup
     */
    public function createRadioGroup($name, $options = array()) {
        $class = $this->getClass('\\Elements\\RadioGroup');
        $group = new $class($name, 'div', $this, $options);
        return $group;
    }

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

