<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\controllers\helpers;

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
class RequiredForm extends \modules\forms\controllers\helpers\_MakeForm {

    protected function _collectComponent() {
        $this->_textInput()->addValidator('Required');
        $this->_passwordInput()->addValidator('required');
        //$this->_hiddenInput()->addValidator('required');
        $this->_dateInput()->addValidator('Required');
        $this->_timeInput()->addValidator('Required');
        $this->_submit();
    }    
}
