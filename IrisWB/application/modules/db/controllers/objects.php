<?php

namespace modules\db\controllers;

 /*
  * This file is part of IRIS-PHP, distributed under the General Public License version 3.
  * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
  * More details about the copyright may be found at
  * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
  *  
  * @copyright 2011-2017 Jacques THOORENS
  */

/**
 * Description of objects
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class objects extends _db {

    protected function _init() {
        $this->_entityManager = \models\TInvoices::DefaultEntityManager();
    }

    public function simpleAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);

        $c1 = $eCustomers->find(1);
        $c1bis = $eCustomers->where('id=', 1)->fetchRow();
        $c1Email[] = $c1->Email;
        $c1bis->Email = "test@gmail.com";
        $c1Email[] = $c1->Email;
        $customers = $eCustomers->fetchAll();
        $customers[0]->Email = "myAddress@mydomain.com";
        $c1Email[] = $c1->Email;
    }

    public function createAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();
        $results->rawShift();

        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $results->addComment('Creating a new customer');
        $new = $eCustomers->createRow();
        $results->addGoodResult('$new = $eCustomers->createRow();', 'OK', \TRUE);
        $new->Name = "Jean Dupont";
        $new->Address = "Downing street";
        $new->Email = "Dupont@government.uk";
        $results
                ->addComment('After putting data in field (except id)');
        $this->_displayCustomer($results, $new);
        $new->save();
        $results->addComment('Saving the new object')
                ->addGoodResult('$new->save();', 'OK', \TRUE);
        $this->_displayCustomer($results, $new);

        $this->setViewScriptName('/tests');
        $results->sendToView();
        $this->dbState()->setModified();
    }

    public function deleteAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();
        $results->rawShift();
        
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $customer = $eCustomers->find($eCustomers->lastInsertedId());
        $results->addComment('Deleting the last inserted customer');
        $customer->delete();
        $results->addGoodResult('$new->delete();', 'OK', \TRUE);
        $results->addComment('Data are still available');
        $this->_displayCustomer($results, $customer);
        $results->addComment('Data may be even be modified');
        $customer->Email = 'some@address';
        $results->addGoodResult('$new->Email = "some@address";', 'OK', \TRUE);
        try {
            $customer->save();
        }
        catch (\Exception $exception) {
            $results->addComment('buyt you cannot save the modification');
            $results->addBadResult('$customer->save();', $exception->getMessage(), \TRUE);
        }
        $results->sendToView();
        $this->setViewScriptName('/tests');
        $this->dbState()->setModified();
    }

    public function doubleAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $eCustomers2 = \Iris\DB\ViewEntity::GetEntity('vcustomers', 'customers', $this->_entityManager);
        $c1 = $eCustomers->find(1);
        $c2 = $eCustomers2->find(1);
        $c1->Email = "anything@gmail.com";

        if (!$c2->isReadOnly()) {
            $c2->Email = "mynewmail@gmail.com";
        }
        else {
            $results->addGoodResult('Property ReadOnly of an object from a view', ' TRUE');
        }
        try {
            $c2->Email = "mynewmail@gmail.com";
        }
        catch (\Exception $exception) {
            $results->addBadResult('Modifying an object from a view', $exception->getMessage());
        }
        $results->sendToView();
        $this->setViewScriptName('/tests');
    }

    /**
     * The purpose of this test is to modify a parent object obtained from one of its children
     * and to show that the saving of the child brings about an automatic save of the parent object.
     */
    public function parentAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $this->_resetCustomerAddress();

        // display the address of the invoice 1 customer
        $eInvoice = \models\TInvoices::GetEntity($this->_entityManager);
        $invoice1 = $eInvoice->find(1);
        $customer = $invoice1->_at_customer_id;
        $results->addComment('Considering Invoice #1');
        $results->addGoodResult('Initial address of its customer', $customer->Address);

        // modify customer's address and save invoice
        $results->addComment("Modifying customer's address and saving invoice #1");
        $customer->Address = 'rue Bassenge';
        $invoice1->save();
        $results->addGoodResult('New address of customer for invoice 1', $customer->Address);
        $results->addBreak()->addComment('See permanent result in next screen');


        $results->sendToView();
        $this->setViewScriptName('/tests');
    }

    /**
     * The purpose of this test is to modify a children obtained from its parent
     * and to show that the saving of the parent brings about an automatic save of the child object.
     */
    public function childrenAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();

        $this->_resetInvoice();

        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $customer1 = $eCustomers->find(1);

        $results->addComment('The customer #1 address has been modified in previous screen.');
        $results->addGoodResult('New address of customer for invoice 1', $customer1->Address);
        // reset all data
        $this->_resetCustomerAddress();
        $results->addComment('NOTE: the address has been reset to the original one (' . $customer1->Address . ').');

        $invoice1 = $this->_showInvoices($results, $customer1);
        $results->addComment('Changing first invoice date to next year and saving customer');
        $invoice1->InvoiceDate = '2013-01-05';
        $customer1->save();
        $results->addGoodResult('Inv #1', $this->callViewHelper('simpleDate', $invoice1->InvoiceDate));

        // reset all data
        $results->addBreak();
        $this->_resetCustomerAddress();
        $results->addComment('See next screen to see a permanent change.');
        $results->sendToView();
        $this->setViewScriptName('/tests');
    }

    public function resetAction() {
        /* @var $results \Iris\controllers\helpers\StoreResults */
        $results = $this->storeResults();
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $customer1 = $eCustomers->find(1);

        $this->_showInvoices($results, $customer1);

        $invoice1 = $this->_resetInvoice();
        $results->addBreak();
        $results->addComment('NOTE: the date has been reset to the original one (' . $this->callViewHelper('simpleDate', $invoice1->InvoiceDate) . ').');

        $results->sendToView();
        $this->setViewScriptName('/tests');
    }

    /* =========================================================================
     * The following methods are used various times
     */

    /**
     * Resets a customer address to its previous value (by default the customer
     * number 1 is treated)
     * 
     * @param int $number
     * @param string $address
     */
    private function _resetCustomerAddress($number = 1, $address = 'rue Villette') {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $customer1 = $eCustomers->find($number);
        $customer1->Address = $address;
        $customer1->save();
    }

    /**
     * Resets a invoice date to its previous value (by default the invoice
     * number 1 is treated)
     * 
     * @param int $number
     * @param string $address
     */
    private function _resetInvoice($number = 1, $date = '2012-01-05') {
        $eInvoice = \models\TInvoices::GetEntity($this->_entityManager);
        $invoice = $eInvoice->find($number);
        $invoice->InvoiceDate = $date;
        $invoice->save();
        return $invoice;
    }

    /**
     * Places all the invoices of a given customer in the result store and
     * return the first invoice in the list.
     * 
     * @param \Iris\controllers\helpers\StoreResults $results The result store
     * @param \Iris\DB\Object $customer The given customer
     * @return \Iris\DB\Object
     */
    private function _showInvoices($results, $customer) {
        $results->addBreak()->rawShift();
        $results->addComment('Displaying Invoices belonging to customer #' . $customer->id);
        $invoices = $customer->_children_invoices__customer_id;
        foreach ($invoices as $invoice) {
            $results->addGoodResult('Inv #' . $invoice->id, $this->callViewHelper('simpleDate', $invoice->InvoiceDate));
        }
        return $invoices[0];
    }

    /**
     * 
     * @param type $results
     * @param type $customer
     */
    public function _displayCustomer($results, $customer) {
        $results->addGoodResult('id', is_null($customer->id) ? "NULL" : $customer->id)
                ->addGoodResult('Name', $customer->Name)
                ->addGoodResult('Address', $customer->Address)
                ->addGoodResult('Email', $customer->Email);
    }

}

