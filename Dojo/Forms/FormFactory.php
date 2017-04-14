<?php

namespace Dojo\Forms;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A specific form factory for Dojo
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class FormFactory extends \Iris\Forms\_FormFactory {

    public function __construct() {
        //\Dojo\views\helpers\Loader::HelperCall('dojo_Loader', array(TRUE));
    }

    /**
     *
     * @param type $name
     * @return Form 
     */
    public function createForm($name) {
        $class = $this->getClass('\\Elements\\Form');
        $form = new $class($name);
        $form->setFormFactory($this);
        return $form;
    }

    /**
     * A specific input type creator for Dojo. 
     * It creates a sort a textarea with HTML capabilities.
     * 
     * @param string $name
     * @param type $options
     * @return Elements\Editor 
     */
    public function createEditor($name, $options = []) {
        return new Elements\Editor($name, $options);
    }

    public function createSubmit($name, $options = []) {
        return new Elements\Submit($name, 'submit', $options);
    }

    public function createReset($name, $options = []) {
        return new Elements\Submit($name, 'reset', $options);
    }

//    public function createPassword($name,$subType,$options = array()){
//        return new Elements\Password($name, $subType, $options);
//    }
}
