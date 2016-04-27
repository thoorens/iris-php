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
 * Small invoice manager for test purpose: the Products table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TProducts extends _invoiceManager {
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
        self::SQLITE =>
        'CREATE TABLE products(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Description TEXT  NOT NULL,
    Price NUMBER)',
        /* ---------------------------------------------------------- */
        self::MYSQL =>
        'CREATE TABLE IF NOT EXISTS products (
  id int(11) NOT NULL AUTO_INCREMENT,
  Description varchar(100) NOT NULL,
  Price float NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
',
        self::POSTGRESQL =>
        'CREATE TABLE IF NOT EXISTS products (
  id SERIAL NOT NULL,
  Description varchar(100) NOT NULL,
  Price float NOT NULL,
  PRIMARY KEY (id)
);
'
    ];

}
