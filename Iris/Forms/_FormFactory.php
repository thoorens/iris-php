<?php

namespace Iris\Forms;
use Iris\Forms\Validators\Force as VForce;
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
     * 
     */
    const AUTO = 'auto';

    /**
     * 
     */
    const HTML = 'html';

    /**
     * 
     */
    const DOJO = 'dojo';

    /**
     * 
     */
    const AUTOFORMNAME = 'form';

    /**
     * Gecko – for Firefox, Camino, K-Meleon, SeaMonkey, Netscape, and other Gecko-based browsers
     */
    const GECKO_ENGINE = "Gecko";

    /**
     * WebKit for 
     * iOS (including both mobile Safari, WebViews 
     * within third-party apps, and web clips), 
     * Safari, Arora, Midori, OmniWeb (since version 5), 
     * Shiira, iCab since version 4, 
     * Web, SRWare Iron, Rekonq, 
     * Sleipnir, in Maxthon 3, and 
     * Google Chrome up to version 27
     */
    const WEBKIT_ENGINE = "WebKit";

    /**
     * EdgeHTML – for Microsoft Edge
     */
    const EDGEHTML_ENGINE = "EdgeHTML";

    /**
     * See \Iris\System\WebEngine for other engine details
     */

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
     *
     * @var string
     */
    protected $_webEngine;

    /**
     *
     * @var string[] 
     */
    protected $_engineNames;

    /**
     *
     * @var string 
     */
    protected $_weVersion;

    
    protected static $_FactoryType;
    
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
    public static function GetFormFactory($factoryType = self::AUTO) {
        switch ($factoryType) {
            case _FormFactory::AUTO:
                $defaultClassName = \Iris\SysConfig\Settings::$DefaultFormClass;
                $factory = new $defaultClassName();
                break;
            case self::HTML:
                $factory = new \Iris\Forms\StandardFormFactory();
                break;
            case self::DOJO:
                $factory = new \Dojo\Forms\FormFactory();
                break;
        }
        return $factory;
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
        print "Submit";
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
     * Creates an element of type Text
     * 
     * @param string $name The name of the element
     * @param array $options The optional options
     * @return \Iris\Forms\Elements\InputElement
     */
    public function createInteger($name, $options = []) {
        $text = $this->createText($name, $options);
        $text->addValidator(new VForce(VForce::NUM));
        return $text;
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

    /**
     * Gets the type of factory of the subclass
     * 
     * @return string
     */
    public function getType(){
        return static::$_FactoryType;
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
        $type = $this->_browserAbility('datetime');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('date');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('datetime-local');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('week');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('url');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('tel');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('search');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('range');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('number');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('month');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('email');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('color');
        $element = $this->_createInput($name, $type, $options);
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
        $type = $this->_browserAbility('time');
        $element = $this->_createInput($name, $type, $options);
        if ($this->_serverValidation) {
            $element->addValidator(new Validators\Color());
        }
        return $element;
    }

    protected function _browserAbility($type) {
        if ($this->_webEngine === \NULL) {
            $webEngine = \Iris\System\WebEngine::GetInstance();
            $this->_webEngine = $webEngine->getEngineName();
            $this->_weVersion = $webEngine->getVersion();
            $this->_engineNames = [
                self::GECKO_ENGINE,
                self::EDGEHTML_ENGINE,
                self::WEBKIT_ENGINE
            ];
        }
        $rules = \Iris\System\WebEngine::$TypeRules;
        foreach ($this->_engineNames as $engineName) {
            if (!isset($rules[$engineName])) {
                $rules[$engineName] = -1;
            }
        }
        if ($rules[$this->_webEngine] > $this->_weVersion) {
            \Iris\Engine\Log::Debug("Type $type :' . N<br/>", \Iris\Engine\Debug::HTML5);
            $value =  'text';
        }
        else {
            \Iris\Engine\Log::Debug("Type $type : Y<br/>", \Iris\Engine\Debug::HTML5);
            $value =  $type;
        }
        return $value;
    }

}
