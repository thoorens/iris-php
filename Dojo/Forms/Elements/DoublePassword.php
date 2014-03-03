<?php



namespace Dojo\Forms\Elements;

use Iris\Forms as ifo;

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
 * A Dojo version of DoublePassword
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class DoublePassword extends \Iris\Forms\Elements\DoublePassword {
    
    use \Dojo\Forms\tDojoDijit;
    
    /**
     * The constructor creates a group and loads all Dojo material needed
     * 
     * @param string $name the name of the element
     * @param \Iris\Forms\_FormFactory $formFactory the form used to create it
     * @param mixed $options special options
     */
    public function __construct($name, $formFactory, $options = array()) {
        \Iris\Forms\Elements\_ElementGroup::__construct($name, 'dl', $formFactory, $options);
        $type = "dojox.form.PasswordValidator";
        \Dojo\Engine\Bubble::GetBubble($type)
                ->addModule('dojox/form/PasswordValidator');
        $this->setDijitType($type);
    }

    /**
     * Dojo uses pwtype new and verify to distinguish the two password
     */
    protected function _createSubcomponents() {
        $this->_pwd1 = $this->_addOption('_1', '')
                ->setLabelPosition(self::NONE)
                ->setPwType('new');
        $this->_pwd2 = $this->_addOption('_2', '')
                ->setPwType('verify');
        $this->_formFactory->validatorRequired();
    }

    /**
     * Job has been done by Dojo
     * 
     * @param mixed[] $data Data from form
     * @return string 
     */
    public function compileValue(&$data) {
        return $data[$this->getName()];
    }

    /**
     * You can't add options to a DoublePassword (subcomponents are internally managed)
     */
    public function addOptions($dummy, $dummy2=FALSE) {
        throw new \Iris\Exceptions\FormException('addOptions is reserved as an internal method');
    }

    /**
     *
     * @param string $key Idenfier (_1 or _2)
     * @param string $value not used (passwords are never inited)
     * @return Password 
     */
    protected function _addOption($key, $value) {
        // special key _1 and _2 must be used
        if ($key != '_1' and $key != '_2') {
            throw new \Iris\Exceptions\FormException('_addOption is reserved as an internal method');
        }
        $ff = new \Iris\Forms\StandardFormFactory();
        //$ff = $this->getFormFactory();
        $innerElement = $ff->createPassword($this->_name . $key)
                ->setValue('');
        $innerElement->_container = $this;
        $this->_subComponents[$key] = $innerElement;
        $this->_container->registerElement($innerElement);
        return $innerElement;
    }

    public function getFirstComponent() {
        return $this->_pwd1;
    }

    public function validate() {
        $valid = parent::validate();
        if ($valid and $this->getValue() == NULL) {
            $this->_pwd1->setError($this->_('The two passwords do not match',TRUE));
            return FALSE;
        }
        else {
            return $valid;
        }
    }
   

}


