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
 * Small invoice manager for test purpose: the Customers table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TCustomers extends \Iris\DB\_Entity {
    use tInvoiceEntity;
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
        \Iris\DB\_EntityManager::SQLITE =>
        'CREATE TABLE customers(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT)',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE customers(
    id INTEGER AUTO_INCREMENT NOT NULL , 
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(100),
    Email VARCHAR(100),
    PRIMARY KEY(id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ',
        \Iris\DB\_EntityManager::POSTGRESQL =>
        'CREATE TABLE customers(
    id SERIAL NOT NULL , 
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(100),
    Email VARCHAR(100),
    PRIMARY KEY(id));',
        \Iris\DB\_EntityManager::ORACLE =>
        'Still undefined'
    ];

    /**
     * SQL commands to create a copy
     * @var string
     */
    private static $_SQLCopy = [
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::SQLITE => [
            'CREATE TABLE customers2(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT);',
            'INSERT INTO customers2 SELECT * FROM customers'],
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL => [
            'CREATE TABLE customers2 AS SELECT * FROM customers;',
            'ALTER TABLE customers2 ADD CONSTRAINT PRIMARY KEY(id);',
        ]
    ];

    /**
     * A series of commands to realise a copy
     */
    public static function Copy() {
        $em = self::GetEM();
        $dbType = self::GetCurrentDbType();
        $result = $em->directSQLExec(static::$_SQLCopy[$dbType][0]);
        if (isset(static::$_SQLCopy[$dbType][1])) {
            $result2 = $em->directSQLExec(static::$_SQLCopy[$dbType][1]);
        }
        return $result > $result2 ? $result : $result2;
    }

    /**
     * Creates the table
     * 
     * @param string $type
     * @return int the number of created objects in the database
     */
    public static function CreateObjects($type) {
        self::Create($type);
        $tCustomers = self::GetEntity();
        $customerList = [
            ['Jacques Thoorens', 'rue Villette', 'irisphp@thoorens.net'],
            ['John Smith', 'Bourbon street', 'john@smith.eu'],
            ['Antonio Sanchez', 'Gran Via', \NULL]
        ];
        $elements = 0;
        foreach ($customerList as $items) {
            $customer = $tCustomers->createRow();
            $customer->Name = $items[0];
            $customer->Address = $items[1];
            $customer->Email = $items[2];
            $customer->save();
            $elements++;
        }
        return $elements;
    }

}
