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
        //$this->dbOpen(); // a call to a helper
    }

    /**
     * Creates (if necessary) all the tables and views for the demo
     * 
     * @param string $dbType  the rdbms type
     * @param boolean $data if false does not create the data
     */
    public function initAction() {
        $this->__action = 'create';
        $result = \models\TCustomers::CreateAll();
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
    public function deletedataAction($unique = \FALSE) {
        $this->dbOpen(); // a call to a helper
        $this->__Result = 'Database deleted';
        $this->setViewScriptName('status');
        \models\TCustomers::DropAll($unique);
        $this->dbState()->setDeleted();
    }

    /**
     * Deletes the database file (only in Sqlite context)
     */
    public function deletefileAction() {
        /* @var $em \Iris\DB\_EntityManager */
        $em = $this->dbOpen(); // a call to a helper
        $this->__Result = 'The database file has been deleted';
        $type = $em->Type;
//        \models\_invoiceEntity::DropAll();
        $this->dbState()->setDeleted();
        $this->setViewScriptName('status');
    }

    /**
     * A way to qhickly verify that the database is working
     */
    public function verifyAction() {
        $em = $this->dbOpen(\TRUE); // a call to a helper
        $expectedTables = \models\TInvoices::$InvoicesTable;
        $tables = $em->listTables();
        $tNumber = $vNumber = 0;
        foreach ($expectedTables as $table) {
            if (array_search($table, $tables)) {
                if ($table[0] == 'v') {
                    $objects[] = [$table, 'view'];
                    $vNumber++;
                }
                else {
                    $objects[] = [$table, 'table'];
                    $tNumber++;
                }
            }
            else {
                $objects[] = [$table, 'not found'];
            }
        }
        $this->__Objects = $objects;
        $this->__tables = $tNumber;
        $this->__views = $vNumber;
        if ($tNumber + $vNumber == count($expectedTables)) {
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
     * This action only display 4 links to permit the change tof
     * a new database management system
     */
    public function changeAction() {
        $buttons['mySQL'] = \Iris\DB\_EntityManager::MYSQL;
        $buttons['SQLite'] = \Iris\DB\_EntityManager::SQLITE;
        $buttons['PostgreSQL'] = \Iris\DB\_EntityManager::POSTGRESQL;
        $buttons['Oracle'] = \Iris\DB\_EntityManager::ORACLE;
        $this->__buttons = $buttons;
    }

    /**
     * Puts the database name for sqlite
     * in the session variable
     */
    public function sqliteAction() {
        //$this->deletefileAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::SQLITE;
        $this->reroute('/db/sample/structure');
    }

    /**
     * Puts the database name for postgresql
     * in the session variable
     */
    public function postgresqlAction() {
        //$this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::POSTGRESQL;
        $this->reroute('/db/sample/structure');
    }

    /**
     * Puts the database name for mySQL (or MariaDB)
     * in the session variable
     */
    public function mysqlAction() {
        //$this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::MYSQL;
        $this->reroute('/db/sample/structure');
    }

    /**
     * Puts the database name for Oracle
     * in the session variable
     */
    public function oracleAction() {
        //$this->deletedataAction();
        $session = \Iris\Users\Session::GetInstance();
        $session->dbini = \Iris\DB\_EntityManager::ORACLE;
        $this->reroute('/db/sample/structure');
    }

    public function testAction() {
        $value = \Iris\Engine\Superglobal::GetSession('dbini', \Iris\DB\_EntityManager::DEFAULT_DBMS);
        $this->__data = $value;
    }

}
