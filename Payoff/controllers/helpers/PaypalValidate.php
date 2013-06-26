<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payoff\controllers\helpers;

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
 */


/**
 * Description of Site
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