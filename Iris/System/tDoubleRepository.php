<?php

namespace Iris\System;


defined('CRLF') or define('CRLF',"\n");
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
 * @copyright 2011-13 Jacques THOORENS
 */

/**
 * Implements an internal repository for a class having named instances
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tDoubleRepository{

    use tRepository;
    
    protected static function _GetObject($groupName, $objectName) {
        if (!isset(self::$_Repository[$groupName]) or !isset(self::$_Repository[$groupName][$objectName])) {
            self::$_Repository[$groupName][$objectName] = self::_New($objectName);
        }
        return self::$_Repository[$groupName][$objectName];
    }
    
    
    /**
     * 
     * @param string $objectName The name of the object to delete
     */
    public static function DropObject($groupName, $objectName){
        if (isset(self::$_Repository[$groupName]) and isset(self::$_Repository[$groupName][$objectName])) {
            unset(self::$_Repository[$groupName][$objectName]);
        }
    }
    
    public static function GetGroup($groupName){
        if(isset(self::$_Repository[$groupName])){
           return self::$_Repository[$groupName]; 
        }
        else{
            return [];
        }
        
    }
}

