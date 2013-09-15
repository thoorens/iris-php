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
 * A concrete implementation of iUser interface based on a database
 * with a TUsers special entity.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class User extends \Iris\DB\Object implements iUser {
    use tUser;
    

    public function getEmailAddress() {
        $emailField = $this->_entity->getEmailField();
        return $this->$emailField;
    }

    public function getName() {
        $nameField = $this->_entity->getNameField();
        return $this->$nameField;
    }

    public function getRole() {
        $roleField = $this->_entity->getRoleField();
        $role = $this->$roleField;
        return $role;
    }

    public function getId() {
        $idField = $this->_entity->getIdField();
        return $this->$idField;
    }

}


