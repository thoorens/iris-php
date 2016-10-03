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
 * Small invoice manager for test purpose: the Orders table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TOrders extends \Iris\DB\_Entity {
    
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
        'CREATE TABLE orders(
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    UnitPrice NUMBER,
    Quantity INTEGER,
    PRIMARY KEY(invoice_id,product_id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id))',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE orders(
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    UnitPrice FLOAT,
    Quantity INTEGER,
    PRIMARY KEY(invoice_id,product_id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id))
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
        $tOrders = self::GetEntity();
        $orderList = [
            //[invoice_id,product_id,Quantity,UnitPrice)
            [1, 1, 1, 0.5],
            [1, 2, 1, 0.6],
            [1, 3, 2, 0.3],
            [2, 2, 1, 0.6],
            [3, 3, 1, 0.3],
            [3, 2, 2, 0.6],
            [4, 1, 3, 0.5],
            [4, 2, 1, 0.6],
            [5, 1, 5, 0.5],
            [5, 3, 2, 0.3],
            [6, 3, 1, 0.3],
        ];
        $elements = 0;
        foreach ($orderList as $item) {
            $order = $tOrders->createRow();
            $order->invoice_id = $item[0];
            $order->product_id = $item[1];
            $order->Quantity = $item[2];
            //$order->UnitPrice = 1.2;
            $order->save();
            $elements++;
        }
        return $elements;
    }
}

