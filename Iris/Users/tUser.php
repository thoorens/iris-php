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
 * @copyright 2011-2013 Jacques THOORENS
 *
 */

/**
 * This trait implements the methods of the iUser interface iUser 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tUser {

    /**
     * The email address of the current user
     * @var string
     */
    protected $_mailAddress;
    /**
     * The user's name
     * @var string
     */
    protected $_name;
    /**
     * The role of the user
     * @var string
     */
    protected $_role;
    /**
     * The user's id
     * @var string
     */
    protected $_id;
    /**
     * The time value of the last activity for the current user
     * 
     * @var $string
     */
    protected $_timer;
    /**
     * The number of 
     * @var int 
     */
    
    /**
     * Accessor get for the id
     * @return string
     */
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Accessor get for the email address
     * 
     * @return string 
     */
    public function getEmailAddress() {
        return $this->_mailAddress;
    }

    /**
     * Accessor get for the name
     * 
     * @return string 
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Acessor get for the role
     * 
     * @return string
     */
    public function getRole() {
        return $this->_role;
    }

     /**
     * Test a role accepting inheritance
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function playRole($expectedRoleName){
        return $this->hasRole($expectedRoleName, FALSE);
    }
    
    /**
     * Test a role strictly
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function hasRole_Strict($expectedRoleName){
        return $this->hasRole($expectedRoleName, TRUE);
    }
    
    /**
     * Determines if the connected user has a role (strickly or by inheritance)
     * The alias playRole and hasRole_Strict methods may be used for more clarity.
     * 
     * @param string $expectedRole the role concerned by the test
     * @param boolean $strict true if no inheritance accepted
     * @return boolean
     */
    public function hasRole($expectedRoleName, $strict=TRUE) {
        $roleName = $this->getRole();
        $hasIt = $expectedRoleName == $roleName;
        if (($strict or $hasIt)) {
            return $hasIt;
        }
        $role = \Iris\Users\Role::GetRole($roleName);
        $ancetres = $role->getAncestors();
        return array_search($expectedRoleName, $ancetres) !== FALSE;
    }

    
}


