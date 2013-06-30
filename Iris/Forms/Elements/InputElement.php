<?php



namespace Iris\Forms\Elements;

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
 * Various class of input. Some of them are not defined in HTML 4,
 * mais implicite validators have been addedd
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class InputElement extends \Iris\Forms\_Element {

    public function __construct($name, $type, $options = array()) {
        parent::__construct($name, 'input', $options);
        $this->_subtype = $type;
        switch ($type) {
            // file is now a separated class
            case 'hidden':
                $this->_labelPosition = self::NONE;
                break;
            case 'image':
            case 'reset':
            case 'submit':
            case 'button':
                $this->_labelPosition = self::NONE;
                $this->_canDisable = FALSE;
                break;
            case 'date':
                $this->addValidator(new ifo\Validators\Date());
                $this->_subtype = 'date';
                break;
            case 'number':
            case 'text':
            case 'password':
            default :
                break;
        }
    }

    

}

?>
