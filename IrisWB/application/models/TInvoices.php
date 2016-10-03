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
 * Small invoice manager for test purpose: the Invoices table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TInvoices extends \Iris\DB\_Entity {

    use tInvoiceEntity;
    
    /**
     *
     * @var string[]
     */
    protected $_externalEntities = ['customers' => 'TCustomers2'];

    /*
     * W A R N I N G:
     * 
     * the code hereafter in this class is only used to create the table 
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
        'CREATE TABLE invoices(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    InvoiceDate DATE,
    customer_id INTEGER,
    Amount NUMBER,
    FOREIGN KEY (customer_id) REFERENCES customers(id))',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE invoices(
    id INTEGER NOT NULL AUTO_INCREMENT, 
    InvoiceDate DATE,
    customer_id INTEGER,
    Amount REAL,
    PRIMARY KEY(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci',
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
        $tInvoices = self::GetEntity();
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
}

