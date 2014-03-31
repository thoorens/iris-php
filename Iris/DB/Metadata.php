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
 * A representation of a table structure:<ul>
 * <li> field descriptions
 * <li> primary key
 * <li> foreign keys
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Metadata implements \Serializable, \Countable {

    /**
     * A string containing the table name
     * 
     * @var string
     */
    private $_tablename = '';

    /**
     *
     * @var MetaItem[] 
     */
    private $_fields = array();

    /**
     * An array consisting of the names of the primary key fieds
     * 
     * @var string[] 
     */
    private $_primary = array();

    /**
     *
     * @var ForeignKey[]
     */
    private $_foreigns = array();

    /**
     * 
     * @param string $tableName
     */
    public function __construct($tableName = \NULL) {
        if (!is_null($tableName)) {
            $this->_tablename = $tableName;
        }
    }

    /**
     * Returns the primary key fields
     * 
     * @return string[]
     */
    public function getPrimary() {
        return $this->_primary;
    }

    /**
     * Add a primary key field
     * 
     * @param string $field
     * @return \Iris\DB\Metadata (for fluent interface)
     */
    public function addPrimary($field) {
        if (array_search($field, $this->_primary) === \FALSE) {
            $this->_primary[] = $field;
        }
        return $this;
    }

    /**
     * Add a new item to the metadata
     * 
     * @param MetaItem $item
     * @return \Iris\DB\Metadata (for fluent interface)
     */
    public function addItem($item) {
        $key = $item->getFieldName();
        $this->_fields[$key] = $item;
        return $this;
    }

    /**
     * Magic method permitting to acces metaItems through their name
     * 
     * @param string $key
     * @return MetaItem
     * @throws \Iris\Exceptions\DBException
     */
    public function __get($key) {
        if (isset($this->_fields[$key])) {
            return $this->_fields[$key];
        }
        throw new \Iris\Exceptions\DBException('Unknown field in metadata : ' . $key);
    }

    /**
     * Magic method permitting to know the existence of a metaItem field
     * 
     * @param string $key
     * @return booelan
     */
    public function __isset($key) {
        return isset($this->_fields[$key]);
    }

    /**
     * Gets the name of the associated table
     * @return string
     */
    public function getTablename() {
        return $this->_tablename;
    }

    /**
     * Getter for the foreign key array
     * 
     * @return array of ForeignKey 
     */
    public function getForeigns() {
        return $this->_foreigns;
    }

    /**
     * Getter for the field items
     * 
     * @return array
     */
    public function getFields() {
        return $this->_fields;
    }

    /**
     * Adds a foreign key object to the metadata and returns its offset
     * 
     * @param \Iris\DB\ForeignKey $foreign
     * @param int $number
     * @return int The foreign key offset in the internal array
     */
    public function addForeign(ForeignKey $foreign, $number = \NULL) {
        $this->_foreigns[] = $foreign;
        if (is_null($number)) {
            $number = count($this->_foreigns) - 1;
        }
        $foreign->setNumber($number);
        foreach ($foreign->getFromKeys() as $fromKey) {
            $this->_fields[$fromKey]->setForeignPointer($number);
        }
        return $number;
    }

//    public function getForeignKeyValues($fatherTable, $entity, $primaryKeys) {
//        $metadata = $entity->getMetadata();
//        $foreigns = $metadata->getForeigns();
//        $i = 0;
//        $searchKey = array();
//        $found = FALSE;
//        while ($i < count($foreigns) and !$found) {
//            $foreign = $foreigns[$i++];
//            if ($foreign->getTargetTable() == $fatherTable) {
//                $found = TRUE;
//                $toKeys = $foreign->getToKeys();
//                $fromKeys = $foreign->getFromKeys();
//                $searchKey = array();
//                $j = 0;
//                foreach ($primaryKeys as $key => $value) {
//                    if (array_search($key, $toKeys) === FALSE) {
//                        $found = FALSE;
//                    }
//                    else {
//                        $searchKey[$fromKeys[$j]] = $value;
//                    }
//                    $j++;
//                }
//            }
//        }
//        return $searchKey;
//    }

    /**
     * Retrieves the parent parameters (entity name and array of primary
     * key names) of an object through the metadata analysis
     * 
     * @param string $links
     * @return array
     */
    public function getParentRowParams($links) {
        $foreigns = $this->getForeigns();
        $found = \FALSE;
        /*         * @var ForeignKey $foreign */
        foreach ($foreigns as $foreign) {
            $fk = implode(IRIS_FIELDSEP, $foreign->getFromKeys());
            if ($fk == $links) {
                $found = \TRUE;
                // sorry Dijkstra, but each(), current() and other stuffs suck.
                break;
            }
        }
        if (!$found) {
            $links = str_replace(IRIS_FIELDSEP, ' and ', $links);
            throw new \Iris\Exceptions\DBException("A parent record from field(s) $links doesn't exist.");
        }
        $entityName = $foreign->getTargetTable();
        return [$entityName, $foreign->getFromKeys()];
    }

    /**
     * 
     * @param string $parentName
     * @param string[] $fkeys
     * @return array
     * @throws \Iris\Exceptions\DBException
     */
    public function getChildrenParams($parentName, $fkeys) {
        $found = \FALSE;
        foreach ($this->_foreigns as $foreign) {
            if ($foreign->getTargetTable() == $parentName) {
                if (count($fkeys) == 0) {
                    $found = \TRUE;
                }
                elseif (implode(IRIS_FIELDSEP, $fkeys) == implode(IRIS_FIELDSEP, $foreign->getFromKeys())) {
                    $found = \TRUE;
                }
            }
            // sorry Dijkstra, but each(), current() and other stuffs suck.
            if ($found)
                break;
        }

        if (!$found) {
            $links = implode(' ', $fkeys);
            throw new \Iris\Exceptions\DBException("A children recordset based on field(s) $links doesn't exist.");
        }
        return [$foreign->getToKeys(), $foreign->getFromKeys()];
    }

    /**
     * Transforms all the inside data (metaitem, primary and foreign keys)
     * in a string
     * 
     * @return string The format is as following:
     * FIELD@fieldName:id!type:INTEGER!size:0!defaultValue:!... (see MetaItem::serialize for details)
     * ...
     * PRIMARY@field1!$field2....
     * FOREIGN@0+from1!from2+targettable+to1!to2
     */
    public function serialize() {
        $strings[] = "TABLE@" . $this->_tablename;
        /* @var $item MetaItem */
        foreach ($this->_fields as $item) {
            $strings[] = $item->serialize();
        }
        $strings[] = "PRIMARY@" . implode('!', $this->_primary) . "\n";
        /* @var $foreign ForeignKey */
        foreach ($this->_foreigns as $foreign) {
            $strings[] = $foreign->serialize();
        }
        return implode("\n", $strings);
    }

    /**
     * Fills the items, primary key and foreignkeys from a string
     * 
     * @param string $serialized
     */
    public function unserialize($serialized) {
        $items = explode("\n", $serialized);
        foreach ($items as $item) {
            list($type, $value) = explode('@', $item . '@');
            switch ($type) {
                case 'TABLE':
                    $this->_tablename = $value;
                    break;
                case 'FIELD':
                    $item = new MetaItem('');
                    $item->unserialize($value);
                    $this->addItem($item);
                    break;
                case 'PRIMARY':
                    $primaries = explode('!', $value);
                    foreach ($primaries as $primary) {
                        $this->addPrimary($primary);
                    }
                    break;
                case 'FOREIGN':
                    $foreignKey = new ForeignKey($value);
                    $this->addForeign($foreignKey, $foreignKey->getNumber());
                    break;
            }
        }
    }

    /**
     * Returns the number of fields in the metadata
     * 
     * @return int
     */
    public function count() {
        return count($this->_fields);
    }

}
