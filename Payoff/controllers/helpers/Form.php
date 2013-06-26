<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payoff\controllers\helpers;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * Creates a form and manages its values
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Form extends \Iris\controllers\helpers\_ControllerHelper {
    use \Payoff\tTranslatable;
    
    protected static $_Singleton = \TRUE;

    const COMPLETED = 0;
    const CANCELLED = 1;
    const VERIFY = 2;

    private $_address = \NULL;
    private $_name = \NULL;

    /**
     * Returns the unique instance of the helper
     * @return \Payoff\controllers\helpers\Form
     */
    public function help() {
        return $this;
    }

    /**
     * Creates a simple form with Name, Address and Submit
     * 
     * @return \Iris\Forms\Elements\Form
     */
    public function compose() {
        $ff = \Iris\Forms\_FormFactory::GetDefaultFormFactory();
        $form = $ff->createForm('Client');
        $form->createText('Name')
                ->setLabel('Name:')
                ->setValue($this->getName())
                ->addTo($form);
        $form->createText('Address')
                ->setLabel('Address:')
                ->setValue($this->getAddress())
                ->addTo($form);
        $form->createSubmit('Submit')
                ->setValue('Buy')
                ->addTo($form);
        return $form;
    }

    /**
     * Returns TRUE if all required fields are filled
     * 
     * @return boolean
     */
    public function dataComplete() {
        $this->_getData();
        $twoElements = (!empty($this->_name) and !empty($this->_address));
        return $twoElements and $this->_otherRequired();
    }

    /**
     * Gets the name and address form POST data and initializes the
     * correspondant variables
     * 
     * @staticvar boolean $read
     */
    private function _getData() {
        static $read = \FALSE;
        if (!$read) {
            $this->_name = \Iris\Engine\Superglobal::GetPost('Name', \NULL);
            $this->_address = \Iris\Engine\Superglobal::GetPost('Address', \NULL);
        }
    }

    /**
     * Gets the name (if possible) from POST data
     * @return string
     */
    public function getName(){
        $this->_getData();
        return $this->_name;
    }
    
    /**
     * Gets the address (if possible) from POST data
     * 
     * @return string
     */
    public function getAddress(){
        $this->_getData();
        return $this->_address;
    }

    /**
     * If overwritten, this method can add other elements to the required ones
     * 
     * @return boolean
     */
    protected function _otherRequired() {
        return \TRUE;
    }
}

