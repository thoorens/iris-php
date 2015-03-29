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
 * Small library manager for test purpose: abstract class implemented in
 * 3 concretes classes corresponding to the 3 tables involved in incoice
 * management. In the present state, only SQlite is taken into account.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _libraryManager extends _wbmodels {
    

    /**
     * By default, invoices use Sqlite
     * 
     * @var int
     */
    protected static $_DefaultDbType = self::SQLITE;
    //protected static $_DefaultDbType = self::MYSQL;
    

    /**
     * The default file name for Sqlite database. The full name will be completed by EMFactory
     * 
     * @var string
     */
    protected static $_SQLiteFileName = 'library/IrisWB/application/config/base/library.sqlite';

    /*
     * The SQL command to create a new mySQL database
     * 
     * CREATE USER 'wb_user'@'localhost' IDENTIFIED BY 'wbwp';
     * GRANT USAGE ON * . * TO 'wb_user'@'localhost' IDENTIFIED BY 'wbwp' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
     * CREATE DATABASE IF NOT EXISTS `wb_db` ;
     * GRANT ALL PRIVILEGES ON `wb_db` . * TO 'wb_user'@'localhost';
     * 
     */
    // The connection parameters for mySQL may be changed if necessary
//    protected static $_MySQLParams = [
//        'base' => 'baseName',
//        'user' => 'username',
//        'password' => 'password'
//    ];

    

    /**
     * Creates all 5 tables with data in them and returns an array
     * with the table names as keys and numbers of rows as values
     * 
     * @return array
     */
    public static function CreateAll() {
        $entityManager = static::GetEM();
        $tables = $entityManager->listTables();
        if (count($tables)) {
            $results['Error'] = \TRUE;
        }
        else {
            $results = [
                
            ];
            \models\VVcustomers::Create($entityManager);
        }
        return $results;
    }
    
    

    /**
     * Deletes all the tables and views from the database.
     */
    public static function DropAll() {
        $tables = [
            
        ];
        $dbType = self::$_DefaultDbType;
        switch ($dbType) {
            case self::SQLITE:
                $em = static::GetEM();
                $dropTable = "DROP TABLE %s;";
                $dropView = "DROP VIEW %s;";
                break;
            case self::MYSQL:
                $em = static::GetEM();
                $dropView = "DROP VIEW IF EXISTS %s;";
                $dropTable = "DROP TABLE IF EXISTS %s;";
        }
        try {
            $em->directSQLExec(sprintf($dropView, 'vcustomers'));
            $em->directSQLExec(sprintf($dropView, 'vcustomers2'));
            foreach ($tables as $table) {
                $em->directSQLExec(sprintf($dropTable, $table));
            }
        }
        catch (\Exception $exc) {
        }
        return $em;
    }

    /**
     * Creates a customer table
     * 
     * @return int Number of rows created
     */
    private static function _CreateBooks() {
        \models\TBooks::Create();
        $tBooks = \models\TBooks::GetEntity();
        $bookList = [
            ['Jacques Thoorens', 'rue Villette', ],
            ['John Smith', 'Bourbon street'],
            ['Antonio Sanchez', 'Gran Via', \NULL]
        ];
        $elements = 0;
        foreach ($bookList as $book) {
            $customer = $tBooks->createRow();
            $customer->Name = $book[0];
            $customer->Address = $book[1];
            $customer->save();
            $elements++;
        }
        return $elements;
    }

    /**
     * 
     * @param type $type
     * @return type
     */
    private static function _CreateCustomers2() {
        return \models\TCustomers::Copy();
    }

    /**
     * Creates a product table
     * 
     * @return int Number of rows created
     */
    private static function _CreateProducts() {
        \models\TProducts::Create();
        $tProducts = \models\TProducts::GetEntity();

        $productList = [
            'orange' => 0.50,
            'banana' => 0.60,
            'apple' => 0.30,
        ];
        $elements = 0;
        foreach ($productList as $description => $price) {
            $product = $tProducts->createRow();
            $product->Description = $description;
            $product->Price = $price;
            $product->save();
            $elements++;
        }
        return $elements;
    }

    /**
     * Creates the invoice table
     * 
     * @return int Number of rows created
     */
    private static function _CreateInvoices() {
        \models\TInvoices::Create();
        $tInvoices = \models\TInvoices::GetEntity();

        $invoiceList = [
            // [customer_id,date]
            [1, '2012-01-05'], // id=1
            [2, '2012-01-05'], // id=2
            [3, '2012-01-05'], // id=3
            [1, '2012-02-13'], // id=4
            [1, '2012-02-21'], // id=5
            [3, '2012-03-05'], // id=6
        ];
        $elements = 0;
        foreach ($invoiceList as $item) {
            $invoice = $tInvoices->createRow();
            $invoice->customer_id = $item[0];
            $invoice->InvoiceDate = $item[1];
            $invoice->save();
            $elements++;
        }
        return $elements;
    }

    /**
     * Creates the order table (each order is a line
     * in the correspondant invoice)
     * 
     * @return int Number of rows created
     */
    private static function _CreateOrders() {
        \models\TOrders::Create();
        $tOrders = \models\TOrders::GetEntity();
        $orderList = [
            //[invoice_id,product_id,quantity)
            [1, 1, 1],
            [1, 2, 1],
            [1, 3, 2],
            [2, 2, 1],
            [3, 3, 1],
            [3, 2, 2],
            [4, 1, 3],
            [4, 2, 1],
            [5, 1, 5],
            [5, 3, 2],
            [6, 3, 1],
        ];
        $elements = 0;
        foreach ($orderList as $item) {
            $order = $tOrders->createRow();
            $order->invoice_id = $item[0];
            $order->product_id = $item[1];
            $order->Quantity = $item[2];
            $order->save();
            $elements++;
        }
        return $elements;
    }

    /**
     * Creates the event table
     * (the events concern each row of the invoices (order, shipment...)
     * 
     * @return int Number of rows created
     */
    private static function _CreateEvents() {
        \models\TEvents::Create();
        $tEvents = \models\TEvents::GetEntity();
        $eventList = [
            // [invoice_id,product_id, Description, Moment)
            [1, 1, 'Order to wholesaler', '2011-12-27 12:00'],
            [1, 1, 'Shipment', '2012-01-05 08:00'],
            [1, 2, 'Shipment', '2012-01-05 07:30'],
            [1, 3, 'Shipment', '2012-01-05 08:00'],
            [2, 2, 'Shipment', '2012-01-05 09:00'],
            [3, 3, 'Shipment', '2012-01-05 09:05'],
            [3, 2, 'Shipment', '2012-01-05 09:10'],
            [4, 1, 'Shipment', '2012-02-13 11:00'],
            [4, 2, 'Shipment', '2012-02-13 11:00'],
            [5, 1, 'Order to wholesaler', '2012-01-18 13:00'],
            [5, 1, 'Shipment', '2012-02-21 14:00'],
            [5, 3, 'Shipment', '2012-02-21 15:00'],
            [6, 3, 'Shipment', '2012-03-04 23:00'],
        ];
        $elements = 0;
        foreach ($eventList as $item) {
            $event = $tEvents->createRow();
            $event->invoice_id = $item[0];
            $event->product_id = $item[1];
            $event->Description = $item[2];
            $event->Moment = $item[3];
            $event->save();
            $elements++;
        }
        return $elements;
    }


}

