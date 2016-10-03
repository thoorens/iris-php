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
 * Small invoice manager for test purpose: the Events table.
 * It has a double foreign key to orders
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TEvents extends \Iris\DB\_Entity{
    use tInvoiceEntity;

    /*
     * W A R N I N G:
     * 
     * the code of this class is only used to create the table
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
        'CREATE TABLE events(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    Description VARCHAR,
    Moment DATETIME,
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    FOREIGN KEY (invoice_id,product_id) REFERENCES orders(invoice_id,product_id))',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE events(
    id INTEGER NOT NULL AUTO_INCREMENT NOT NULL,
    Description VARCHAR(100),
    Moment DATETIME,
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    PRIMARY KEY(id),
    FOREIGN KEY (invoice_id,product_id) REFERENCES orders(invoice_id,product_id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::POSTGRESQL => 'not yet defined',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::ORACLE => 'not yet defined'
        ];

    
    /**
     * Creates the table
     * 
     * @param string $type
     * @return int the number of created objects in the database
     */
    public static function CreateObjects($type) {
        self::Create($type);
        $tEvents = self::GetEntity();
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

