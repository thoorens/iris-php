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
 * An abstract class common for all type of forms and grouping 
 * all shared facilities
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Form implements iFormContainer {

    /**
     * form name
     * @var string
     */
    protected $_name;

    /**
     * file to decode results
     * @var string
     */
    protected $_action;

    /**
     * method of value transmission (get or post)
     * @var string
     */
    protected $_method;

    /**
     * the direct components of the form (which may be iFormContainer)
     * 
     * @var array(_Element)
     */
    protected $_components = array();

    /**
     * These are elements whose value must be calculated from their components
     * value.
     * 
     * @var array(_Element)
     */
    protected $_aidedComponents = array();

    /**
     * the elements contained (directly or in container) by the form 
     * @var array(_Element)
     */
    protected $_registry = array();

    /**
     * the layout used to render the form and its elements
     * 
     * @var _FormLayout
     */
    protected $_layout = NULL;

    /**
     * the factory used to create the form (its methods can create appropriate
     * elements and validators)
     * 
     * @var _FormFactory
     */
    protected $_formFactory = NULL;

    /**
     * An associative array of facultative attributes
     * 
     * @var array
     */
    protected $_attributes = array();

    /**
     * A constructor for a file (never called directly by new, use
     * a form factory instead)
     * 
     * @param string $name
     * @param string $action
     * @param string $method 
     */
    public function __construct($name, $action=NULL, $method='post') {
        $this->_name = $name;
        if ($action == NULL) {
            $this->_action = \Iris\Engine\Superglobal::GetServer('REQUEST_URI');
        }
        $this->_method = $method;
    }

    /**
     * The form can uses all its factory methods directly
     * (specially element and validator builders)
     * 
     * @param type $name
     * @param type $arguments
     * @return _Form 
     */
    public function __call($name, $arguments) {
        return call_user_func_array(array($this->_formFactory, $name), $arguments);
    }

    /**
     * Renders a form with all the elements included
     * 
     * @return string the html code to display the form controllers and content
     */
    public function render() {
        $layout = $this->getLayout();
        $lines[] = $this->_formTag();
        $lines[] = $layout->getEntry(' class="main"');
        foreach ($this->_components as $element) {
            $lines[] = $element->render();
        }
        $lines[] = $layout->getExit();
        return implode("\n\t", $lines) . "\n</form>";
    }

    /**
     * Renders the form in "display" mode ie no possibility to enter
     * data
     * 
     * @return string the html code to display the form content
     */
    public function display() {
        $layout = $this->getLayout();
        $lines[] = '<div class="displayform">' . "\n";
        $lines[] = $layout->getEntry(' class="main"');
        foreach ($this->_components as $element) {
            $lines[] = $element->render();
        }
        return implode("\n\t", $lines) . "\n</div>";
    }

    /**
     * A substitute for render for debugging purpose
     * 
     * @return string 
     */
    public function show($doRender=TRUE) {
        if ($doRender) {
            $html = $this->render();
        }
        else {
            $html = $this->display();
        }
        $r2 = str_replace('<', '&lt;', $html);
        $r2 = str_replace("\t", '_ _ _ _', $r2);
        return str_replace("\n", "<br>\n", $r2);
    }

    /**
     * Add an element (or a container element) to the form
     * 
     * @param _Element $element 
     */
    public function addElement(_Element $element) {
        $this->_components[$element->getName()] = $element;
        $element->setContainer($this);
        $this->registerElement($element);
    }

    /**
     * Add an element before the last element (usually the submit button)
     * 
     * @param _Element $element 
     * @deprecated (use PlaceHolder instead, it is more versatile)
     */
    public function appendElement(_Element $element) {
        $last = array_pop($this->_components);
        $this->addElement($element);
        $this->_components[$last->getName()] = $last;
    }

    /**
     * Render the <form> tag : to override with a specific
     * method for each form type
     * 
     */
    abstract protected function _formTag();

    /**
     * The action to be taken after the user fills the form
     * 
     * @param string $actionURI 
     */
    public function setAction($actionURI) {
        $this->_action = $actionURI;
    }

    /**
     * Checks each data in formdata against the element validators
     * 
     * @param array $formData
     * @return boolean 
     */
    public function isValid($formData) {
        $this->fill($formData);
        $valid = TRUE;
        foreach ($this->_registry as $element) {
            if (!$element->validate()) {
                $valid = FALSE;
            }
        }
        return $valid;
    }

    /**
     * Fill the form values with the data provided (or by default
     * with $_POST values)
     * 
     * @param array $formData 
     */
    public function fill($formData=NULL) {
        if ($formData == NULL) {
            $formData = \Iris\Engine\Superglobal::GetPost();
        }
        foreach ($formData as $key => $value) {
            if (isset($this->_registry[$key])) {
                $element = $this->_registry[$key];
                $this->_registry[$key]->setValue($value);
            }
        }
    }

    /**
     * Mark each element as disabled (if possible)
     * 
     */
    public function makeReadOnly() {
        foreach ($this->_registry as $element) {
            if ($element->canDisable()) {
                $element->setDisabled('disabled');
            }
        }
    }

    /**
     * Register an element to a form (throwing an exception if it is already
     * registred).
     * 
     * @param _Element $element 
     */
    public function registerElement(_Element $element) {
        $name = $element->getName();
        if (isset($this->_registry[$name])) {
            throw new \Iris\Exceptions\FormException("The element {$name} already  exists in the form.");
        }
        $this->_registry[$name] = $element;
        if ($element instanceof \Iris\Forms\Elements\iAidedValue) {
            $this->_aidedComponents[] = $element;
        }
    }

    /**
     * Get a component of the registry by its name
     * (it can be embedded in a container). If it is doesn't exist no
     * error, return NULL
     * 
     * @param string $name
     * @return _Element
     */
    public function getComponent($name) {
        if (isset($this->_registry[$name])) {
            return $this->_registry[$name];
        }
        else {
            return NULL;
        }
    }

    /**
     * Get a component of the registry by its name
     * (synonymous of getComponent used as a magic method)
     * 
     * @param type $name
     * @return _Element 
     */
    public function __get($name) {
        return $this->_registry($name);
    }

    /**
     *
     * @return _FormFactory 
     */
    public function getFormFactory() {
        return $this->_formFactory;
    }

    /**
     * Get the layout of the form (used to display the elements)
     * 
     * @return _FormLayout 
     */
    public function getLayout() {
        if (is_null($this->_layout))
            $this->_layout = new DefLayout();
        return $this->_layout;
    }

    /**
     * Set the layout of the form 
     * 
     * @param type $layout 
     */
    public function setLayout($layout) {
        $this->_layout = $layout;
    }

    /**
     * Get the form factory used to create the form (permits to create elements
     * and validators in harmony with the form type).
     * 
     * @param _FormFactory $formFactory 
     */
    public function setFormFactory($formFactory) {
        $this->_formFactory = $formFactory;
    }

    /**
     * Return a string with all specific attributes of a form
     */
    protected function _renderAttributes() {
        $text = '';
        foreach ($this->_attributes as $key => $value) {
            $text .= " $key=\"$value\" ";
        }
        return $text;
    }

    /**
     * Add a new attribute to the form tag
     * 
     * @param string $key
     * @param string $value 
     */
    public function addAttribute($key, $value) {
        $this->_attributes[$key] = $value;
    }

    public function getDataFromPost($field=NULL, $defaultValue=NULL) {
        static $data = NULL;
        if ($data == NULL) {
            $formData = \Iris\Engine\Superglobal::GetPost();
            $data = $formData;
            foreach ($this->_aidedComponents as $element) {
                $data[$element->getName()] = $element->compileValue($formData);
            }
        }
        if ($field == NULL) {
            return $data;
        }
        else {
            if (isset($data[$field])) {
                return $data[$field];
            }
            else {
                return $defaultValue;
            }
        }
    }

}

