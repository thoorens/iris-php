<?php

namespace Iris\DB;

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
 * A mean to describe and manage a foreign key of a table.
 * The child entity is implicit (its metadata contains
 * the instance of ForeignKey
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ForeignKey implements \Serializable{

    /**
     * The list of key names in the child table
     * 
     * @var array(string)
     */
    private $_fromKeys = array();

    /**
     * The list of key names in the father table
     * @var array(string)
     */
    private $_toKeys = array();
    
    /**
     * The name of the father table
     * 
     * @var string
     */
    private $_targetTable;

    /**
     * The number in the metadata array (to be coherent in case of serialisation)
     * @var int
     */
    private $_number;
    
    
    public function __construct($string = \NULL) {
        if(!is_null($string)){
            $this->unserialize($string);
        }
    }

    
    
    public function getFromKeys() {
        return $this->_fromKeys;
    }

    public function setFromKeys($_fromKeys) {
        $this->_fromKeys = $_fromKeys;
    }

    public function setToKeys($_toKeys) {
        $this->_toKeys = $_toKeys;
    }

    public function addFromKey($fromKey) {
        $this->_fromKeys[] = $fromKey;
    }

    public function getToKeys() {
        return $this->_toKeys;
    }

    public function addToKey($toKey) {
        $this->_toKeys[] = $toKey;
    }

    public function getTargetTable() {
        return $this->_targetTable;
    }

    public function setTargetTable($targetTable) {
        $this->_targetTable = $targetTable;
    }

    public function serialize() {
        $strings[] = $this->getNumber();
        $strings[] = implode('!',$this->_fromKeys);
        $strings[] = $this->_targetTable;
        $strings[] = implode('!',$this->_toKeys);
        return "FOREIGN@".implode('+',$strings)."\n";
    }

    public function unserialize($serialized) {
        list($number,$cmpFromKeys,$targetTable,$cmptoKeys) = explode('+',$serialized);
        $this->setNumber($number);
        $this->_targetTable = $targetTable;
        $fromKeys = explode('!',$cmpFromKeys);
        foreach($fromKeys as $fromKey){
            $this->addFromKey($fromKey);
        }
        $toKeys = explode('!',$cmptoKeys);
        foreach($toKeys as $toKey){
            $this->addToKey($toKey);
        }
    }

    public function getNumber() {
        return $this->_number;
    }

    public function setNumber($number) {
        $this->_number = $number;
    }


    
}

