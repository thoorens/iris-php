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
 * A stupid table with a double key
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TNumbers extends \Iris\DB\_Entity {
    
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
     * SQL command to construct the table
     * 
     * @var string[]
     */
    protected static $_SQLCreate = [
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::SQLITE =>
        'CREATE TABLE "numbers" (
            "French" VARCHAR NOT NULL,
            "English" VARCHAR NOT NULL ,
            "Data" VARCHAR, 
            PRIMARY KEY ("French", "English"));',
        /* ---------------------------------------------------------- */
        \Iris\DB\_EntityManager::MYSQL =>
        'CREATE TABLE numbers (
            French VARCHAR(50) NOT NULL,
            English VARCHAR(50) NOT NULL ,
            Data VARCHAR(50), 
            PRIMARY KEY (French, English))
            ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;',
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
        $numberList = [
            ['un', 'one', 'first number'],
            ['deux', 'two', 'second number'],
            ['trois', 'three', 'third number'],
        ];
        $elements = 0;
        $eNumbers = self::GetEntity();
        foreach ($numberList as $item) {
            $number = $eNumbers->createRow();
            $number->French = $item[0];
            $number->English = $item[1];
            $number->Data = $item[2];
            $number->save();
            $elements++;
        }
        return $elements;
    }

}
