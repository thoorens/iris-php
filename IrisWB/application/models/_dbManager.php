<?php

namespace models;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class serves as an ancestor for all the families of models in
 * the application. It is able to create and populate the tables 
 * with data
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _dbManager extends \Iris\DB\_Entity {

    /**
     * The recognized DBMS
     */
    const MYSQL = 1;
    const SQLITE = 2;

    /**
     * Eachs subclasses will have its proper SQL DDL string to create a table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [];

    /**
     * Gets the current entity manager (if necessary, creates it according to
     * the mentionned type, by defaut SQLite)
     * 
     * @param string $dbType The type of database (by default sqlite)
     * @return \Iris\DB\_EntityManager
     */
    public static function GetEM() {
        $instance = \wbClasses\AutoEM::GetInstance();
        return $instance->getEm();
    }

    /**
     * These model classes do not use a standard default entity manager. They
     * use the manager corresponding to a static var in this class which can
     * be changed manually ($DefaultDbType)
     * 
     * @return \Iris\DB\_EntityManager
     */
    public static function DefaultEntityManager() {
        return static::GetEM();
    }

    /**
     * 
     * @return int
     */
    public static function GetCurrentDbType() {
        $instance = \wbClasses\AutoEM::GetInstance();
        return $instance->getDbType();
    }

    /**
     * Creates all 5 tables with data in them and returns an array
     * with the table names as keys and numbers of rows as values
     * 
     * @return array
     */
    public static function CreateAll() {
        die('This method <b>CreateAll</b> should be overwritten to be usable');
    }

    
    /**
     * Deletes the database: not necessaray for all DBMS
    
     * @param wbClasses\AutoEM
     */
    public static function DeleteFile() {
        $model = \wbClasses\AutoEM::GetInstance();
        switch ($model->getDbType()) {
            case \wbClasses\AutoEM::SQLITE:
                \Iris\DB\Dialects\Em_PDOSQLite::PurgeFile($model->getSqlParameters()['file']);
                break;
        }
    }    
    
    /**
     * Deletes all the tables and views from the database.
     */
    public static function DropAll() {
        die('This method <b>DropAll</b> should be overwritten to be usable');
    }

    /**
     * Creates un new table in the database
     */
    public static function Create() {
        $instance = \wbClasses\AutoEM::GetInstance();
        $em = $instance->getEm();
        $dbType = $instance->getDbType();
        $sql = static::$_SQLCreate[$dbType];
        $em->directSQLExec($sql);
    }

}
