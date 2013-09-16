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
 * Two main purposes<ul>
 * <li>manage a role (and its ancestors)
 * <li> manage the role collection
 * </ul>
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Role {
    
    /** ========================================================================
     *  Static part
     *  ========================================================================
     */
    
    
    /**
     * An array containing all existing roles
     * @var Role[]
     */
    protected static $_Collection = array();
    
    /**
     * Returns a list of the existing roles in the application
     * (form administrative purpose).
     * 
     * @return array(Role)
     */
    public static function GetCollection() {
        return self::$_Collection;
    }

    /**
     * Return the role from its name
     * 
     * @param string $name
     * @return Role 
     */
    public static function GetRole($name) {
        if (!isset(self::$_Collection[$name])) {
            throw new \Iris\Exceptions\UserException('Inexisting role' . $name);
        }
        return self::$_Collection[$name];
    }
        
    /**
     * Creates the role collection from a config
     * 
     * @param \Iris\SysConfig\Config $config 
     */
    public static function Init(\Iris\SysConfig\Config $config) {
        foreach ($config as $role => $ancestors) {
            $ancestors = $ancestors=='' ? array() : explode(',', $ancestors);
            $role = new Role($role, $ancestors); // self register in $_Collection
        }
    }

    /** ========================================================================
     *  Object part
     *  ========================================================================
     */

    /**
     * An array containing all the parent names of
     * a role
     * @var string[] 
     */
    protected $_parents = array();
    
    /**
     * The role name
     * @var string 
     */
    protected $_name;

    /**
     * Creates a new role, with its name and list of parent names,
     * and register it in the collection
     * 
     * @param string $name
     * @param string[] $parentNames 
     */
    public function __construct($name, $parentNames = array()) {
        $this->_name = $name;
        self::$_Collection[$name] = $this;
        foreach ($parentNames as $parentName) {
            if ($parentName != 'null') {
                $this->_parents[$parentName] = $parentName;
            }
        }
    }

    /**
     * Returns the ancestor names of the role
     * @return array(string) 
     */
    public function getAncestors() {
        $ancestorNames = array();
        foreach ($this->_parents as $parentName) {
            $ancestorNames[] = $parentName;
            $parent = self::GetRole($parentName);
            $newAncestors = $parent->getAncestors();
            $ancestorNames = array_merge($ancestorNames, $newAncestors);
        }
        return $ancestorNames;
    }
    
    /**
     * Returns the role name
     * @return string 
     */
    public function getName() {
        return $this->_name;
    }

    

}


