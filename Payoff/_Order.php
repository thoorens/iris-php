<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Payoff;

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
 * Description of _Order
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _Order {

    /**
     * The price of the object/service
     * @var float
     */
    private $_amount = \NULL;

    /**
     * The code for currency
     * @var string
     */
    private $_currencyCode = \NULL;

    /**
     * Shipping fees
     * @var float
     */
    private $_shipping = 0.0;

    /**
     * The tax to add to the price
     * @var float
     */
    private $_tax = \NULL;

    /**
     * The url to go after paiement
     * @var string
     */
    private $_return = \NULL;

    /**
     * The url to go after cancelation
     * @var string
     */
    private $_cancelReturn = \NULL;

    /**
     * The URL to notify after paiement
     * @var string
     */
    private $_notifyUrl = \NULL;

    
    /**
     * Seller id
     * @var string
     */
    private $_business = \NULL;

    /**
     * What is sold
     * @var string
     */
    private $_itemName = \NULL;

    /**
     * ???
     * @var boolean
     */
    private $_noNote = \NULL;

    /**
     * Locale
     * @var string 
     */
    private $_lc = \NULL;

    private $_charset = 'UTF8';

    /**
     * Variables in the form "var1=xyz&var2=123"
     * @var string
     */
    private $_custom = \NULL;

    /**
     * The submit button value
     * @var string
     */
    protected $_submit = \NULL;
    
    /**
     * The customer's name (for site internal use)
     * @var string
     */
    private $_name;

    /**
     * The customer's address (for site internal use)
     * @var string 
     */
    private $_address;
    
    /**
     * By default the order is in demo mode
     * @var boolean
     */
    protected $_demo = \TRUE;

    /* =====================================================================
     * S E T T E R S
     * =====================================================================
     */
    
    /**
     * 
     * @param type $amount
     * @return \Payoff\_Order
     */
    public function setAmount($amount) {
        $this->_amount = $amount;
        return $this;
    }

    

        
    public function setCurrencyCode($currencyCode) {
        $this->_currencyCode = $currencyCode;
        return $this;
    }

    public function setShipping($shipping) {
        $this->_shipping = $shipping;
        return $this;
    }

    public function setTax($tax) {
        $this->_tax = $tax;
        return $this;
    }

    public function setReturn($return) {
        $this->_return = $return;
        return $this;
    }

    public function setCancelReturn($cancelReturn) {
        $this->_cancelReturn = $cancelReturn;
        return $this;
    }

    public function setNotifyUrl($notifyUrl) {
        $this->_notifyUrl = $notifyUrl;
        return $this;
    }

    
    public function setBusiness($business) {
        $this->_business = $business;
        return $this;
    }

    public function setItemName($itemName) {
        $this->_itemName = $itemName;
        return $this;
    }

    public function setNo_note($noNote) {
        $this->_noNote = $noNote;
        return $this;
    }

    public function setLocale($lc) {
        $this->_lc = $lc;
        return $this;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function setAddress($address) {
        $this->_address = $address;
        return $this;
    }

    
    public function setCustom($custom) {
        $this->_custom = $custom;
        return $this;
    }

    public function setSubmit($submit) {
        $this->_submit = $submit;
        return $this;
    }
    
    
    

    
    /* =====================================================================
     * Renderers
     * =====================================================================
     */

    protected function _renderAmount() {
        
        return $this->_renderField('amount', $this->_amount);
    }

    protected function _renderCurrencyCode() {
        $this->_required('currency_code');
        return $this->_renderField('currency_code', $this->_currencyCode);
    }

    protected function _renderShipping() {
        return $this->_renderField('shipping', $this->_shipping);
    }

    protected function _renderTax() {
        return $this->_renderField('tax', $this->_tax);
    }

    protected function _renderReturn() {
        return $this->_renderField('return', $this->_return);
    }

    protected function _renderCancelReturn() {
        return $this->_renderField('cancel_return', $this->_cancelReturn);
    }

    protected function _renderNotifyUrl() {
        return $this->_renderField('notify_url', $this->_notifyUrl);
    }

    protected  function _renderCharset(){
        return $this->_renderField('charset', $this->_charset);
    }

    protected function _renderBusiness() {
        $this->_required('business');
        return $this->_renderField('business', $this->_business);
    }

    protected function _renderItemName() {
        $this->_required('item_name', $this->_itemName);
        return $this->_renderField('item_name', $this->_itemName);
    }

    protected function _renderNoNote() {
        return $this->_renderField('no_note', $this->_noNote);
    }

    protected function _renderLc() {
        return $this->_renderField('lc', $this->_lc);
    }

    

    protected function _renderCustom() {
        return $this->_renderField('custom', $this->_custom);
    }

    protected function _renderSubmit(){
        if(is_null($this->_submit)){
            throw new \Payoff\Exception('No submit button has been provided');
        }
    }

    abstract protected function _renderField($name, $value);

    public function render() {
        $html = $this->_renderAmount();
        $html .= $this->_renderBusiness();
        $html .= $this->_renderCancelReturn();
        $html .= $this->_renderCurrencyCode();
        $html .= $this->_renderCustom();
        $html .= $this->_renderItemName();
        $html .= $this->_renderLc();
        $html .= $this->_renderNoNote();
        $html .= $this->_renderNotifyUrl();
        $html .= $this->_renderReturn();
        $html .= $this->_renderShipping();
        $html .= $this->_renderSubmit();
        $html .= $this->_renderTax();
        $html .= $this->_renderCharset();
        return $html;
    }

    public function setDemo($demo = \TRUE) {
        $this->_demo = $demo;
        return $this;
    }

    
    /* =====================================================================
     * G E T T E R S
     * =====================================================================
     */

    public function getCurrencyCode() {
        return $this->_currencyCode;
    }

    public function getShipping() {
        return $this->_shipping;
    }

    public function getTax() {
        return $this->_tax;
    }

    public function getBusiness() {
        return $this->_business;
    }

    public function getAmount() {
        return $this->_amount;
    }    
    
    public function getName() {
        return $this->_name;
    }
    
    public function getAddress() {
        return $this->_address;
    }
    /**
     * Gets the associated server URL
     */
    abstract protected function _getSite();
    
    private function _required($fieldName){
        $parts = explode('_',$fieldName);
        if(count($parts) == 1){
            $field = "_$fieldName";
        }
        else{
            $field = "_".$parts[0];
            $field .= ucfirst($parts[1]);
        }
        if(is_null($this->$field)){
            throw new Exception("The field $fieldName must be filled in");
        }
    }
}

