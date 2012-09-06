<?php



namespace Dojo\Forms;

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
 * A specific form factory for Dojo
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class FormFactory extends \Iris\Forms\_FormFactory {

    protected static $_Library = "Dojo\\Forms";

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
    public function createEditor($name, $options= NULL) {
        return new Elements\Editor($name, $options);
    }

    public function createSubmit($name, $options= NULL) {
        return new Elements\Submit($name, $options);
    }

//    public function createPassword($name,$subType,$options = array()){
//        return new Elements\Password($name, $subType, $options);
//    }
}

?>
