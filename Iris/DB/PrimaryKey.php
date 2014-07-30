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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * A mean to describe and manage the primary key of a table.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class PrimaryKey implements \Serializable {

    /**
     * The internal list of fields
     * @var string[]
     */
    private $_fields = [];

    /**
     * The autoincrement property of the primary key
     * 
     * @var boolean
     */
    private $_autoIncrement = \FALSE;

    /**
     * Creates a new primary key (with optional data to fill its fields)
     * 
     * @param string $value
     */
    public function __construct($value = \NULL) {
        if (!is_null($value)) {
            $this->unserialize($value);
        }
    }

    /**
     * Creates a string with primary key field name 
     * to be inserted in serialized metadata
     * 
     * @return string
     */
    public function serialize() {
        return "PRIMARY@" . implode('!', $this->_fields);
    }

    /**
     * Modifies current primary key according to the data provided
     * and returns it
     * 
     * @param string $serialized
     * @return \Iris\DB\PrimaryKey
     */
    public function unserialize($serialized) {
        $primaries = explode('!', $serialized);
        foreach ($primaries as $primary) {
            $this->addField($primary);
        }
        return $this;
    }

    /**
     * Adds a new field to the primary key
     * @param string $fieldName
     */
    public function addField($fieldName) {
        if (array_search($fieldName, $this->_fields) === \FALSE) {
            $this->_fields[] = $fieldName;
            sort($this->_fields);
        }
    }

    /**
     * Accessor to the fields 
     * @return string
     */
    public function getFields() {
        return $this->_fields;
    }

    public function isAutoincrement() {
        return $this->_autoIncrement;
    }

    public function setAutoIncrement($value = \TRUE) {
        $this->_autoIncrement = $value;
    }

    public function isMultiField() {
        return count($this->_fields) > 1;
    }

    public function getNamedValues($idValues) {
        // a single value becomes an array
        if (!\is_array($idValues)) {
            $namedValues[$this->_fields[0]] = $idValues;
        }
        else {
            if (count($idValues) != count($this->_fields)) {
                iris_contextItem(3);
                throw new \Iris\Exceptions\DBException('The number of key values is different from the number of key fields');
            }
            // if keys are not numeric, we have already an array with names, we keep it
            if (!is_numeric(implode('', array_keys($idValues)))) {
                $namedValues = $idValues;
                ksort($namedValues);
            }
            else {
                // otherwise, we are to match values to names, hoping the order is good
                $i = 0;
                foreach ($this->_fields as $fieldName) {
                    $namedValues[$fieldName] = $idValues[$i++];
                }
            }
        }
        return $namedValues;
    }

}
