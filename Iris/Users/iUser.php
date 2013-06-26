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
 *
 */

/**
 * The interface iUser specifies all methods required by User classes
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
interface iUser {
    
    /**
     * Accessor get for the id
     * @return string
     */
    public function getId();
    /**
     * Accessor get for the name
     * 
     * @return string 
     */
    public function getName();
    
    /**
     * Accessor get for the email address
     * 
     * @return string 
     */
    public function getEmailAddress();
   
    /**
     * Acessor get for the role
     * 
     * @return string
     */
    public function getRole();
    
    /**
     * Determines if the connected user has a role (strickly or by inheritance)
     * The alias playRole and hasRole_Strict methods may be used for more clarity.
     * 
     * @param string $expectedRole the role concerned by the test
     * @param boolean $strict true if no inheritance accepted
     * @return boolean
     */
    public function hasRole($role,$strict=TRUE);
    
     /**
     * Test a role accepting inheritance
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function playRole($expectedRoleName);
    
    /**
     * Test a role strictly
     * 
     * @param string $expectedRoleName the role concerned by the test
     * @return boolean 
     */
    public function hasRole_Strict($expectedRoleName);
    
 
}
