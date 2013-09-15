<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace modules\subhelpers\controllers;
use \Iris\Time\Date;

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
 */


/**
 * Description of EventTest
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */

class EventTest implements \Iris\Time\iEvent{
    
    /**
     *
     * @var int
     */
    private $_id;
    
    /**
     *
     * @var int
     */
    private $_type;
    
    /**
     *
     * @var \Iris\Time\Date
     */
    private $_date;
    
    /**
     *
     * @var string
     */
    private $_description;
    
    
    function __construct($id, $type, $date, $description) {
        $this->_id = $id;
        $this->_type = $type;
        $this->_date = new Date($date);
        $this->_description = $description;
    }

    public function getDate() {
        return $this->_date;
    }

    public function getDescription() {
        return $this->_description;
    }

    public function display(){
        $text = '<b>'.$this->_date->toString('j M').'</b>:<br>';
        $text .= $this->getDescription();
        return $text;
    }
    
    public function getId() {
        return $this->_id;
    }

    public function getType() {
        return $this->_type;
    }

    public function isTakenPlace($date) {
        
    }

    public function __toString() {
        return (string)$this->_description;
    }
}






