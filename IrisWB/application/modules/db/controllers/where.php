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
        $this->_entityManager = \Iris\DB\_Entity::DefaultEntityManager();
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
    public function limitAction(){
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $eInvoices->order('id')
                ->limit(2,0);
        $this->__invoices = $eInvoices->fetchall();
        $this->__clause = "ORDER BY Address";
    }
    
    public function nextAction($offset = 0){
        $this->_specialScreen(['Press next to see the rest of the invoices','Press resume to go back to the normal sequence']);
        $eInvoices = \models\TInvoices::GetEntity($this->_entityManager);
        $eInvoices->order('id')
                ->limit(2,$offset);
        $this->__invoices = $eInvoices->fetchall();
        $this->__clause = "ORDER BY Address";
        $this->setViewScriptName('limit');
    }
}
