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
 * Small invoice manager for test purpose: the vcustomers view
 * which acces all customers from the customers table having an id less than 3
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class VVcustomers extends \Iris\DB\ViewEntity {

     protected $_reflectionEntity = 'customers';

    
    /*
     * W A R N I N G:
     * 
     * the code of this class is only used to create the view and
     * its copy.
     * 
     * It is by no way an illustration of a table management
     * 
     */
    
    /**
     * SQL command to construct the view
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [
        _invoiceManager::SQLITE_NUMBER =>
        'CREATE  VIEW "main"."%s" AS select * from customers WHERE id %s;',
        _invoiceManager::MYSQL_NUMBER => 
        'CREATE  VIEW %s AS select * from customers WHERE id %s;',
    ];

    
    /**
     * Creates two new views in the database 
     * 
     * @param string $dbType The type of database (by default sqlite)
     * @param \Iris\DB\_EntityManager em
     */
    public static function Create($em) {
        $sqlFormat = static::$_SQLCreate[_invoiceManager::GetCurrentDbType()];
        $sql1 = sprintf($sqlFormat, 'vcustomers', '< 3');
        $em->directSQLExec($sql1);
        $sql2 = sprintf($sqlFormat, 'vcustomers2', '> 1');
        $em->directSQLExec($sql2);
    }

}


