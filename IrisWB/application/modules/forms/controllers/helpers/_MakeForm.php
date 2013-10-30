<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace modules\forms\controllers\helpers;

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
 * Description of newIrisClass
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _MakeForm extends \Iris\controllers\helpers\_ControllerHelper {

    const TEXT = 0b00000000001;
    const HIDDEN = 0b00000000010;
    const PASSWORD = 0b00000000100;
    const DATE = 0b00000001000;
    const TIME = 0b00000010000;
    const CHECK = 0b00000100000;
    const RADIONAME = 0b00001000000;
    const RADIOINDEX = 0b00010000000;
    const SELECT = 0b00100000000;
    const MULTI = 0b01000000000;
    const BUTTONS = 0b10000000000;
    const MULTIPERLINE = 0b11011000000;
    const ALL = 0b11111111111;

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
                        ->setTitle('Enter a description')
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
                        ->setTitle('Enter a date')
                        ->addTo($this->_form);
    }

    /**
     * An hour input
     * @return \Iris\Forms\_Element
     */
    protected function _timeInput() {
        return $this->_formFactory->createTime('EventHour')
                        ->setLabel('Starting time:')
                        ->setTitle('Enter an hour')
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
                        ->setTitle('Enter your password')
                        ->setLabel("Password:");
    }

    /**
     * Four radio button (value is index)
     * @return \Iris\Forms\_Element
     */
    protected function _radioIndex() {
        // The array index are used to set the names and select the
        // choosed value (see FALSE in addOptions)
        return $this->_formFactory->createRadioGroup('Radio_index')
                        ->setPerLine($this->_perline)
                        ->addTo($this->_form)
                        ->setLabel('Radio group (by index):')
                        ->setValue(2)
                        ->setTitle("Choose your prefered operating system")
                        ->addOptions($this->_sampleData, \FALSE);
    }

    /**
     * Four radio button (value is label)
     * @return \Iris\Forms\_Element
     */
    protected function _radioLabel() {
        // The array values are used to set the names and select the
        // choosed value (see TRUE in addOptions)
        return $this->_formFactory->createRadioGroup('Radio_name')
                        ->setPerLine($this->_perline)
                        ->addTo($this->_form)
                        ->setLabel('Radio group (by content):')
                        ->setValue('Linux')
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
                        ->setTitle('Check if to use with PC')
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
                        ->setValue(2 + 4);
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
                        ->setTitle("Choose your prefered operating systems")
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
                        ->addTo($this->_form);
    }

    /**
     * Will enumerate the actual components of the form
     */
    protected abstract function _collectComponent();
        

}

