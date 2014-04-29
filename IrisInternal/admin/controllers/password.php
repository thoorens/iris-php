<?php

namespace IrisInternal\admin\controllers;

use \Iris\Users as u;

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
 * In admin internal module, permits to switch role in order to test
 * application behaviour.
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class password extends _admin {

    public function indexAction(){
        if(isset($_POST['password'])){
            $clear = $_POST['password'];
            $this->__clear = $clear;
            $initialPasswordMode = \Iris\SysConfig\Settings::GetPasswordHashType();
            \Iris\SysConfig\Settings::SetPasswordHashType(PASSWORD_IRIS);
            $this->__hashIris = u\_Password::EncodePassword($clear);
            \Iris\SysConfig\Settings::SetPasswordHashType(PASSWORD_BCRYPT);
            $this->__hashBcrypt =  u\_Password::EncodePassword($clear);
            \Iris\SysConfig\Settings::SetPasswordHashType($initialPasswordMode);
        }
    }
}


