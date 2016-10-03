<?php

namespace models;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * This trait serves as an second ancestor for all the families of models in
 * the workbench application. It is able to create and populate the tables 
 * with data
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
trait tInvoiceEntity {

    private static $_Numbers = [
        \Iris\DB\_EntityManager::MYSQL => 1,
        \Iris\DB\_EntityManager::SQLITE => 2,
        \Iris\DB\_EntityManager::POSTGRESQL => 3,
        \Iris\DB\_EntityManager::ORACLE => 4
    ];

    /**
     * Eachs class will have its proper SQL DDL string to create a table
     * 
     * @var string[]
     */
    //protected static $_SQLCreate = [];

    /**
     * Gets the current entity manager (if necessary, creates it)
     * 
     * @return \Iris\DB\_EntityManager
     */
    public static function GetEM() {
        static $instance = \NULL;
        if (is_null($instance)) {
            $number = self::$_Numbers[self::GetCurrentDbType()];
            $instance = \Iris\DB\_EntityManager::EMByNumber($number);
        }
        return $instance;
    }

    /**
     * Returns the current db type recorded in the session global variable (by default, sqlite)
     * 
     * @return string the type name of the current database in the session context
     */
    public static function GetCurrentDbType() {
        return \Iris\Engine\Superglobal::GetSession('dbini', \Iris\DB\_EntityManager::DEFAULT_DBMS);
    }

    /*
     * ===============================================================================================================
     * The next functions are provided to create, reset or delete the databases
     * ===============================================================================================================
     */


    /*
     * The SQL command to create a new mySQL database
     * 
     * CREATE USER 'wb_user'@'localhost' IDENTIFIED BY 'wbwp';
     * GRANT USAGE ON * . * TO 'wb_user'@'localhost' IDENTIFIED BY 'wbwp' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
     * CREATE DATABASE IF NOT EXISTS `wb_db` ;
     * GRANT ALL PRIVILEGES ON `wb_db` . * TO 'wb_user'@'localhost';
     */

    /**
     * This array contains the table names of the small application
     * 
     * @var string[] the list of all the tables (in creation order)
     */
    public static $InvoicesTable = [
        "autoform",
        "customers",
        "products",
        "invoices",
        "orders",
        "events",
        "numbers",
        "numbers2",
        "numbers3"
    ];

    /**
     * Creates all 9 tables with data in them and returns an array
     * with the table names as keys and numbers of rows as values
     * 
     * @return array
     */
    public static function CreateAll() {
        $entityManager = self::GetEM();
        $type = $entityManager->Type;
        $definedTables = $entityManager->listTables();
        foreach (self::$InvoicesTable as $table) {
            if (array_search($table, $definedTables) === \FALSE) {
                $class = '\models\T' . ucfirst($table);
                $results[$table] = $class::CreateObjects($type);
            }
            else {
                $results[$table] = "existing";
            }
        }

       // \models\VVcustomers::Create($entityManager);

        return $results;
    }

    public static function CreateOne($Number) {
        
    }

    /**
     * Deletes all the tables and views from the database.
     * 
     * @param mixed $unique if not false indicate the unique table to drop
     * @return \Iris\DB\_EntityManager
     */
    public static function DropAll($unique = \FALSE) {
        $tables = array_reverse(self::$InvoicesTable);
        $dbType = self::GetCurrentDbType();
        switch ($dbType) {
            case \Iris\DB\_EntityManager::SQLITE:
                $em = static::GetEM();
                $dropTable = "DROP TABLE %s;";
                $dropView = "DROP VIEW %s;";
                break;
            case \Iris\DB\_EntityManager::MYSQL:
                $em = static::GetEM();
                $dropView = "DROP VIEW IF EXISTS %s;";
                $dropTable = "DROP TABLE IF EXISTS %s;";
                break;
            case \Iris\DB\_EntityManager::POSTGRESQL:
                $em = static::GetEM();
                $dropView = "DROP VIEW IF EXISTS %s;";
                $dropTable = "DROP TABLE IF EXISTS %s CASCADE;";
                break;
            case \Iris\DB\_EntityManager::ORACLE:
                break;
        }
        try {
            foreach ($tables as $table) {
                if ($unique === \FALSE or $table === $unique) {
                    // the views are only deleted when the corresponding table is deleted
                    if ($table === 'customers') {
//                        $em->directSQLExec(sprintf($dropView, 'vcustomers'));
//                        $em->directSQLExec(sprintf($dropView, 'vcustomers2'));
                    }
                    $em->directSQLExec(sprintf($dropTable, $table));
                }
            }
        }
        catch (\Exception $exc) {
            throw new \Iris\Exceptions\DBException('Error in droping a table or view');
        }
        return $em;
    }

    /**
     * Deletes the database: not necessaray for all DBMS

     * @param wbClasses\AutoEM
     */
    public static function DeleteFile($type) {
        $type = self::GetCurrentDbType();
        switch ($type) {
            case \Iris\DB\_EntityManager::SQLITE:
                $fileName = self::GetEM()->getFileName();
                \Iris\DB\Dialects\Em_PDOSQLite::PurgeFile($fileName);
                break;
        }
    }

    /**
     * Deletes all the tables and views from the database.
     */
//    public static function DropAll() {
//        \Iris\Engine\Debug::Abort('This method <b>DropAll</b> should be overwritten to be usable');
//    }

    /**
     * Creates un new table in the database
     */
    public static function Create($dbType) {
        $sql = static::$_SQLCreate[$dbType];
        self::GetEM()->directSQLExec($sql);
    }

    /**
     * Creates a copy of customers table
     * 
     * @param type $type
     * @return type
     */
    private static function _CreateCustomers2() {
        return \models\TCustomers::Copy();
    }

}
