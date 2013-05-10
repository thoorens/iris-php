<?php

namespace modules\db\controllers;

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
 * 
 * Test of basic database operations
 * 
 * @author jacques
 * @license not defined
 */
class show extends _db {

    

    protected function _init() {
        //
        $this->dbState()->validateDB();
    }

    /**
     * Displays all the invoices (with full details)
     * 
     * @param type $dbType
     */
    public function invoicesAction($dbType = 'sqlite') {
        $tInvoices = \models\TInvoices::GetEntity();
        $invoices = $tInvoices->fetchAll();
        foreach ($invoices as $invoice) {
            $invs[] = $this->_readInvoice($invoice);
        }
        $this->__invoices = $invs;
        /* @var $container \Dojo\views\helpers\TabContainer */
        $container = $this->callViewHelper('dojo_tabContainer', 'container');
        $container->setDim(300, 700);
    }

    /**
     * Converts an invoice to an array, reading the orders and 
     * products corresponding to them
     * 
     * @param type $invoice
     * @return type
     */
    private function _readInvoice($invoice) {
        $inv = array();
        $inv['id'] = $invoice->id;
        $date = new \Iris\Time\Date($invoice->InvoiceDate);
        $inv['Date'] = $date->toString('d M Y');
        $inv['CustName'] = $invoice->_at_customer_id->Name;
        $orders = $invoice->_children_orders__invoice_id;
        foreach ($orders as $order) {
            $ord['Qty'] = $order->Quantity;
            $product = $order->_at_product_id;
            $ord['Description'] = $product->Description;
            $ord['UP'] = $product->Price;
            $inv['Orders'][] = $ord;
        }
        return $inv;
    }

    /**
     * Displays all the customers
     */
    public function customersAction() {
        $tCustomers = \models\TCustomers2::GetEntity();
        $customers = $tCustomers->fetchAll();
        foreach ($customers as $customer) {
            $cust['Name'] = $customer->Name;
            $invoices = $customer->_children_invoices__customer_id;
            // foreign key not necessary here
            //$invoices = $customer->_children_invoices;
            $invs = array();
            foreach ($invoices as $invoice) {
                $date = new \Iris\Time\Date($invoice->InvoiceDate);
                $invs[] = array('Number' => $invoice->id, 'Date' => $date->toString('d M Y'));
            }
            $cust["Inv"] = $invs;
            $custs[] = $cust;
        }
        $this->__customers = $custs;
    }

    public function eventsAction() {
        $tEvents = \models\TEvents::GetEntity();
        $events = $tEvents->fetchAll();
        foreach ($events as $event) {
            $ord = array();
            $ev['Moment'] = $event->Moment;
            $ev['Description'] = $event->Description;
            $order = $event->_at_invoice_id__product_id;
            $ord['Qty'] = $order->Quantity;
            $ord['Product'] = $order->_at_product_id->Description;
            $ord['Invoice'] = $order->_at_invoice_id->id;
            $ev['Order'] = $ord;
            $evs[] = $ev;
        }
        $this->__events = $evs;
    }

    public function ordersAction() {
        $tOrders = \models\TOrders::GetEntity();
        $orders = $tOrders->fetchAll();
        foreach ($orders as $order) {
            $evs = array();
            $invoice = $order->_at_invoice_id;
            $ord['InvNum'] = $order->invoice_id . '/' . $order->product_id;
            $date = new \Iris\Time\Date($invoice->InvoiceDate);
            $ord['Date'] = $date;
            $ord['Description'] = $order->_at_product_id->Description;
            $events = $order->_children_events__invoice_id__product_id;
            foreach ($events as $event) {
                $ev = $event->Moment . ': ' . $event->Description;
                $evs[] = $ev;
            }
            $ord['Events'] = $evs;
            $ords[] = $ord;
            /* @var $container \Dojo\views\helpers\TabContainer */
        }
        $this->__orders = $ords;
        $container = $this->callViewHelper('dojo_tabContainer', 'container');
        $container->setDim(300, 700);
    }

    /**
     * In case of a broken database, the action is redirected here
     * 
     * @param int $num
     */
    public function errorAction($num) {
    }

}
