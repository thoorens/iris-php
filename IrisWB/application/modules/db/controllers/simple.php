<?php

namespace modules\db\controllers;

use Iris\DB\Query;

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
 *
 */

/**
 * Description of simple
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
class simple extends _db {
    /*
     * This portion of code is repeated in entities.php for readability
     */

    private $_entityManager;

    public function _init() {
        $dsn = "sqlite:/library/IrisWB/application/config/base/invoice.sqlite";
        $user = $password = ''; // no user for Sqlite
        $this->_entityManager = \Iris\DB\_EntityManager::EMFactory($dsn, $user, $password);
        $this->__tables = $this->_entityManager->listTables();
    }

    /* end of repeated code */

    /**
     * Access to table through a table name
     */
    public function customersAction() {
        // see _init for entity manager definition
        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers', $this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'Antonio Sanchez');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
    }

    /**
     * Access to table through an entity class name
     */
    public function customers2Action() {
        $eCustomer = \Iris\DB\TableEntity::GetEntity('customers','\\models\TCustomers', $this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'Antonio Sanchez');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->setViewScriptName('customers');
    }

    /**
     * Access to table through an entity class static method
     */
    public function customers3Action() {
        $eCustomer = \models\Anything::GetEntity($this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'Antonio Sanchez');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->setViewScriptName('customers');
    }

    /**
     * Access to view through its name and associated table
     */
    public function viewAction() {
        $eCustomer = \Iris\DB\ViewEntity::CreateView('vcustomers', 'customers', $this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'John Smith');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->setViewScriptName('customers');
    }

    /**
     * Access to view through its name
     */
    public function view2Action() {
        $eCustomer = \models\ViewCustomers::CreateView($this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'John Smith');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->setViewScriptName('customers');
    }

    /**
     * Access to view through its name
     */
    public function view3Action() {
        $eCustomer = \models\TVcustomers::CreateView($this->_entityManager);
        $this->__customer2 = $eCustomer->find(2);
        $eCustomer->where('Name =', 'John Smith');
        $this->__customer3 = $eCustomer->fetchRow();
        $this->__customers = $eCustomer->fetchAll();
        $this->setViewScriptName('customers');
    }
    
    public function invoicesAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $this->__invoice2 = $eInvoices->find(2);
        $eInvoices->where('InvoiceDate =', '2012-02-13');
        $this->__invoice4 = $eInvoices->fetchRow();
        $this->__invoices = $eInvoices->fetchAll();
    }

    public function customersinvoicesAction($id = 1) {
        $eCustomer = \models\TCustomers::GetEntity($this->_entityManager);
        $customer = $eCustomer->find($id);
        $this->__invoices = $customer->_children_invoices__customer_id;
        $this->__customer = $customer;
    }

    public function logicalAction() {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $eCustomers->where('Name = ', 'Antonio Sanchez');
        $eCustomers->_NOT_();
        $this->__customers1 = $eCustomers->fetchAll();

        $eCustomers->where('Name = ', 'Antonio Sanchez');
        $eCustomers->_NOT_();
        $eCustomers->where('Address =', 'rue Villette');
        $eCustomers->_AND_();
        $this->__customers2 = $eCustomers->fetchAll();

        $eCustomers->where('Name = ', 'Antonio Sanchez');
        $eCustomers->where('Name = ', 'Jacques Thoorens');
        $eCustomers->_OR_();
        $this->__customers3 = $eCustomers->fetchAll();
    }

    public function likeAction() {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $eCustomers->whereLike('Name', 'An% S_nchez');
        $this->__like1 = $eCustomers->fetchRow();

        $eCustomers->whereLike('Name', 'Anto', Query::BEGINS);
        $this->__like2 = $eCustomers->fetchRow();

        $eCustomers->whereLike('Name', 'chez', Query::ENDS);
        $this->__like3 = $eCustomers->fetchRow();

        $eCustomers->whereLike('Name', 'nio San', Query::CONTENTS);
        $this->__like4 = $eCustomers->fetchRow();
    }

    public function invoicesproductsAction($invoice_id = 1, $product_id = 1) {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $invoice = $eInvoices->find($invoice_id);
        $this->__invoice = $invoice;
        $this->__orders = $invoice->_children_orders__invoice_id;

        $eProduct = \models\TProducts::GetEntity($this->_entityManager);
        $product = $eProduct->find($product_id);
        $this->__product = $product;
        $this->__orders2 = $product->_children_orders__product_id;
    }

    public function whereAction() {
        // Invoices in January
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $eInvoices->whereBetween('InvoiceDate', '2012-02-01', '2012-02-29');
        $this->__invoices = $eInvoices->fetchAll();
        // Order with quantity 3, 4 or 5 
        $eOrders = \models\TOrders::GetEntity($this->_entityManager);
        $eOrders->whereIn('Quantity', [3, 4, 5]);
        $this->__orders = $eOrders->fetchAll();
        // Customers with no email
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $eCustomers->whereNull('Email');
        $this->__noMails = $eCustomers->fetchAll();
        // Customers with email
        $eCustomers->whereNotNull('Email');
        $this->__withMail = $eCustomers->fetchAll();
    }

}
