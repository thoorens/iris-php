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
 * Small invoice manager for test purpose: the Customers table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TCustomers extends _invoiceManager {

    /*
     * W A R N I N G:
     * 
     * the code of this class is only used to create the table and
     * its copy.
     * 
     * It is by no way an illustration of a table management
     * 
     */
    
    
    
    /**
     * SQL command to construct the table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [
        /* ---------------------------------------------------------- */
        self::SQLITE =>
        'CREATE TABLE customers(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT)',
        /* ---------------------------------------------------------- */
        self::MYSQL =>
        'CREATE TABLE customers(
    id INTEGER AUTO_INCREMENT NOT NULL , 
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(100),
    Email VARCHAR(100),
    PRIMARY KEY(id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ',
    ];

    /**
     * SQL commands to create a copy
     * @var string
     */
    private static $_SQLCopy = [
        /* ---------------------------------------------------------- */
        self::SQLITE => [
            'CREATE TABLE customers2(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT);',
            'INSERT INTO customers2 SELECT * FROM customers'],
        /* ---------------------------------------------------------- */
        self::MYSQL =>[
        'CREATE TABLE customers2 AS SELECT * FROM customers;',
        'ALTER TABLE customers2 ADD CONSTRAINT PRIMARY KEY(id);',    
            ]
    ];

    /**
     * A series of commands to realise a copy
     */
    public static function Copy() {
        $em = self::GetEM();
        $dbType = self::$_DefaultDbType;
        _invoiceManager::Say('Making a copy of customers');
        $result = $em->directSQL(static::$_SQLCopy[$dbType][0], \FALSE);
        if (isset(static::$_SQLCopy[$dbType][1])) {
            $result2 = $em->directSQL(static::$_SQLCopy[$dbType][1], \FALSE);
        }
        return $result > $result2 ? $result : $result2;
    }

}

