<?php



namespace Dojo\Forms\Elements;

use \Iris\Forms as ifo;

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

/*
 * Password input element
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Password_____ extends \Iris\Forms\Elements\InputElement {

    /**
     *
     * @param string $name name of the element
     * @param string $type HTML type (input) or subtype (date)
     * @param type $options any option to add to HTML code
     */
    public function __construct($name, $subType, $options = array()) {
        parent::__construct($name, 'password', $options);
        $type = "dojox.form.PasswordValidator";
        $dojoManager = \Dojo\Manager::GetInstance();
        $dojoManager->addRequisite("PasswordValidator", $type);
        $this->dojoType = $type;
        $this->setPwType($subType);
    }



}
?>
