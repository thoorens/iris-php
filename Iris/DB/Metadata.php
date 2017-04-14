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
     * 
     */
    const FIELD_SEP = '__';

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
    private $_fields = [];

    /**
     * A special object containing the primary key description
     * 
     * @var PrimaryKey
     */
    private $_primary;

    /**
     * An array containing the foreign keys of the table
     * 
     * @var ForeignKey[]
     */
    private $_foreigns = [];

    /**
     *
     * @var string
     */
    private $_bridgeId;

    /**
     *
     * @var string 
     */
    private $_targetId;

    /**
     * Spectifies if the table has an auto increment primary key
     * @var boolean
     */
    private $_autoIncrementPrimary = \FALSE;
    private $_parentRows = [];

    /**
     * 
     * @param string $tableName
     */
    public function __construct($tableName = \NULL) {
        if (!\is_null($tableName)) {
            $this->_tablename = $tableName;
        }
        $this->_primary = new PrimaryKey();
    }

    /**
     * Returns the primary key fields
     * 
     * @return PrimaryKey
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
        $this->_primary->addField($field);
        // Multikey primary key are not autoincrement
        if ($this->_primary->isMultiField()) {
            $this->_autoIncrementPrimary = \FALSE;
            $this->_primary->setAutoIncrement(\FALSE);
        }
        return $this;
    }

    /**
     * Adds a new item to the metadata
     * 
     * @param MetaItem $item
     * @return \Iris\DB\Metadata (for fluent interface)
     */
    public function addItem($item) {
        $key = $item->getFieldName();
        if ($item->isAutoIncrement() and $item->isPrimary()) {
            $this->_autoIncrementPrimary = \TRUE;
            $this->_primary->setAutoIncrement();
        }
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
        if (\is_null($number)) {
            $number = \count($this->_foreigns) - 1;
        }
        $foreign->setNumber($number);
        foreach ($foreign->getFromKeys() as $fromKey) {
            $this->_fields[$fromKey]->setForeignPointer($number);
        }
        return $number;
    }

    /**
     * Retrieves the parent parameters (entity name and array of primary
     * key names) of an object through the metadata analysis
     * 
     * @param string $links
     * @return array
     */
    public function getParentRowParams($links) {
        if (\is_array($links)) {
            $links = \implode(self::FIELD_SEP, $links);
        }
        if (!isset($this->_parentRows[$links])) {
            $foreigns = $this->getForeigns();
            $found = \FALSE;
            /** @var ForeignKey $foreign */
            foreach ($foreigns as $foreign) {
                $fromKeys = $foreign->getFromKeys();
                $fk = \implode(self::FIELD_SEP, $fromKeys);
                if ($fk === $links) {
                    $found = \TRUE;
                    break; // sorry Dijkstra, but each(), current() and other stuffs suck.
                }
                $fk2 = \implode(self::FIELD_SEP, \array_reverse($fromKeys));
                if ($fk2 === $links) {
                    $found = \TRUE;
                    break; // sorry Dijkstra, but each(), current() and other stuffs suck.
                }
            }
            if (!$found) {
                $links = \str_replace(self::FIELD_SEP, ' and ', $links);
                throw new \Iris\Exceptions\DBException("A parent record from field(s) $links doesn't exist.");
            }
            $entityName = $foreign->getTargetTable();
            $this->_parentRows[$links] = [$entityName, $foreign->getKeys()];
        }
        return $this->_parentRows[$links];
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
                if (\count($fkeys) === 0) {
                    $found = \TRUE;
                }
                elseif (\implode(self::FIELD_SEP, $fkeys) === \implode(self::FIELD_SEP, $foreign->getFromKeys())) {
                    $found = \TRUE;
                }
            }
            if ($found) {
                break;// sorry Dijkstra, but each(), current() and other stuffs suck.
            }
        }

        if (!$found) {
            $links = \implode(' ', $fkeys);
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
        $strings[] = $this->_primary->serialize();
        /* @var $foreign ForeignKey */
        foreach ($this->_foreigns as $foreign) {
            $strings[] = $foreign->serialize();
        }
//        $strings[] = 'BRIDGES@' . implode('!', $this->_bridges);
        $strings[] = 'AUTOPK@' . ($this->_autoIncrementPrimary ? MetaItem::S_TRUE : MetaItem::S_FALSE);
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
                    $this->_primary = new PrimaryKey($value);
                    break;
                case 'FOREIGN':
                    $foreignKey = new ForeignKey($value);
                    $this->addForeign($foreignKey, $foreignKey->getNumber());
                    break;
                case 'AUTOPK':
                    $this->_autoIncrementPrimary = $value === MetaItem::S_TRUE;
                    $this->_primary->setAutoIncrement($this->_autoIncrementPrimary);
                    break;
//                case 'BRIDGES':
//                    $this->_bridges = explode('!', $value);
//                    break;
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

    /**
     * 
     * @param _Entity $entity
     */
    public function getBridgeParams($entity) {
        foreach ($this->getForeigns() as $foreign) {
            //print($foreign->getTargetTable().':' );
            //print($entity->getEntityName().' - ');
            if ($foreign->getTargetTable() == $entity->getEntityName()) {
                $this->_bridgeId = $foreign->getFromKeys()[0];
                //print('bridge : '.$foreign->getFromKeys()[0].'<br/>');
            }
            else {
                $this->_targetId = $foreign->getFromKeys()[0];
                //print('target : '.$foreign->getFromKeys()[0].'<br/>');
            }
        }
    }

    /**
     * 
     * @param _Entity $entity
     * @param Object $object
     * @param string $bridgeName
     * @return array
     */
    public function manageBridge($entity, $object, $bridgeName) {
        $result = []; // maybe no result
        if (is_null($this->_bridgeId)) {
            $this->getBridgeParams($entity);
        }
        $pivots = $object->getChildren([$bridgeName, $this->_bridgeId]);
        foreach ($pivots as $pivot) {
            $result[] = [$pivot->getParent($this->_targetId), $pivot];
        }
        return $result;
    }

}
