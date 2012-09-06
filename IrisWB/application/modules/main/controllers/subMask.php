<?php

namespace modules\main\controllers;

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Submask : this controller is intented to be masked by
 * another in Dummmy module and therefore should never be
 * used (exception in case of error)
 * 
 */
class subMask extends \Iris\MVC\_Subcontroller {

    public function indexAction($number = 5) {
        $message = <<< MESS
    main/SubMask is a fake controller and has been called by error.<br>
    testLayout/Submask was expected.
MESS;
        throw new \Iris\Exceptions\ControllerException($message);
    }

}
