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
 * Management of the PapaylURL
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class PaypalSite extends \Iris\controllers\helpers\_ControllerHelper{
    protected static $_Singleton = \TRUE;
    
    const COMPLETED = 0;
    const CANCELLED = 1;
    const VERIFY = 2;
    
    private $_base;
    private $_actions;
    /**
     *
     * @var \Payoff\Ticket
     */
    private $_ticket;
    
    public function help($base, $actions = []){
        if(count($actions)==0){
            $actions = [
                'sale/completed', // COMPLETED
                'sale/cancelled', // CANCELLED
                'sale/certify',   // VERIFY
            ];
        }
        $this->_actions = $actions;
        $this->_base = $base;
        return $this;
    }
    
    public function setActions($actions) {
        $this->_actions = $actions;
        return $this;
    }

    public function setTicket(\Payoff\Ticket $ticket) {
        $this->_ticket = $ticket;
        return $this;
    }

    /**
     * 
     * @param \Payoff\_Order $order
     */
    public function url4Order($order){
        $order->setCancelReturn($this->_url(self::CANCELLED));
        $order->setNotifyUrl($this->_url(self::VERIFY));
        $order->setReturn($this->_url(self::COMPLETED));
    }
    
    
    public function cancelled(){
        return $this->_url(self::CANCELLED);
    }
    public function verify(){
        return $this->_url(self::VERIFY);
    }
    public function completed(){
        return $this->_url(self::COMPLETED);
    }

    private function _url($index) {
        $ticketNumber = $this->_ticket->getValue();
        return sprintf("%s/%s/%s",$this->_base, $this->_actions[$index], $ticketNumber);
    }
}

