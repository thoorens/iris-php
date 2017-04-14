<?php

namespace Iris\Forms;

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
 * Description of FormMaker
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _FormMaker {

    const AUTO = 0;
    const HTML = 1;
    const DOJO = 2;

    /**
     *
     * @var _Form
     */
    protected $_form;

    /**
     *
     * @var _FormFactory
     */
    protected $_formFactory;

    /**
     *
     * @var _Element[]
     */
    protected $_elements = [];

    /**
     *
     * @var _Element
     */
    protected $_submitButton = 'Send';

    /**
     * Gets a form maker to make a form by hand
     * 
     * @param string $formName The form name
     * @param int $factoryType The factory type (may ignore the default settings)
     * @return self
     */
    public static function HandMadeForm($formName = 'autoform', $factoryType = self::AUTO) {
        $formMaker = Makers\HandMade::_GetMaker($formName, $factoryType);
        return $formMaker;
    }

    /**
     * Gets a form maker to make a form using parameters
     * 
     * @param string $formName The form name
     * @param int $factoryType The factory type (may ignore the default settings)
     * @return self
     */
    public static function ParametricForm($formName = 'autoform', $factoryType = self::AUTO) {
        $formMaker = Makers\Parametric::_GetMaker($formName, $factoryType);
        return $formMaker;
    }

    /**
     * Gets a form maker to make a form using the metadata
     * 
     * @param string $entityName The entity name from which extract the internal structure
     * @param int $factoryType The factory type (may ignore the default settings)
     * @return self
     */
    public static function EntityForm($entityName, $factoryType = self::AUTO) {
        $formMaker = Makers\Entity::_GetMaker($entityName, $factoryType);
        $em = \Iris\DB\_Entity::DefaultEntityManager();
        $entityClass = \Iris\System\Functions::TableToEntity($entityName);
        \Iris\DB\_EntityManager::GetEntityPath();
        \models\TCustomers::GetEntity();
        return $formMaker;
    }

    /**
     * Gets a form maker to make a form using an ini file
     * 
     * @param string $fileName The ini file name
     * @param int $factoryType The factory type (may ignore the default settings)
     * @return self
     */
    public static function FileForm($fileName, $factoryType = self::AUTO) {
        $formName = basename($fileName,'ini');
        $formMaker = Makers\IniFile::_GetMaker($formName, $factoryType);
        $formMaker->setFile($fileName);
        return $formMaker;
    }

    /**
     * Gets a form maker to make a form using metadata with comments
     * 
     * @param string $entityName The entity name
     * @param int $factoryType The factory type (may ignore the default settings)
     * @return self
     */
    public static function CommentForm($entityName = 'autoform', $factoryType = self::AUTO) {
        $formMaker = Makers\Comment::_GetMaker($entityName, $factoryType);
        return $formMaker;
    }

    /**
     * Initialize a form maker with convenient internal formfactory and form
     * 
     * @param string $formName
     * @param int $factoryType
     * @return FormMaker
     */
    protected abstract static function _GetMaker($formName, $factoryType);
//    {
//        switch ($factoryType) {
//            case self::AUTO:
//                $factory = _FormFactory::GetDefaultFormFactory();
//                break;
//            case self::HTML:
//                $factory = new \Iris\Forms\StandardFormFactory();
//                break;
//            case self::DOJO:
//                $factory = new \Dojo\Forms\FormFactory();
//                break;
//        }
//        $formMaker = new self();
//        $formMaker->setFactory($factoryType)->setForm($formName);
//        return $formMaker;
//    }

    /**
     * Accessor set for the internal form factory
     * 
     * @param _FormFactory $factory the form factory to use
     * @return FormMaker for fluent interface
     */
    public function setFactory($factoryType) {
        if ($factoryType instanceof _FormFactory) {
            $this->_formFactory = $factoryType;
        }
        else {
            switch ($factoryType) {
                case self::AUTO:
                    $factory = _FormFactory::GetDefaultFormFactory();
                    break;
                case self::HTML:
                    $factory = new \Iris\Forms\StandardFormFactory();
                    break;
                case self::DOJO:
                    $factory = new \Dojo\Forms\FormFactory();
                    break;
            }
            $this->_formFactory = $factory;
        }
        return $this;
    }

    /**
     * Accessor set for the internal form
     * 
     * @param mixed $form the form object or the form name
     * @return FormMaker for fluent interface
     */
    public function setForm($form) {
        if ($form instanceof Elements\Form) {
            $this->_form = $form;
        }
        else {
            $this->setFormName($form);
        }
        return $this;
    }

    /**
     * Creates the form or changes the existing
     * @param mixed $formName
     */
    public function setFormName($formName) {
        if (is_null($this->_form)) {
            $this->_form = $this->_formFactory->createForm($formName);
        }
        else {
            $this->_form->changeFormName($formName);
        }
    }

    /**
     * Accessor get for the internal form factory
     * 
     * @return _FormFactory
     */
    public function getFormFactory() {
        return $this->_formFactory;
    }

    public function getSubmitButton() {
        if (is_string($this->_submitButton)) {
            $this->_submitButton = $this->_formFactory->createSubmit($this->_submitButton)->setValue($this->_submitButton);
        }
        return $this->_submitButton;
    }

    /**
     * Accessor get for the internal form
     * 
     * @return _Form
     */
    /**
     * 
     * @param booelan $treat if TRUE all the elements are inserted in the form
     * @return _Form
     */
    public function getForm($treat = \TRUE) {
        if ($treat) {
            $this->_insertElements();
        }
        return $this->_form;
    }

    /**
     * 
     * @return string
     */
    public function formRender() {
        $this->_insertElements();
        return $this->_form->render();
    }

    /**
     * 
     * @param type $text
     */
    public function setSubmitValue($text) {
        $this->getSubmitButton()->setValue($text);
    }

    /**
     * 
     */
    protected function _insertElements() {
        $this->_form->addElement($this->getSubmitButton());
    }

    /**
     * Export all unknown methods to the internal form
     * 
     * @param string $methodName the name of the method
     * @param mixed $arguments the argument as an array
     */
    public function __call($methodName, $arguments) {
        $this->_form->$methodName($arguments[0]);
    }

    
}
