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
 * Small invoice manager for test purpose: the Invoices table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TInvoices extends _invoiceManager {

    /**
     *
     * @var string[]
     */
    protected $_externalEntities = ['customers' => 'TCustomers2'];
    
    /**
     * SQL command to construct the table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = array(
        'sqlite' =>
        'CREATE TABLE invoices(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    InvoiceDate DATE,
    customer_id INTEGER,
    Amount NUMBER,
    FOREIGN KEY (customer_id) REFERENCES customers(id))',
        'mysql' => ''
    );

    
}


