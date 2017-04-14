<?php

namespace models;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */


/**
 * This abstract class has been replaced by the trait tInvoiceEntity
 * 
 * This file has been moved to  BluePrint archives
 * 
 * To access one of the static methods in the trait, use one of the model classes using the trait :<ul>
 * <li> TCustomers
 * <li> TEvents
 * <li> TInvoices
 * <li> TOrders
 * <li> TProducts
 * 
 * 
 * @deprecated since version august 2016
 */

abstract class _invoiceEntity extends \Iris\DB\_Entity {

    use tInvoiceEntity;
}
