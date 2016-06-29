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
 * Small invoice manager for test purpose: the Orders table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TOrders extends _invoiceManager {
    
    
    
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
        self::SQLITE_NUMBER =>
        'CREATE TABLE orders(
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    UnitPrice NUMBER,
    Quantity INTEGER,
    PRIMARY KEY(invoice_id,product_id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id))',
        /* ---------------------------------------------------------- */
        self::MYSQL_NUMBER =>
        'CREATE TABLE orders(
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    UnitPrice FLOAT,
    Quantity INTEGER,
    PRIMARY KEY(invoice_id,product_id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ',
    ];

}

