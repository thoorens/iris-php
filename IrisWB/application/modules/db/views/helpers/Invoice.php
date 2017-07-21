<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Presenting an invoice
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * 
 */
class Invoice extends \Iris\views\helpers\_ViewHelper {

    /**
     * Returns a description of an invoice
     * 
     * @param \Iris\DB\Object $invoice
     * @param mixed $id
     * @return string
     */
    public function help($invoice, $id) {
        if (empty($invoice)) {
            $invoiceText = "<i>No invoice</i> $id";
        }
        else {
            // number
            $text[] = "<i>Invoice number</i> : " . $invoice->id;
            // date
            $text[] = "<i>Date</i> : " . $invoice->InvoiceDate;
            // customer
            $text[] = "<i>Customer name</i> : " . $invoice->_at_customer_id->Name;
            $invoiceText = implode("<br/>", $text);
        }
        return $invoiceText;
    }

}
