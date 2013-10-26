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
 * This controller inits, presents, verifies and deletes the sample database
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class sample extends _db {

    /**
     * Show a picture of the example database structure
     */
    public function structureAction() {
        
    }

    /**
     * Deletes the database (and optionally creates new data)
     * 
     * @param string $dbType  the rdbms type
     * @param boolean $data if false does not create the data
     */
    public function initAction() {
        $this->__action = 'create';
        $result = $this->prepareInvoices()->createAll();
        if (isset($result['Error'])) {
            $this->__error = \TRUE;
        }
        else {
            $this->__error = \FALSE;
            $this->__tables = $result;
        }
        $this->dbState()->setCreated();
    }

    /**
     * Deletes the example database
     * 
     * @param string $dbType the rdbms type
     */
    public function deletedataAction() {
        $this->__Result = 'Database deleted';
        $this->setViewScriptName('status');
        \models\_invoiceManager::DeleteFile();
        $this->dbState()->setDeleted();
    }

    /**
     * Deletes the database file (only 
     */
    public function deletefileAction() {
        $this->__Result = 'The database file has been deleted';
        \models\_invoiceManager::DeleteFile();
        $this->dbState()->setDeleted();
        $this->setViewScriptName('status');
    }

    public function error($number) {
        switch ($number) {
            case 1:
                die('Erreur creation');
                break;
            case 2:
                break;
        }
    }

    public function verifyAction() {
        $em = \models\_invoiceManager::GetEM();
        $tables = $em->listTables();
        foreach ($tables as $table) {
            if ($table[0] == 'v') {
                $objects[] = [$table, 'view'];
            }
            else {
                $objects[] = [$table, 'table'];
            }
        }
        if (count($tables) == 8) {
            $this->__Objects = $objects;
            $this->__Complete = \TRUE;
        }
        else {
            $this->__Complete = \FALSE;
        }
    }

}
