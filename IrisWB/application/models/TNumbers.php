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
 * A stupid table with a double key
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TNumbers extends _invoiceManager {
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
        'CREATE TABLE "numbers" (
            "French" VARCHAR NOT NULL,
            "English" VARCHAR NOT NULL ,
            "Data" VARCHAR, 
            PRIMARY KEY ("French", "English"));',
        /* ---------------------------------------------------------- */
        self::MYSQL =>
        'CREATE TABLE numbers (
            French" VARCHAR(50) NOT NULL,
            English VARCHAR(50) NOT NULL ,
            Data VARCHAR(50), 
            PRIMARY KEY (French, English));',
    ];

    public static function Create() {
        parent::Create();
        $data = [
            ['un', 'one', 'first number'],
            ['deux', 'two', 'second number'],
            ['trois', 'three', 'third number'],
        ];
        $eNumbers = self::GetEntity();
        foreach($data as $item){
            $number = $eNumbers->createRow();
            $number->French = $item[0];
            $number->English = $item[1];
            $number->Data = $item[2];
            $number->save();
        }
    }

    
}
