<?php

namespace modules\db\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This controller inits, presents, verifies and deletes the sample database
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class sample extends _db {

    protected function _init() {
        $this->setDefaultScriptDir('sample');
    }

    /**
     * Show a picture of the example database structure
     */
    public function structureAction() {
        $this->dbOpen(); // a call to a helper
    }

    /**
     * Creates (if necessary) all the tables and views for the demo
     * 
     * @param string $dbType  the rdbms type
     * @param boolean $data if false does not create the data
     */
    public function initAction() {
        $this->__action = 'create';
        $result = \models\_invoiceManager::CreateAll();
        if (isset($result['Error'])) {
            $this->__error = \TRUE;
        }
        else {
            $this->__error = \FALSE;
            $this->__tables = $result;
        }
        $this->dbState()->setCreated(); // a call to a helper
    }

    /**
     * Deletes the example database
     * 
     * @param string $dbType the rdbms type
     */
    public function deletedataAction() {
        $this->dbOpen(); // a call to a helper
        $this->__Result = 'Database deleted';
        $this->setViewScriptName('status');
        \models\_invoiceManager::DropAll();
        $this->dbState()->setDeleted();
    }

    /**
     * Deletes the database file (only in Sqlite context)
     */
    public function deletefileAction() {
        $this->dbOpen(); // a call to a helper
        $this->__Result = 'The database file has been deleted';
        \models\_invoiceManager::DeleteFile(\wbClasses\AutoEM::GetInstance()->getDbType());
        $this->dbState()->setDeleted();
        $this->setViewScriptName('status');
    }

    /**
     * A way to qhickly verify that the database is working
     */
    public function verifyAction() {
        $db = $this->dbOpen(\TRUE); // a call to a helper
        $em = $db->getEm();
        //iris_debug($em->listTables());
        $tables = $em->listTables();
        foreach ($tables as $table) {
            if ($table[0] == 'v') {
                $objects[] = [$table, 'view'];
            }
            else {
                $objects[] = [$table, 'table'];
            }
        }
        if (count($tables) > 0) {
            $this->__Objects = $objects;
            $this->__Complete = \TRUE;
        }
        else {
            $this->__Complete = \FALSE;
        }
    }

    /**
     * The sequence table offers a warning before going to next screen: deleleall!
     */
    public function warningAction() {
        $this->redirect('verify');
    }

    /**
     * This action only display some links to permit the change tof
     * a new database management system
     */
    public function changeAction() {
        
    }

    /**
     * Changes some parameter in the current session to prepare a new 
     * database for Sqlite
     */
    public function sqliteAction() {
        $this->deletefileAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \models\_dbManager::SQLITE_NUMBER;
//      $session->entityType = \wbClasses\AutoEM::SQLITE;
//      $session->SQLParams = ['file' => 'library/IrisWB/application/config/base/invoice.sqlite'];
//     $session->entityType = \Iris\DB\_EntityManager::SQLITE;
        //$session->SQLParams = ['file' => 'library/IrisWB/application/config/base/invoice.sqlite'];
        $this->reroute('/db/sample/structure');
    }

    public function postgresqlAction() {
        $this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \models\_dbManager::POSTGRESQL_NUMBER;
//        $session->entityType = \Iris\DB\_EntityManager::POSTGRESQL;
//        $session->entityType = \wbClasses\AutoEM::POSTGRESQL;
//        $session->SQLParams = [
//            'host' => 'localhost',
//            'base' => 'wb_db',
//            'user' => 'wb_user',
//            'password' => 'wbwp'];
        $this->reroute('/db/sample/structure');
    }

    /**
     * Changes some parameter in the current session to prepare a new 
     * database for mySQL (or MariaDB)
     */
    public function mysqlAction() {
        $this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \models\_dbManager::MYSQL_NUMBER;
//        $session->entityType = \Iris\DB\_EntityManager::MYSQL;
//        $session->entityType = \wbClasses\AutoEM::MYSQL;
//        $session->SQLParams = [
//            'host' => 'localhost',
//            'base' => 'wb_db',
//            'user' => 'wb_user',
//            'password' => 'wbwp'];
        $this->reroute('/db/sample/structure');
    }

}
