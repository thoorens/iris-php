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
 * Test of basic crud operations
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class crud extends _dbCrud {

    /**
     * Creates the tables from scratch (deleting an existing database if necessary)
     * 
     * @param string $dbType  the rdbms type
     * @param boolean $data if true creates the data
     */
    public function initAction($dbType='sqlite', $data = \FALSE) {
        \Iris\Users\Session::GetInstance()->dbType = $dbType;
        \models\_invoiceManager::Reset($dbType);
        $this->prepareInvoices()->createCustomers($dbType, $data);
        $this->prepareInvoices()->createInvoices($dbType, $data);
        $this->redirect('customers');
    }

    /**
     * 
     * @param string $type 
     */
    public function customersAction() {
        $tCustomers = new \models\TCustomers();
        $this->__customers = $tCustomers->fetchall();
    }

    public function invoicesAction() {
        $tInvoices = new \models\TInvoices();
        $this->__invoices = $tInvoices->fetchall();
    }

}
