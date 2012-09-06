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
 * Represents a line in a metadata, i.e. a field description
 * or a foreign key
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class MetaItem implements \Serializable {

    /**
     *
     * @var string
     */
    private $_fieldName = NULL;

    /**
     *
     * @var string
     */
    private $_type = NULL;

    /**
     *
     * @var int 
     */
    private $_size = 0;

    /**
     *
     * @var string
     */
    private $_defaultValue = NULL;

    /**
     *
     * @var boolean
     */
    private $_notNull = TRUE;

    /**
     *
     * @var boolean 
     */
    private $_autoIncrement = \FALSE;

    /**
     *
     * @var boolean
     */
    private $_primary = FALSE;

    /**
     *
     * @var string
     */
    private $_foreignPointer = NULL;
    private $_properties = array();

    public function __construct($fieldName) {
        $this->_fieldName = $fieldName;
    }

    /**
     * Accessor get for field name
     * @return string 
     */
    public function getFieldName() {
        return $this->_fieldName;
    }

    /**
     * Accessor get for field type
     * @return string 
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Accessor set for field type
     * 
     * @param string $type
     * @return MetaItem (fluent interface)
     */
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }

    /**
     * Accessor get for defaultValue (as stated in table 
     * definition - see initialize for a more convenient value)
     * 
     * @return mixed
     */
    public function getDefaultValue() {
        return $this->_defaultValue;
    }

    /**
     * Accessor set for defaultValue
     * @param type $_defaultValue
     * @return MetaItem  (fluent interface)
     */
    public function setDefaultValue($_defaultValue) {
        $this->_defaultValue = $_defaultValue;
        return $this;
    }

    /**
     * Accessor get for the autoincrement feature of a field
     * 
     * @return boolean 
     */
    public function isAutoIncrement() {
        return $this->_autoIncrement;
    }

    /**
     * Accessor set for the autoincrement feature of a field
     * @param boolean $autoIncrement
     * @return MetaItem (fluent interface)
     */
    public function setAutoIncrement($autoIncrement = \TRUE) {
        $this->_autoIncrement = $autoIncrement;
        return $this;
    }

    /**
     * Accessor get for the not null field feature
     * 
     * @return boolean
     */
    public function isNotNull() {
        return $this->_notNull;
    }

    /**
     * Accessor det for the not null field feature
     * 
     * @param boolean $notNull
     * @return MetaItem (fluent interface)
     */
    public function setNotNull($notNull=TRUE) {
        $this->_notNull = $notNull = TRUE;
        return $this;
    }

    /**
     * Accessor get for the index key in Foreign key definition
     * 
     * @return string 
     */
    public function getForeignPointer() {
        return $this->_foreignPointer;
    }

    /**
     * Accessor set for the index key in Foreign key definition
     * 
     * @param string $foreign
     * @return MetaItem (fluent interface)
     */
    public function setForeignPointer($foreign) {
        $this->_foreignPointer = $foreign;
        return $this;
    }

    /**
     * Accessor get for the primary key field feature
     * @return type 
     */
    public function isPrimary() {
        return $this->_primary;
    }

    /**
     * 
     * @param type $primary
     * @return \Iris\DB\MetaItem (fluent interface)
     */
    public function setPrimary($primary=TRUE) {
        $this->_primary = $primary;
        return $this;
    }

//    /**
//     * Indicates a virtual fied
//     * @return type 
//     */
//    public function isVirtual() {
//        return $this->_virtual;
//    }
//
//    public function setVirtual($virtual=TRUE) {
//        $this->_virtual = $virtual;
//    }

    /**
     * Accessor get for size
     * @return int
     */
    public function getSize() {
        return $this->_size;
    }

    /**
     * 
     * @param int $size
     * @return \Iris\DB\MetaItem (fluent interface)
     */
    public function setSize($size) {
        $this->_size = $size;
        return $this;
    }

    /**
     * Returns an initial value according to 
     * field definition
     * 
     * @param mixed $value
     * @return mixed 
     */
    public function initialize($value) {
        if (is_null($value)) {
            if ($this->isAutoIncrement()) {
                $value = \NULL;
            }
            elseif (!is_null($this->getDefaultValue())) {
                $value = $this->getDefaultValue();
            }
        }
        return $value;
    }

    /**
     * Creates a serialized string corresponding to the metadata
     * 
     * @return string
     */
    public function serialize() {
//      -- example of MetaItem
//      object(Iris\DB\MetaItem)#26 (8) {
//      ["_fieldName":"Iris\DB\MetaItem":private]=>
//      string(2) "id"
//      ["_type":"Iris\DB\MetaItem":private]=>
//      string(7) "INTEGER"
//      ["_size":"Iris\DB\MetaItem":private]=>
//      int(0)
//      ["_defaultValue":"Iris\DB\MetaItem":private]=>
//      NULL
//      ["_notNull":"Iris\DB\MetaItem":private]=>
//      bool(true)
//      ["_autoIncrement":"Iris\DB\MetaItem":private]=>
//      bool(false)
//      ["_primary":"Iris\DB\MetaItem":private]=>
//      bool(true)
//      ["_foreignPointer":"Iris\DB\MetaItem":private]=>
//      NULL
//    }
        $string[] = "fieldName:" . $this->_fieldName;
        $string[] = "type:" . $this->_type;
        $string[] = "size:" . $this->_size;
        $string[] = "defaultValue:" . $this->_defaultValue;
        $string[] = "notNull:" . ($this->_notNull ? '*TRUE*' : '*FALSE*');
        $string[] = "autoIncrement:" . ($this->_autoIncrement ? '*TRUE*' : '*FALSE*');
        $string[] = "primary:" . ($this->_primary ? '*TRUE*' : '*FALSE*');
        $string[] = "foreignPointer:" . (is_null($this->_foreignPointer) ? '*NULL*' : $this->_foreignPointer);
        return "FIELD@" . implode('!', $string) . "\n";
    }

    /**
     * Initializes or completes the internal data according to the serialized string given
     * 
     * @param string $serialized
     */
    public function unserialize($serialized) {
        $properties = explode('!', $serialized);
        foreach ($properties as $property) {
            list($key, $value) = explode(':', $property);
            if ($value == '*TRUE*')
                $value = \TRUE;
            elseif ($value == '*FALSE*')
                $value = \FALSE;
            elseif ($value == '*NULL*')
                $value = \NULL;
            $propertyName = "_$key";
            $this->$propertyName = $value;
        }
    }

    public function __set($name, $value) {
        $this->_properties[$name] = $value;
    }

    public function __get($name) {
        return $this->_properties[$name];
    }

    public function get($name, $default = \NULL) {
        if (isset($this->_properties[$name])) {
            return $this->$name;
        }
        else {
            return $default;
        }
    }

}

