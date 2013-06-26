<?php

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
 * @copyright 2012 Jacques THOORENS
 *
 * 
 * 
 */

/**
 * Generates or verify a semi-random number
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Ticket implements \Serializable {

    private $_value;
    private $_description;

    public function __construct($seed, $obfuscate = \TRUE) {
        $this->_value = $obfuscate ? self::Obfuscate($seed) : $seed;
        $this->_description = $seed;
    }

    public function getValue() {
        $session = \Iris\Users\Session::GetInstance();
        $session->PayoffTicket = $this->serialize();
        return $this->_value;
    }

    public function validate() {
        $oldTIcket = new Ticket('0000000');
        $oldTIcket->unserialize(\Iris\Users\Session::GetInstance()->getValue('PayoffTicket', 'nothing!!000000'));
        if($oldTIcket->_value !== $this->_value){
            return \FALSE;
        }else{
            $this->_description = $oldTIcket->_description;
            return \TRUE;
        }
    }

    public function serialize() {
        return $this->_description . "!!" . $this->_value;
    }

    public function unserialize($serialized) {
        list($this->_description, $this->_value) = explode('!!', $serialized);
    }

    public static function Obfuscate($seed, $length = 12) {
        return substr(md5($seed), 0, $length);
    }

    public function getDescription() {
        return $this->_description;
    }

}

?>
