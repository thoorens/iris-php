<?php

namespace models;

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
 * Small invoice manager for test purpose: abstract class implemented in
 * 3 concretes classes corresponding to the 3 tables involved in incoice
 * management. In the present state, only SQlite is taken into account.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _invoiceManager extends \Iris\DB\_Entity {

    /**
     * Eachs subclasses will have its proper SQL DDL string to create a table
     * @var array
     */
    protected static $_SQLCreate = array();
    private static $_Em = \NULL;
    private static $_File = '/library/IrisWB/application/config/base/invoice.sqlite';

    /**
     * Gets the current entity manager (if necessary, creates it according to
     * the mentionned type, by defaut SQLite)
     * 
     * @param string $dbType The type of database (by default sqlite)
     * @return \Iris\DB\_EntityManager
     */
    public static function GetEM($dbType='sqlite') {
        if (is_null(self::$_Em)) {
            switch ($dbType) {
                case 'sqlite':
                    $file = self::$_File;
                    self::$_Em = \Iris\DB\_EntityManager::EMFactory("sqlite:$file");
            }
        }
        return self::$_Em;
    }

    
    public static function DefaultEntityManager() {
        return self::GetEM();
    }

    /**
     * Resets the database to an initial state (no table).
     * 
     * @param string $dbType The type of database (by default sqlite)
     */
    /**
     * 
     * @param type $dbType
     * @return \Iris\DB\_EntityManager
     */
    public static function Reset($dbType) {
        switch ($dbType) {
            case 'sqlite':
                // in SQlite, only delete the database file
                if (file_exists(IRIS_ROOT_PATH . self::$_File)) {
                    unlink(IRIS_ROOT_PATH . self::$_File);
                }
                break;
        }
        return self::GetEM($dbType);
    }

    /**
     * Creates un new table in the database
     * @param string $dbType The type of database (by default sqlite)
     */
    public static function Create($dbType) {
        $em = self::GetEM($dbType);
        $sql = static::$_SQLCreate[$dbType];
        $em->directSQL($sql);
    }

    

}


