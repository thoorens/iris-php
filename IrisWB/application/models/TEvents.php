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
 * Small invoice manager for test purpose: the Events table.
 * It has a double foreign key to orders
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TEvents extends _invoiceManager {

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
    protected static $_SQLCreate = array(
        /* ---------------------------------------------------------- */
        self::SQLITE =>
        'CREATE TABLE events(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    Description VARCHAR,
    Moment DATETIME,
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    FOREIGN KEY (invoice_id,product_id) REFERENCES orders(invoice_id,product_id))',
        /* ---------------------------------------------------------- */
        self::MYSQL =>
        'CREATE TABLE events(
    id INTEGER NOT NULL AUTO_INCREMENT NOT NULL,
    Description VARCHAR(100),
    Moment DATETIME,
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    PRIMARY KEY(id),
    FOREIGN KEY (invoice_id,product_id) REFERENCES orders(invoice_id,product_id))
    ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci '
    );

}

