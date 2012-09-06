<?php

namespace Iris\Users;

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
 * An abstract class for managing passwords
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Password {
    /**
     * Creates an uncrypted password with salt
     * 
     * @param string $password
     * @return string
     */
    public static function EncodePassword($password) {
        $pos = rand(0, strlen($password) - 2);
        $subString = substr($password, $pos, $pos + 1);
        $md5 = md5($subString);
        $salt = substr($md5, 0, 2);
        $candidate = md5(crypt($password, $salt));
        $encrypt = $salt . $candidate;
        return $encrypt;
    }

    /**
     * Verifies an password 
     * 
     * @param string $clear 
     * @param string $encrypt
     * @return boolean 
     */
    public static function VerifyPassword($clear, $encrypt) {
        $salt = substr($encrypt, 0, 2);
        $candidate = md5(crypt($clear, $salt));
        $test = $salt . $candidate;
        return $encrypt == $test;
    }

        
}