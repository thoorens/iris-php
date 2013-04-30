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
trait tRepository{

    /**
     * All the object are placed in a repository
     * @var array(static)
     */
    private static $_Repository = array();

    
    /**
     * The name of the bubble
     * 
     * @var string
     */
    private $_objectName;
    
    
    /**
     * A private constructor, each object is created or retrieved by its name.
     * 
     * @param string $objectName The name of the new object
     */
    private function __construct($objectName) {
        $this->_objectName = $objectName;
    }
    /**
     * Returns an object (after creating it if necessary)
     * by its name
     * 
     * @param string $objectName The name of the object to create/retrieve
     * @return static
     */
    protected static function _GetObject($objectName) {
        if (!isset(self::$_Repository[$objectName])) {
            self::$_Repository[$objectName] = new static($objectName);
        }
        return self::$_Repository[$objectName];
    }
    
    /**
     * Returns all the bubbles (used internally to generate the javascript
     * code.
     * 
     * @return array
     */
    public static function GetAllObjects() {
        return self::$_Repository;
    }
    
    /**
     * 
     * @param string $objectName The name of the object to delete
     */
    public static function DropObject($objectName){
        if (isset(self::$_Repository[$objectName])) {
            unset(self::$_Repository[$objectName]);
        }
    }
    
    public static function InstanceNumber(){
        return count(self::$_Repository);
    }
}

