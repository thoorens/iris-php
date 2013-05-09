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
 * Modèle pour les utilisateurs
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class TUsers extends \Iris\DB\_Entity {

    public static $systemUserEntity = null;
    
    protected $_idField = 'id';
    protected $_nameField = 'Username';
    protected $_emailField = 'Email';
    protected $_roleField = "Role";
    
    protected $_rowType = '\\Iris\\Users\\User';
    
    public function getIdField() {
        return $this->_idField;
    }

        
    public function getNameField() {
        return $this->_nameField;
    }
    public function getEmailField() {
        return $this->_nameField;
    }
    
    public function getRoleField(){
        return $this->_roleField;
    }
    
    /**
     *
     * @param type $name 
     * @return User
     */
    public function fetchUser($name){
//        $object = $this->fetchRow("$this->_nameField =",$name);
//        return new User($object);
        return $this->fetchRow("$this->_nameField =",$name);
    }
    
    public static function UserList(){
        $tusers = new static();
        $tusers->select(array($tusers->getIdField(),$tusers->getNameField()));
        $tusers->whereClause('TRUE');
        $users = $tusers->fetchAll();
        $nameField = $tusers->getNameField();
        foreach($users as $user){
            $list[$user->id]=$user->$nameField;
        }
        return $list;
    }
}

