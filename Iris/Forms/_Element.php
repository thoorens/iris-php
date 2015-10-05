<?php

namespace Iris\Forms;

use Iris\Forms\Validators as iv;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An abstract class common for all elements and grouping 
 * all shared facilities
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Element implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    const NONE = 0;
    const BEFORE = 1;
    const AFTER = 2;
    const BOTH = 3;
    const MIDDLE = 4;
    const INNER = 8;

    /**
     * Indicate that tag has structure <tag attribues />
     * @var bolean 
     */
    protected static $_EndTag = \FALSE;

    /**
     *
     * @var string
     */
    protected $_type;

    /**
     *
     * @var string
     */
    protected $_subtype = '';

    /**
     *
     * @var int
     */
    protected $_labelPosition = 0;

    /**
     *
     * @var string
     */
    protected $_attributes = [];

    /**
     *
     * @var mixed
     */
    protected $_label = \NULL;

    /**
     *
     * @var string
     */
    protected $_name = \NULL;

    /**
     *
     * @var mixed
     */
    protected $_value = '';

    /**
     *
     * @var string
     */
    protected $_errorMessage = '';

    /**
     *
     * @var boolean 
     */
    protected $_canDisable;

    /**
     *
     * @var _Form 
     */
    protected $_container = \NULL;

    /**
     *
     * @var iv\_Validator 
     */
    protected $_validator = \NULL;

    /**
     * TRUE if the element can be checked
     * 
     * @var boolean 
     */
    protected $_checkable = \FALSE;

    /**
     * Value can be rendered as an attribute (by default NOT)
     * 
     * @var boolean
     */
    protected $_valueAsAttribute = \FALSE;
    
    /**
     * For input file, indicate max file size
     * @var int
     */
    protected $_fileSize = 0;

    /**
     * In mother class, options are a simple text to be inserted
     * in the main tag (special attributes)
     * 
     * @var string
     */
    protected $_options = [];

    /**
     * A class to put on each part of the render code
     * 
     * @var string
     */
    protected $_globalClass = \NULL;

    /**
     *
     * @param string $name name of the widget
     * @param string $type type of the widget
     * @param string[] $options options for special elements 
     */
    public function __construct($name, $type, $options = []) {
        $this->_type = $type;
        $this->_labelPosition = self::BEFORE;
        $this->setName($name);
        $this->_canDisable = \TRUE;
        $this->_options = $options;
    }

    /**
     * getter for the widget type
     * @return string 
     */
    protected function _getType() {
        return $this->_type;
    }

    /**
     * This magic methods permits to use setXXXXX() to define an
     * attribute of the element.
     * 
     * @param type $name
     * @param type $arguments
     * @return \Iris\Forms\_Element
     * @throws \Iris\Exceptions\NotSupportedException
     */
    public function __call($name, $arguments) {
        if (strpos($name, 'set') === 0) {
            $this->__set(substr($name, 3), $arguments[0]);
            return $this;
        }
        else {
            throw new \Iris\Exceptions\NotSupportedException("Illegal method call : $name");
        }
    }

    /**
     * Magic method for getting an attribute value
     * @param string $name
     * @return mixed 
     */
    public function __get($name) {
        $name = lcfirst($name);
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        else {
            return '';
        }
    }

    /**
     * Magic method for setting an attribute value
     * @param string $name
     * @param mixed $value 
     */
    public function __set($name, $value) {
        $name = lcfirst($name);
        $this->_attributes[$name] = $value;
        return $this;
    }

    /**
     * Magic method for testing the existence of an attribute
     * @param string $name
     * @return boolean
     */
    public function __isset($name) {
        return isset($this->_attributes[$name]);
    }

    public function setDisabled($value) {
        if ($value === \TRUE) {
            $value = 'disabled';
        }
        $this->$value = $value;
        return $this;
    }

    /**
     * Sets a classname common to all parts of the rendering
     * @param string $className
     */
    public function setGlobalClass($className) {
        $this->_globalClass = $className;
        return $this;
    }

    private function _getGlobalClass() {
        if ($this->_globalClass === \NULL) {
            return '';
        }
        else {
            return ' class="' . $this->_globalClass . '"';
        }
    }

    /* ----------------------------------------------------------------------
     * Rendering 
     */

    /**
     * the precise rendering is composed by <ul>
     * <li> a label
     * <li> a tag (and commons attributes)
     * <li> the closing of the tag
     * <li> a label (only one actually) </ul>
     * 
     * @return string
     */
    function render($layout = \NULL) {
        // normal element have their layout set
        // companion elements need to receive one
        if ($layout === \NULL) {
            $layout = $this->getLayout();
        }
        // maybe layout will be pleased to know what kind of element it is 
        // decorating
        $layout->setCurrentElement($this);
        $text = '';
        $global = $this->_getGlobalClass();
        $text .= $layout->initialSeparator("id=\"$this->_name-label\" $global");
        $text .= $this->_outerLabel();
        $text .= $layout->innerSeparator();
        $text .= $this->_renderError();
        $text .= $this->baseRender();
        $text .= $layout->finalSeparator();
        if ($this->_fileSize > 0) {
            $text .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$this->_fileSize\" />\n";
        }
        return $text;
    }

    public function baseRender($dummy = \NULL) {
        $text = '';
        $text .= $this->_innerLabel(self::BEFORE);
        $global = $this->_getGlobalClass();
        $text .= "\t<$this->_type $global";
        if ($this->_subtype != '') {
            $text .= " type=\"$this->_subtype\" ";
        }
        $text .= $this->_renderName();
        $text .= $this->_renderAttributes();
        $text .= $this->_renderOptions();
        if (static::$_EndTag) {
            $text .= ">\n";
            $text .= $this->_renderComponents() . "\n";
            $text .= "\t</$this->_type>\n";
        }
        else {
            $text .= $this->_renderValue();
            $text .= ">\n";
        }
        $text .= $this->_innerLabel(self::AFTER);
        return $text;
    }

    /**
     * Display all attributes
     * 
     * @return string 
     */
    protected function _renderAttributes() {
        $text = '';
        foreach ($this->_attributes as $key => $value) {
            if (($key != 'value' or $this->_valueAsAttribute)) {
                $text .= " $key = \"$value\" ";
            }
        }
        if($this->_valueAsAttribute){
            $text .= ' value = "'.$this->getValue().'" ';
        }
        return $text;
    }

    /**
     * Some options may be added to an element. They are placed in the
     * opening tag.
     * 
     * @return string
     */
    protected function _renderOptions() {
        $text = '';
        foreach ([] as $key => $option) {
            $text .= $key . ' = "' . $option . '" ';
        }
        return $text;
    }

    /**
     * In case of error not detected by Javascript, the server fills a special
     * paragraph with an error message.
     * 
     * @return string (html)
     */
    protected function _renderError() {
        if ($this->_errorMessage === '') {
            return '';
        }
        return "<p class=\"error_validate\">$this->_errorMessage</p>";
    }

    /**
     * The name is rendered through two attributes: name and id
     * 
     * @return string (html)
     */
    protected function _renderName() {
        $name = $this->getName();
        if ($name === \NULL) {
            return '';
        }
        else {
            return " name=\"$name\" id=\"$name\" ";
        }
    }

    /**
     * The value is rendered from the stored value, optionally modified
     * by a validator method.
     * 
     * @return string (html) 
     */
    protected function _renderValue() {
        $value = $this->getValue();
        $checkMark = '';
        if ($this->_validator !== \NULL) {
            $value = $this->_validator->prepareValue($value);
        }
        if ($this->_checkable) {
            // $value may be boolean or numeric, so do not compare with === 1
            if ($value) {
                $checkMark = ' checked = "checked" ';
            }
        }
        $html = "value = \"$value\" $checkMark";
        return $html;
    }

    

    /**
     * Renders a label (or nothing) according to the position
     * 
     * @param int $position
     * @param boolean $inner
     * @return string
     */
    protected function _renderLabel($position, $inner) {
        // Treating inner labels
        if ($this->_labelPosition & self::INNER) {
            if ($inner and $position & $this->_labelPosition) {
                return $this->_prepareLabel($position);
            }
            else {
                return '';
            }
        }
        if ($inner) {
            return '';
        }
        else {
            return $this->_prepareLabel($position);
        }
    }

    /**
     * Returns the html for a label (if no label provided, uses field name)
     * A "for" attribute is added for simple elements.
     * 
     * @param int $position
     * @return string 
     */
    protected function _prepareLabel($position) {
        $name = $this->getName();
        $label = $this->getLabel();
        if ($label === '') {
            $label = "<i>$name</i>:";
        }
        $text = '';
        if ($position & $this->_labelPosition) {
            $text .= "\t<label";
            if ($this instanceof Elements\_ElementGroup) {
                $text .= ">";
            }
            else {
                $text .=" for=\"$name\">";
            }
            $text .= "$label</label>\n";
        }
        return $text;
    }

    protected function _innerLabel($position) {
        return $this->_renderLabel($position, \TRUE);
    }

    protected function _outerLabel() {
        return $this->_renderLabel(self::BEFORE, \FALSE);
    }

    /**
     * To be overwritten if necessary
     */
    protected function _renderComponents() {
        return $this->getValue() . "\n";
    }

    /* ----------------------------------------------------------------------
     * Validation
     */

    /**
     * Add a validator to an element. The validator can be an object or a
     * name (which requires the element to be registred).
     * Caution, the object will be chained with other validators. In most
     * cases, it is safer to use a new object (the use of the name will create one)
     * 
     * 
     * @param mixed $validator a validator or a validator name
     * @return _Element (fluent interface) 
     */
    public function addValidator($validator) {
        if (!$validator instanceof iv\_Validator) {
            if ($this->_container === \NULL) {
                throw new \Iris\Exceptions\FormException(
                $this->_('named validators only operate on objects registred with addTo().', \TRUE));
            }
            $creator = "validator$validator";
            $ff = $this->getFormFactory();
            $validator = $ff->$creator();
        }
        return $this->_addValidator($validator);
    }

    /**
     *
     * @param iv\_Validator $validator
     * @return _Element (fluent interface)
     */
    protected function _addValidator(iv\_Validator $validator) {
        $validator->setElement($this);
        if ($this->_validator === \NULL) {
            $this->_validator = $validator;
        }
        else {
            $this->_validator->addValidator($validator);
        }
        return $this;
    }

    public function validate() {
        if ($this->_validator === \NULL) {
            return \TRUE;
        }
        $val = $this->getValue();
        return $this->_validator->validate($this->getValue());
    }

    /**
     * Autoregister the element in a form
     * 
     * @param _Form $container 
     */
    public function addTo(iFormContainer $container) {
        $container->addElement($this);
        $this->_container = $container;
        return $this;
    }

    /* ----------------------------------------------------------------------
     * Getter and setter
     */

    public function canDisable() {
        return $this->_canDisable;
    }

    /**
     *
     * @return _Layout
     */
    public function getLayout() {
        return $this->_container->getLayout();
    }

    /**
     *
     * @return \Iris\Forms\_FormFactory 
     */
    public function getFormFactory() {
        return $this->_container->getFormFactory();
    }

    public function getValue() {
        return trim($this->_value);
    }

    public function setValue($value) {
        if($value instanceof \Iris\DB\Object)
            iris_debug($value);
        $value = str_replace('"', '&quot;', $value);
        $this->_value = $value;
        return $this;
    }

    /**
     * getter for the label
     * @return string 
     */
    public function getLabel($num = 0) {
        if (is_array($this->_label)) {
            return $this->_label[$num];
        }
        else {
            return $this->_label;
        }
    }

    /**
     * setter for the label
     * @param string $label
     * @return Element 
     */
    public function setLabel($label) {
        $this->_label = explode('|', $label . '|');
        return $this;
    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function getSubtype() {
        return $this->_subtype;
    }

    public function getType() {
        return $this->_type;
    }

    public function setError($message) {
        $this->_errorMessage = $message;
        return $this;
    }

    public function setContainer($container) {
        $this->_container = $container;
    }

    public function setLabelPosition($labelPosition) {
        $this->_labelPosition = $labelPosition;
        return $this;
    }

}

