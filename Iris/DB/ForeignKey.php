<?php

namespace Iris\DB;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
class ForeignKey implements \Serializable {

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

    /**
     * An array with key names in the father table as indexes and
     * the key names in the child table as values
     * 
     * @var string[]
     */
    private $_keys = [];

    /**
     * If a parameter is provided it is considered as a serialized string
     * 
     * @param string $string
     */
    public function __construct($string = \NULL) {
        if (!is_null($string)) {
            $this->unserialize($string);
        }
    }

    /**
     * Adds a complex foreign key : from two array the child names and the 
     * parents names (in REFERENCES)
     * 
     * Warning : the fields are sorted by child key names 
     * 
     * @param string[] $fromKeys
     * @param string[] $toKeys
     * @return \Iris\DB\ForeignKey for fluent interface
     */
    public function addKeys($fromKeys, $toKeys) {
        if(is_string($fromKeys)){
            $fromKeys = explode(',', $fromKeys);
            $toKeys = explode(',', $toKeys);
        }
        $this->_keys = array_combine($fromKeys, $toKeys);
        ksort($this->_keys);
        return $this;
    }

    /**
     * Adds a foreign key part. 
     * Warning : the fields are sorted by child key names 
     * 
     * @param string $fromKey the name in child record
     * @param string $Key then name in parent record
     * @return \Iris\DB\ForeignKey for fluent interface
     */
    public function addKey($fromKey, $Key) {
        $this->_keys[$fromKey] = $Key;
        ksort($this->_keys);
        return $this;
    }

    /**
     * Returns an array of fields, seen from the child side
     * 
     * @return string[]
     */
    public function getFromKeys() {
        return array_keys($this->_keys);
    }

    /**
     * Returns the various part of the foreign key as an indexed
     * array. Indexes are child names and values are parent names.
     * 
     * @return string[]
     */
    public function getKeys() {
        return $this->_keys;
    }

    /**
     * Returns an array of fields, seen from the parent side
     * 
     * @return string[]
     */
    public function getToKeys() {
        return array_values($this->_keys);
        //return $this->_toKeys;
    }

    /**
     * Returns the target table name of the foreign key
     * @return string
     */
    public function getTargetTable() {
        return $this->_targetTable;
    }

    /**
     * Sets the target table name of the foreign key
     * 
     * @param type $targetTable
     * @return \Iris\DB\ForeignKey for fluent interface
     */
    public function setTargetTable($targetTable) {
        $this->_targetTable = $targetTable;
        return $this;
    }

    /**
     * Serializes the object
     * 
     * @return string
     */
    public function serialize() {
        $strings[] = $this->getNumber();
        $strings[] = $this->_targetTable;
        $strings[] = serialize($this->_keys);
        return "FOREIGN@" . implode('+', $strings) . "\n";
    }

    /**
     * Unserializes a string to a foreign key. If requested, creates
     * a new object and returns it
     * 
     * @param string $serialized The serialized string
     * @param boolean $new Specifies if a new object is returned
     */
    public function unserialize($serialized, $new = \FALSE) {
        if ($new) {
            $object = new ForeignKey;
        }
        else {
            $object = $this;
        }
        list($number, $targetTable, $keys) = explode('+', $serialized);
        $object->setNumber($number);
        $object->_targetTable = $targetTable;
        $object->_keys = unserialize($keys);
        return $object;
    }

    /**
     * Returns the sequential number used in the metadata array
     * 
     * @return int
     */
    public function getNumber() {
        return $this->_number;
    }

    /**
     * Sets the sequential number used in the metadata array
     * 
     * @param type $number
     * @return \Iris\DB\ForeignKey for fluent interface
     */
    public function setNumber($number) {
        $this->_number = $number;
        return $this;
    }

}
