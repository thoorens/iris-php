<?php

namespace modules\db\controllers;

use Iris\DB\Query;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * 
 * Test of all WHERE variants
 * 
 * @author jacques
 * @license not defined
 */
class where extends _db {

    /**
     * Common part of the actions
     */
    protected function _init() {
        $this->_entityManager = $entityManager = \models\TInvoices::GetEM();
        $this->__action = "show";
        $this->dbState()->validateDB();
        $this->setDefaultScriptDir('where');
    }

    /**
     * Distinguishes find and fetch/where
     */
    public function whereAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $this->__invoice2 = $eInvoices->find(2);
        $this->__invoice45 = $eInvoices->find(45);
        $eInvoices->where('InvoiceDate >', '2012-02-13');
        $this->__invoice1 = $eInvoices->fetchRow();
        // must repeat condition after fetch
        $eInvoices->where('InvoiceDate >', '2012-02-13');
        $this->__invoices = $eInvoices->fetchAll();
    }

    /**
     * Uses 4 types of where: <ul>
     * <li>whereBetween
     * <li>whereIn
     * <li>whereNull
     * <li>whereNotNull
     * </ul>
     */
    public function where4Action() {
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

    public function findAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $this->__invoice2 = $eInvoices->find(2);
        $this->__invoice45 = $eInvoices->find(45);
        $eOrders = \models\TOrders::GetEntity($this->_entityManager);
        $this->__order12 = $eOrders->find([1, 2]);
        $this->__order10145 = $eOrders->find([101, 45]);
        $this->__order51 =  $eOrders->find(['invoice_id' => 5, 'product_id' => 1]);
        $this->__order5_1 = $eOrders->find(['product_id' => 1, 'invoice_id' => 5]);// not displayed
    }

        /*  +----+-------------+-------------+--------+
            | id | InvoiceDate | customer_id | Amount |
            +----+-------------+-------------+--------+
            |  1 | 2012-01-05  |           1 |   NULL |
            |  2 | 2012-01-05  |           2 |   NULL |
            |  3 | 2012-01-05  |           3 |   NULL |
            |  4 | 2012-02-13  |           1 |   NULL |
            |  5 | 2012-02-21  |           1 |   NULL |
            |  6 | 2012-03-05  |           3 |   NULL |
            +----+-------------+-------------+--------+  */
    
    
    public function fetchrowAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        /* @var $i1 \Iris\DB\Object */
        $this->__inv1 = $i1 = $eInvoices->fetchRow("InvoiceDate>",'2012-02-13');  // invoices 5 (next ones is 6
        $this->__inv2 = $i2 =  $eInvoices->fetchRow("InvoiceDate>",'2017-01-01');  // NULL
        /* @var $i3 \Iris\DB\Object */
        $this->__inv3 = $i3 =  $eInvoices->where("InvoiceDate>",'2012-02-13')->fetchRow();
        $this->__inv4 = $i4 =  $eInvoices->where("InvoiceDate>",'2017-01-01')->fetchrow();
        if (count(array_diff($i1->asArray(), $i3->asArray()))) {
            print('Error in comparing the results');
        }
        if (!empty($i2) or (!empty($i4))) {
            print('Error in \NULL results');
        }
    }

    public function fetchallAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $this->__inv1 = $i1 = $eInvoices->fetchAll();  // invoices 5 and 6
        /* @var $i3 \Iris\DB\Object */
        $this->__inv2 = $i2 =  $eInvoices->where("InvoiceDate>",'2017-01-01')->fetchAll();
//        if (count(array_diff($i1->asArray(), $i3->asArray()))) {
//            print('Error in comparing the results');
//        }
//        if (!empty($i2) or (!empty($i4))) {
//            print('Error in \NULL results');
//        }
    }

    public function fetchallinarrayAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $this->__inv1 = $i1 = $eInvoices->where('InvoiceDate==','2012-01-05')->fetchAllInArray();
        //i_d($i1);
    }

    public function fetchallindexedAction() {
        
    }

    /**
     * 4 usages of whereLike()
     */
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

    /**
     * Demo for the logical stack
     */
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

    /**
     * Demo for whereClause
     */
    public function clauseAction() {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $clause = "Name = 'Antonio Sanchez' OR Name = 'Jacques Thoorens'";
        $eCustomers->whereClause($clause);
        $this->__customers = $eCustomers->fetchall();
        $this->__clause = "WHERE $clause";
    }

    /**
     * Demo for wherePairs (with various operators)
     */
    public function pairsAction() {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        // the second parameter suppresses the use of implicit '='
        $eCustomers->wherePairs(['Address' => 'rue Villette', 'Name' => 'Jacques Thoorens']);
        $this->__customer1 = $eCustomers->fetchRow();
        $this->__methodCall1 = "wherePairs(['Address' => 'rue Villette', 'Name' => 'Jacques Thoorens'])";
        $eCustomers->wherePairs(['Address' => 'rue Villette', 'Name' => 'Antonio Sanchez'], '<>');
        $this->__customer2 = $eCustomers->fetchRow();
        $this->__methodCall2 = "wherePairs(['Address' => 'rue Villette', 'Name' => 'Antonio Sanchez'], '<>')";
        $eCustomers->wherePairs(['Address >' => 'Bourbon', 'Name <' => 'Jacques Thoorens'], '');
        $this->__customer3 = $eCustomers->fetchRow();
        $this->__methodCall3 = "wherePairs(['Address >' => 'Bourbon', 'Name <' => 'Jacques Thoorens'], '')";
    }

    /**
     * Demo for Order()
     */
    public function orderAction() {
        $eCustomers = \models\TCustomers::GetEntity($this->_entityManager);
        $eCustomers->order('Address');
        $this->__customers = $eCustomers->fetchall();
        $this->__clause = "ORDER BY Address";
    }

    /**
     * Demo for Limit
     * @param init $offset
     */
    public function limitAction() {
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $eInvoices->order('id')
                ->limit(2, 0);
        $this->__invoices = $eInvoices->fetchall();
        $this->__clause = "ORDER BY Address";
    }

    public function nextAction($offset = 0) {
        $this->_specialScreen(['Press next to see the rest of the invoices', 'Press resume to go back to the normal sequence']);
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $eInvoices->order('id')
                ->limit(2, $offset);
        $this->__invoices = $eInvoices->fetchall();
        $this->__clause = "ORDER BY Address";
        $this->setViewScriptName('limit');
    }

}
