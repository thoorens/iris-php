<?php

namespace modules\db\controllers;

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
 * Description of entities
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class entities extends _db {
    /*
     * This portion of code is repeated from simple.php for readability
     */

    private $_entityManager;

    public function _init() {
        $dsn = "sqlite:/library/IrisWB/application/config/base/invoice.sqlite";
        $user = $password = ''; // no user for Sqlite
        $this->_entityManager = \Iris\DB\_EntityManager::EMFactory($dsn, $user, $password);
        $this->__tables = $this->_entityManager->listTables();
    }

    /* end of repeated code */

    public function newAction() {
        // first call
        $eCustomers = new \models\TCustomers($this->_entityManager);
        $this->__customer = $eCustomers->find(1);
        try {
            $eCustomers2 = new \models\TCustomers($this->_entityManager);
            $this->__customer2 = $eCustomers->find(2);
        }
        catch (\Iris\Exceptions\_Exception $ex) {
            $this->__warning = $ex->getMessage();
        }
    }

    public function getAction() {
        //iris_debug($this->_entityManager);
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $this->__Title = "Brol";
        // first call
        //$eCustomers = new \models\TCustomers($this->_entityManager);
        //$eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        //iris_debug($eCustomers);
        $this->__customer = $eCustomers->find(1);
        try {
            $eCustomers2 = new \models\TCustomers($this->_entityManager);
        }
        catch (\Iris\Exceptions\_Exception $ex) {
            $this->__warning = $ex->getMessage();
        }
    }
}
