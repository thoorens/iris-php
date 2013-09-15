<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payoff\Models;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * Description of _TOrders
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


