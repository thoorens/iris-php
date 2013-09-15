<?php

namespace Iris\Engine;

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
 *  A way to access superglobals, with security and default value
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class Superglobal {
    
    
    
    public static function GetPost($key=NULL,$default=NULL){
        return self::_GetData('POST', $key, $default);
    }
    
    public static function GetGet($key=NULL,$default=NULL){
        return self::_GetData('GET', $key, $default);
    }
    
    public static function GetServer($key=NULL,$default=NULL){
        return self::_GetData('SERVER', $key, $default);
    }
    
    public static function GetSession($key=NULL,$default=NULL){
        return self::_GetData('SESSION', $key, $default);
    }
    
    public static function GetEnv($key=NULL,$default=NULL){
        return self::_GetData('ENV', $key, $default);
    }
    

    
    /**
     *
     * @param string $name
     * @param type $key
     * @param type $default
     * @return type 
     */
    private static function _GetData($name,$key=NULL,$default=NULL){
        switch ($name){
            case 'SERVER':
                $var = $_SERVER;
                break;
            case 'SESSION':
                $var = $_SESSION;
                break;
            case 'POST':
                $var = $_POST;
                break;
            case 'GET':
                $var = $_GET;
                break;
            case 'ENV':
                $var = $_ENV;
                break;
        }
        if(is_null($key)){
            return $var;
        }else{
            return isset($var[$key]) ? $var[$key] : $default;
        }
    }
    
    
}


