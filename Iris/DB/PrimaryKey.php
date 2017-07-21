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

    /**
     * 
     * @param type $idValues
     * @return type
     * @throws \Iris\Exceptions\DBException
     */
    public function getNamedValues($idValues) {
        //show_nd('getNamedValues');
        //show_nd($idValues);
        ////show_nd($idValues);
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
        ////show_nd($namedValues);
        return $namedValues;
    }

}
