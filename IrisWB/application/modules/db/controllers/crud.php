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
     * 
     * @param string $type 
     */
    public function customersAction() {
        $tCustomers = \models\TCustomers::GetEntity();
        $this->__customers = $tCustomers->fetchAll();
    }

    public function invoicesAction() {
        $tInvoices = \models\TInvoices::GetEntity();
        $this->__invoices = $tInvoices->fetchAll();
    }

    public function productsAction(){
        $tProducts = \models\TProducts::GetEntity();
        $this->__products = $tProducts->fetchall();
    }
    
    /**
     * In case of a broken database, the action is redirected here
     * 
     * @param int $num
     */
    public function errorAction($num) {
        $this->setViewScriptName('commons/error');
    }
}
