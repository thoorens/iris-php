<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */ 

/**
 * @todo Write the description of the class
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
        $this->_passwordInput()->addValidator('Required');
        //$this->_hiddenInput()->addValidator('required');
        $this->_dateInput()->addValidator('Required');
        $this->_timeInput()->addValidator('Required');
        $this->_submit();
    }    
}

