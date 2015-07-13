<?php

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
 * The Mode class is defined in Loader.php file for consistency reasons
 * 
 * The mode class permits to know which type of site is running. 
 * 
 */

namespace Iris\Engine;

/**
 * A singleton has only on instance and must have a non public constructor
 * 
 */
trait tSingleton {

    
    protected function __construct() {
    }
    
    /**
     * Returns the unique instance or creates it if necessary.
     * 
     * @staticvar \static $Instance Serves to store the unique instance
     * @return \static
     */
    public static function GetInstance() {
        static $Instance = \NULL;
        if (is_null($Instance)) {
            $Instance = new static();
        }
        return $Instance;
    }

}


