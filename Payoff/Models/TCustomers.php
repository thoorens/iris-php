<?php

namespace Payoff\Models;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class TCustomers extends \Iris\DB\_Entity {

    public static function CreateTable($type) {
        switch ($type) {
            case 'sqlite':
                self::_CreateSqlite();
                break;
        }
    }

    private static function _CreateSqlite() {
        $SQL = <<< SQL
CREATE TABLE "customers" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
    "Name" VARCHAR, 
    "Address" VARCHAR);
SQL;
    }
    
   
}


