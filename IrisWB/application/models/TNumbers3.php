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
 * A stupid table with a double foreign key
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TNumbers3 extends \Iris\DB\_Entity {
    
    use tInvoiceEntity;
    
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
     * The foreign keys if not defined in the databases. Each
     * 
     * @var array
     * @todo This var does not seem to be initialized
     */
    protected $_foreignKeyDescriptions = [
        0 => [
            0 => 'TNumbers',
            1 => ['English, French'],
            2 => ['English, French'],
        ]
    ];

    /**
     * SQL command to construct the table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::SQLITE =>
        'CREATE TABLE "numbers3" (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
            "French" VARCHAR NOT NULL,
            "English" VARCHAR NOT NULL);',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE numbers3 (
            id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
            French VARCHAR(50) NOT NULL,
            English VARCHAR(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::POSTGRESQL => 'not yet defined',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::ORACLE => 'not yet defined'
    ];

    public static function CreateObjects($type) {
        self::Create($type);
        $numberList = [
            [1, 'un', 'one'],
            [2, 'deux', 'two'],
            [3, 'un', 'one'],
        ]; 
        $eNumbers = self::GetEntity();
        $elements = 0;
//        foreach ($numberList as $item) {
//            $number = $eNumbers->createRow();
//            $number->French = $item[1];
//            $number->English = $item[2];
//            $number->save();
//            $elements++;
//        }
        return $elements;
    }

}
