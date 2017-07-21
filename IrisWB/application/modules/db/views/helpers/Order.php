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
 * Presenting an order line in an invoice
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * 
 */
class Order extends \Iris\views\helpers\_ViewHelper {

    /**
     * Returns a localized formated date: e.g. 5 janvier 2013
     * 
     * @param \Iris\Time\Date/string $date
     * @return string
     */
    public function help($order, $invId, $prodId) {
        if (empty($order)) {
            $invoiceText = "<i>No order for invoice </i>$invId - <i>product </i>$prodId";
        }
        else {
            // number
            $text[] = "<i>Order multiple key</i> : " . $order->invoice_id .'-' . $order->product_id;
            // invoice date
            $text[] = "<i>InvoiceDate</i> : " . $order->_at_invoice_id->InvoiceDate;
            // customer
            $text[] = "<i>Product description</i> : " . $order->_at_product_id->Description;
            //quantity
            $text[] = "<i>Quantity : </i>" . $order->Quantity;
            $invoiceText = implode("<br/>", $text);
        }
        return $invoiceText;
    }

}
