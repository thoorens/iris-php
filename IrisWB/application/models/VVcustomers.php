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
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::SQLITE =>
        'CREATE  VIEW "main"."%s" AS select * from customers WHERE id %s;',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE  VIEW %s AS select * from customers WHERE id %s;',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::POSTGRESQL => 'not yet defined',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::ORACLE => 'not yet defined'
    ];

    
    /**
     * Creates two new views in the database 
     * 
     * @param string $dbType The type of database (by default sqlite)
     * @param \Iris\DB\_EntityManager em
     */
    public static function Create($em) {
        $sqlFormat = static::$_SQLCreate[_invoiceEntity::GetCurrentDbType()];
        $sql1 = sprintf($sqlFormat, 'vcustomers', '< 3');
        $em->directSQLExec($sql1);
        $sql2 = sprintf($sqlFormat, 'vcustomers2', '> 1');
        $em->directSQLExec($sql2);
    }

}


