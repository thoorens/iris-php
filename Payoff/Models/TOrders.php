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
class TOrders extends \Iris\DB\_Entity {

    public static $DbType = 'sqlite';
    public static $FileNameTemplate = "%s/config/base/invoice.sqlite";

    public static function CreateTable() {
        switch (self::$DbType) {
            case 'sqlite':
                self::_CreateSqlite();
                break;
        }
    }

    public static function GetDefaultEntityManager() {
        switch (self::$DbType) {
            case 'sqlite':
                $fileName = sprintf(self::$FileNameTemplate,IRIS_PROGRAM_PATH);
                if(!file_exists($fileName)){
                    \Iris\OS\_OS::GetInstance()->touch($fileName);
                }
                return \Iris\DB\_EntityManager::EMFactory("sqlite:$fileName");
                break;
        }
    }

    private static function _CreateSqlite() {
        //$dns = sprintf(self::$DnsTemplate,)
        $SQL = <<< SQL
CREATE TABLE "orders" (
    "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
    "Amount" FLOAT, 
    "Fees" FLOAT, 
    "Tax" FLOAT, 
    "TargetCount" VARCHAR, 
    "Currency" VARCHAR, 
     "customer_id" INTEGER, FOREIGN KEY (customer_id) REFERENCES customers(id));                
SQL;
    }

    /**
     * 
     * @param \Payoff\Paypal\Order $order
     */
    public static function InsertOrder($order) {
        try {
            $eOrder = self::GetEntity(self::GetDefaultEntityManager());
        }
        catch (Exception $exc) {
            die('OK pas de tables');
        }

        $newOrder = $eOrder->createRow();
        $newOrder->Amount = $order->getAmount();
        $newOrder->Fees = $order->getShipping();
        $newOrder->Tax = $order->getTax();
        $newOrder->Currency = $order->getCurrencyCode();
        $eCustomer = \models\TCustomers::GetEntity();
        $name = $order->getName();
        $address = $order->getAddress();
        $eCustomer->where('Name=', $name);
        $customers = $eCustomer->fetchAll();
        $customer = \NULL;
        foreach ($customers as $candidate) {
            if ($candidate->Address == $address) {
                $customer = $candidate;
                break;
            }
        }
        if (is_null($customer)) {
            $customer = $eCustomer->createRow();
            $customer->Name = $name;
            $customer->Address = $address;
            $customer->save();
        }
        $newOrder->customer_id = $customer->id;
    }

}

?>
