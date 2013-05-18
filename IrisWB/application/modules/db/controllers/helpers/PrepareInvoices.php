<?php

namespace Iris\controllers\helpers;

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
 *
 * 
 */

/**
 * Provides a mean to create five table for a small invoice manager.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class PrepareInvoices extends \Iris\controllers\helpers\_ControllerHelper {

    protected $_singleton = TRUE;
    
    
    public function help() {
        return $this;
    }

    

    /**
     * Creates all 5 tables with data in them and returns an array
     * with the table names as keys and numbers of rows as values
     * 
     * @param string $type the rdbms type
     * @return array
     */
    public function createAll($type, $data = \TRUE) {
        $entityManager = \models\_invoiceManager::Reset($type);
        $results =  [
            "Customers" => $this->createCustomers($type, $data),
            "Products" => $this->createProducts($type, $data),
            "Invoices" => $this->createInvoices($type, $data),
            "Orders" => $this->createOrders($type, $data),
            "Events" => $this->createEvents($type, $data),
        ];
        \models\VVcustomers::Create($type, $entityManager);
        return $results;
    }

    /**
     * Creates a customer table, optionaly with data
     * 
     * @param string $type the rdbms type
     * @param boolean $withData if true, populates the table (default)
     * @return int Number of rows created
     */
    public function createCustomers($type, $withData = \TRUE) {
        \models\TCustomers::Create($type);
        $tCustomers = \models\TCustomers::GetEntity();
        $customerList = [
            ['Jacques Thoorens', 'rue Villette', 'irisphp@thoorens.net'],
            ['John Smith' , 'Bourbon street', 'john@smith.eu'],
            ['Antonio Sanchez' , 'Gran Via', \NULL]
        ];
        $elements = 0;
        if ($withData) {
            foreach ($customerList as $items) {
                $customer = $tCustomers->createRow();
                $customer->Name = $items[0];
                $customer->Address = $items[1];
                $customer->Email = $items[2];
                $customer->save();
                $elements++;
            }
        }
        return $elements;
    }

    /**
     * Creates a product table, optionaly with data
     * 
     * @param string $type the rdbms type
     * @param boolean $withData if true, populates the table (default)
     * @return int Number of rows created
     */
    public function createProducts($type, $withData = \TRUE) {
        \models\TProducts::Create($type);
        $tProducts = \models\TProducts::GetEntity();

        $productList = [
            'orange' => 0.50,
            'banana' => 0.60,
            'apple' => 0.30,
        ];
        $elements = 0;
        if ($withData) {
            foreach ($productList as $description => $price) {
                $product = $tProducts->createRow();
                $product->Description = $description;
                $product->Price = $price;
                $product->save();
                $elements++;
            }
        }
        return $elements;
    }

    /**
     * Creates a invoice table, optionaly with data
     * 
     * @param string $type the rdbms type
     * @param boolean $withData if true, populates the table (default)
     * @return int Number of rows created
     */
    public function createInvoices($type, $withData = \TRUE) {
        \models\TInvoices::Create($type);
        $tInvoices = \models\TInvoices::GetEntity();

        $invoiceList = [
            // [customer_id,date]
            [1, '2012-01-05'], // id=1
            [2, '2012-01-05'], // id=2
            [3, '2012-01-05'], // id=3
            [1, '2012-02-13'], // id=4
            [1, '2012-02-21'], // id=5
            [3, '2012-03-05'], // id=6
        ];
        $elements = 0;
        if ($withData) {
            foreach ($invoiceList as $item) {
                $invoice = $tInvoices->createRow();
                $invoice->customer_id = $item[0];
                $invoice->InvoiceDate = $item[1];
                $invoice->save();
                $elements++;
            }
        }
        return $elements;
    }

    /**
     * Creates a order table, optionaly with data (each order is a line
     * in the correspondant invoice)
     * 
     * @param string $type the rdbms type
     * @param boolean $withData if true, populates the table (default)
     * @return int Number of rows created
     */
    public function createOrders($type, $withData = \TRUE) {
        \models\TOrders::Create($type);
        $tOrders = \models\TOrders::GetEntity();
        $orderList = [
            //[invoice_id,product_id,quantity)
            [1, 1, 1],
            [1, 2, 1],
            [1, 3, 2],
            [2, 2, 1],
            [3, 3, 1],
            [3, 2, 2],
            [4, 1, 3],
            [4, 2, 1],
            [5, 1, 5],
            [5, 3, 2],
            [6, 3, 1],
        ];
        $elements = 0;
        if ($withData) {
            foreach ($orderList as $item) {
                $order = $tOrders->createRow();
                $order->invoice_id = $item[0];
                $order->product_id = $item[1];
                $order->Quantity = $item[2];
                $order->save();
                $elements++;
            }
        }
        return $elements;
    }

    /**
     * Creates a event table, optionaly with data
     * (the events concern each row of the invoices (order, shipment...)
     * 
     * @param string $type the rdbms type
     * @param boolean $withData if true, populates the table (default)
     * @return int Number of rows created
     */
    public function createEvents($type, $withData = \TRUE) {
        \models\TEvents::Create($type);
        $tEvents = \models\TEvents::GetEntity();
        $eventList = [
            // [invoice_id,product_id, Description, Moment)
            [1, 1, 'Order to wholesaler', '2011-12-27 12:00'],
            [1, 1, 'Shipment', '2012-01-05 08:00'],
            [1, 2, 'Shipment', '2012-01-05 07:30'],
            [1, 3, 'Shipment', '2012-01-05 08:00'],
            [2, 2, 'Shipment', '2012-01-05 09:00'],
            [3, 3, 'Shipment', '2012-01-05 09:05'],
            [3, 2, 'Shipment', '2012-01-05 09:10'],
            [4, 1, 'Shipment', '2012-02-13 11:00'],
            [4, 2, 'Shipment', '2012-02-13 11:00'],
            [5, 1, 'Order to wholesaler', '2012-01-18 13:00'],
            [5, 1, 'Shipment', '2012-02-21 14:00'],
            [5, 3, 'Shipment', '2012-02-21 15:00'],
            [6, 3, 'Shipment', '2012-03-04 23:00'],
        ];
        $elements = 0;
        if ($withData) {
            foreach ($eventList as $item) {
                $event = $tEvents->createRow();
                $event->invoice_id = $item[0];
                $event->product_id = $item[1];
                $event->Description = $item[2];
                $event->Moment = $item[3];
                $event->save();
                $elements++;
            }
        }
        return $elements;
    }

    
}
