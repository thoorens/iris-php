<?php

namespace Payoff\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class PaypalValidate extends \Iris\controllers\helpers\_ControllerHelper{
    protected static $_Singleton = \TRUE;
 
    private $_targetCount;
    
    private $_amount;
    
    private $_shippingFees;
    
    private $_tax;
    
    private $_currencyCode;
    
    
    
    public function help(){
        return $this;
    }
    
    public function setTargetCount($targetCount) {
        $this->_targetCount = $targetCount;
        return $this;
    }

    public function setAmount($amount) {
        $this->_amount = $amount;
        return $this;
    }

    public function setShippingFees($shippingFees) {
        $this->_shippingFees = $shippingFees;
        return $this;
    }

    public function setTax($tax) {
        $this->_tax = $tax;
        return $this;
    }

    /**
     * 
     * @param \Payoff\Paypal\Order $order
     */
    public function setOrder($order){
        $this->_amount = $order->getAmount();
        $this->_currencyCode = $order->getCurrencyCode();
        $this->_targetCount = $order->getBusiness();
        $this->_shippingFees = $order->getShipping();
        $this->_tax = $order->getTax();
    }

}