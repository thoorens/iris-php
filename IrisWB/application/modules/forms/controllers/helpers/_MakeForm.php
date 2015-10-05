<?php

namespace modules\forms\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _MakeForm extends \Iris\controllers\helpers\_ControllerHelper {

    private $_specialNames = [
        'layout' => 'def',
        'perline' => 4,
    ];
    protected $_layout;
    protected $_perline;
    protected $_formFactory;
    protected $_form;
    protected $_sampleData = [
        1 => 'Windows',
        2 => 'Linux',
        4 => 'MacOS',
        8 => 'Android'
    ];

    /**
     * 
     * @param \Iris\Forms\_FormFactory $formFactory
     * @param int $fields
     * @return \Iris\Forms\_Form
     */
    public function help($formFactory = \NULL, $specials = []) {
        foreach ($this->_specialNames as $name => $default) {
            $varName = "_$name";
            if (isset($specials[$name])) {
                $this->$varName = $specials[$name];
            }
            else {
                $this->$varName = $default;
            }
        }
        if (is_null($formFactory)) {
            $formFactory = \Iris\Forms\_FormFactory::GetDefaultFormFactory();
        }
        $this->_formFactory = $formFactory;

        $this->_form = $formFactory->createForm('Test');
        /* @var $layout string : defined in specials */
        switch ($this->_layout) {
            case 'def':
                $this->_form->setLayout(new \Iris\Forms\DefLayout());
                break;
            case 'no':
                $this->_form->setLayout(new \Iris\Forms\NoLayout());
                break;
            case 'tab':
                $this->_form->setLayout(new \Iris\Forms\TabLayout());
                break;
        }
        $this->_collectComponent();
        return $this->_form;
    }

    /**
     * A simple text
     * 
     * @return \Iris\Forms\_Element
     */
    protected function _textInput() {
        return $this->_formFactory->createText('Name')
                        ->addTo($this->_form)
                        ->setTitle('If filled, this element will produce a «Name» field in POST.')
                        ->setLabel("The event name:");
    }

    /**
     * A hidden field
     * @return \Iris\Forms\_Element
     */
    protected function _hiddenInput() {
        return $this->_formFactory->createHidden('Hidden')
                        ->addTo($this->_form)
                        ->setValue('Not visible');
    }

    /**
     * A date input
     * @return \Iris\Forms\_Element
     */
    protected function _dateInput() {
        return $this->_formFactory->createDate('EventDate')
                        ->setLabel('The event date:')
                        ->setTitle('If filled, this element will produce an «EventDate» field in POST.')
                        ->addTo($this->_form);
    }

    /**
     * An hour input
     * @return \Iris\Forms\_Element
     */
    protected function _timeInput() {
        return $this->_formFactory->createTime('EventHour')
                        ->setLabel('Starting time:')
                        ->setTitle('If filled, this element will produce an «EventHour» field in POST.')
                        ->addTo($this->_form);
    }

    /**
     * A simple password
     * 
     * @return \Iris\Forms\_Element
     */
    protected function _passwordInput() {
        return $this->_formFactory->createPassword('Password')
                        ->addTo($this->_form)
                        ->setTitle('If filled, this element will produce a «Password» field in POST.')
                        ->setLabel("Password:");
    }

    /**
     * Four radio button (value is index)
     * @return \Iris\Forms\_Element
     */
    protected function _radioIndex() {
        // The array index are used to set the names and select the
        // choosed value (see FALSE in addOptions)
        return $this->_formFactory->createRadioGroup('Radio_index_notinited')
                        ->setPerLine($this->_perline)
                        ->addTo($this->_form)
                        ->setLabel('Radio group (by index) without initialization:')
                        // could be better inited by validation 
                        ->setValue(\Iris\Engine\Superglobal::GetPost('Radio_index_notinited', \NULL))
                        ->setTitle("Choose your prefered operating system")
                        ->addOptions($this->_sampleData, \FALSE);
    }

    /**
     * Four radio button (value is index)
     * @return \Iris\Forms\_Element
     */
    protected function _initedRadioIndex() {
        // The array index are used to set the names and select the
        // choosed value (see FALSE in addOptions)
        return $this->_formFactory->createRadioGroup('Radio_index')
                        ->setPerLine($this->_perline)
                        ->addTo($this->_form)
                        ->setLabel('Radio group (by index):')
                        // could be better inited by validation
                        ->setValue(\Iris\Engine\Superglobal::GetPost('Radio_index', 2))
                        ->setTitle("Choose your prefered operating system")
                        ->addOptions($this->_sampleData, \FALSE);
    }

    /**
     * Four radio button (value is label)
     * @return \Iris\Forms\_Element
     */
    protected function _initedRadioLabel() {
        // The array values are used to set the names and select the
        // choosed value (see TRUE in addOptions)
        return $this->_formFactory->createRadioGroup('Radio_name')
                        ->setPerLine($this->_perline)
                        ->addTo($this->_form)
                        ->setLabel('Radio group (by content):')
                        ->setValue(\Iris\Engine\Superglobal::GetPost('Radio_name','Linux'))
                        ->setTitle("Choose your prefered operating system")
                        ->addOptions($this->_sampleData, \TRUE);
    }

    /**
     * A simple check
     * 
     * @return \Iris\Forms\_Element
     */
    protected function _checkInput() {
        return $this->_formFactory->createCheckbox('Checkbox')
                        ->addTo($this->_form)
                        ->setValue(\TRUE)
                        ->setTitle('If this checkbox is checked, a Checkbox field will appear in POST')
                        ->setLabel("For use with a PC");
    }

    /**
     * Select between four options
     *  
     * @return \Iris\Forms\_Element
     */
    protected function _select() {
        return $this->_formFactory->createSelect('Select')
                        ->addTo($this->_form)
                        ->setLabel('Select option:')
                        ->addOptions($this->_sampleData)
                        ->setTitle("Choose your prefered operating system")
                        ->setValue($this->_perline);
    }

    /**
     * Four multicheck buttons
     * 
     * @return \Iris\Forms\_Element
     */
    protected function _multiCheck() {
        return $this->_formFactory->createMultiCheckbox('Multi')
                        ->addTo($this->_form)
                        ->setPerLine($this->_perline)
                        ->setLabel('Multicheck group:')
                        ->addOptions($this->_sampleData)
                        ->setTitle("Choose your prefered operating system")
                        ->autoSet(6);
    }

    /**
     * Four submit buttons from $_sampleData
     * @return \Iris\Forms\_Element
     */
    protected function _buttons() {
        return $this->_formFactory->createButtonGroup('ButtonGroup')
                        ->addTo($this->_form)
                        ->setPerLine($this->_perline)
                        ->setLabel('Button group:')
                        ->setTitle("This button will produce a ButtonGroupX in POST where X may be 1, 2, 4 or 8")
                        ->addOptions($this->_sampleData);
    }

    /**
     * A normal submit button 
     * @return \Iris\Forms\_Element
     */
    protected function _submit() {
        return $this->_formFactory->createSubmit('Submit')
                        ->setValue('Submit')
                        ->setLabel('Brol:')
                        ->setTitle('This button will produce a «Submit» field in POST')
                        ->addTo($this->_form);
    }

    /**
     * Will enumerate the actual components of the form
     */
    protected abstract function _collectComponent();
}

