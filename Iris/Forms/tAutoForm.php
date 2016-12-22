<?php

namespace Iris\Forms;

/*
 * This file is part of IRIS-PHP distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This treat adds a few properties and method common to the iris and dojo versions of AutoFormFactory
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tAutoForm {

   
    
    protected $_hasSubmit = \FALSE;

    /**
     *
     * @return _AutoFormFactory
     */
    public static function GetDefaultFormFactory() {
        $defaultClassName = \Iris\SysConfig\Settings::$DefaultFormClass;
        $className = str_replace('FormFactory', 'AutoFormFactory', $defaultClassName);
        return new $className();
    }

    
    
    /**
     *
     * @param type $name
     * @return Elements\MagickForm 
     */
    public function initForm($name, $entity, $command = self::DEFCOMMAND) {
        if (self::TYPE == 'DOJO') {
            $form = new \Dojo\Forms\Elements\MagickForm($name);
        }
        else {
            $form = new \Iris\Forms\Elements\MagickForm($name);
        }
        $form->setFormFactory($this);
        $form->setEntity($entity);
        $form->setMode($command);
        return $form;
    }

    public function getHasSubmit() {
        return $this->_hasSubmit;
    }

    public function createSubmit($name, $options = []) {
        if (!$this->_hasSubmit) {
            $this->_hasSubmit = \TRUE;
            return $this->_createInput($name, 'submit', $options);
        }
    }

}
