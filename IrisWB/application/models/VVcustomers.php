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
class VVcustomers extends \Iris\DB\ViewEntity {

    /**
     * SQL command to construct the table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [
        'sqlite' =>
        'CREATE  VIEW "main"."vcustomers" AS select * from customers WHERE id < 3;
            ',
        'mysql' => '',
        'oracle' => '
            
',
    ];

    protected $_reflectionEntity = 'customers';


    /**
     * Creates un new view in the database
     * 
     * @param string $dbType The type of database (by default sqlite)
     * @param \Iris\DB\_EntityManager em
     */
    public static function Create($dbType, $em) {
        $sql = static::$_SQLCreate[$dbType];
        $em->directSQL($sql);
    }

}


