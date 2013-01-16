<?php

namespace Iris\DB;

use Iris\Exceptions as ie;

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
 * A persistent object class. It has a relation with a table or a view.
 * As an abstract class, it has no instance. Three way to create entities:<ul>
 * <li> an explicit model derived from it (in the case special methods are needed)
 * <li> an AutotEntity (much simpler)
 * <li> an ViewEntity
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _Entity {

    const FIRST = 0;
    const PREVIOUS = 1;
    const NEXT = 2;
    const LAST = 3;

    /**
     *
     * @var Query
     */
    private $_query;

    /**
     *
     * @var String 
     */
    protected $_entityName = NULL;

    /**
     * 
     * @var string
     */
    protected $_reflectionEntity = NULL;

    /**
     * the _Entity manager used to manage the entity (linked to  
     * database dns and  parameters
     * 
     * @var \Iris\DB\_EntityManager
     */
    private $_entityManager = NULL;
    protected static $_fixedEntityManager = \FALSE;

    /**
     * By default, the row are of type Object
     * @var string 
     */
    protected $_rowType = '\\Iris\\DB\\Object';

    /**
     * The primary key fields (while be filled by getMetadata if possible).
     * May safely initialized by hand
     * 
     * @var array 
     */
    protected $_idNames = array();

    /**
     * The foreign keys if not defined in the databases. Each
     * 
     * @var array
     */
    protected $_foreignKeyDescriptions = array();

    /**
     * The name of the field serving for description of an concrete object
     * 
     * @var string 
     */
    protected $_descriptionField = '';

    /**
     * Metadata are stored for performance reasons
     * @var  Metadata
     */
    protected $_metadata = NULL;
    protected $_formProperties = array();

    /**
     * The last inserted Id (only valid after an insertion
     * with autoincrement)
     * 
     * @var int 
     */
    protected $_lastInsertedId = NULL;

    /**
     * The constructor of "normal" entities makes the initialisation himself 
     * 
     * @param _EntityManager $EM : a special entity manager for that class
     */
    public function __construct($EM = NULL) {
        $this->_initialize($EM);
    }

    /**
     * The initializer does 2 tasks:<ul>
     * <li>provide a entity manager for the entity if it has none specified
     * in constructor parameter </li>
     * <li>prepare a statemant SELECT * with no WHERE nor ORDER clause</li>
     * </ul>
     * It is separated from constructor to permit AutoEntity to do some task
     * before initialisation.
     * 
     * @param _EntityManager $EM : a special entity manager for that class
     */
    protected function _initialize($EM) {
        if (is_null($EM)) {
            // tests the existence of an alternative EM
            if (static::$_fixedEntityManager!=\FALSE) {
                $crudClass = static::$_fixedEntityManager;
                $EM = $crudClass::GetEM();
            }
            // otherwise take the default one
            else {
                $EM = _EntityManager::GetInstance();
            }
        }
        $this->_entityManager = $EM;
        if (is_null($this->_entityName)) {
            $className = basename(str_replace('\\', '/', get_class($this)));
            $this->_entityName = strtolower(substr($className, 1));
        }
        // reflection entities are only used for views
        if (is_null($this->_reflectionEntity)) {
            $this->_reflectionEntity = $this->_entityName;
        }
        $this->getMetadata();
        $this->_query = new Query();
        $EM->registerEntity($this);
    }

    /**
     * Accessor get for the id field list
     * 
     * @return array
     */
    public function getIdNames() {
        return $this->_idNames;
    }

    /**
     * Accessor set for the id field list
     * 
     * @param array $idNames 
     */
    public function setIdNames($idNames) {
        if (!is_array($idNames)) {
            $idNames = array($idNames);
        }
        $this->_idNames = $idNames;
    }

    /**
     * Returns the name of the field serving for the description of a concrete
     * object
     * 
     * @return string 
     */
    public function getDescriptionField() {
        return $this->_descriptionField;
    }

    /**
     * Accessor get for the entity (table) name
     * 
     * @return string
     */
    public function getEntityName() {
        return $this->_entityName;
    }

    /**
     * Accessor set for the entity (table) name
     * 
     * @param string $entityName
     */
    public function setEntityName($entityName) {
        $this->_entityName = $entityName;
    }

    /**
     * It is possible to modify the type of rows returned
     * 
     * @param string $rowType 
     */
    public function setRowType($rowType) {
        $this->_rowType = $rowType;
    }

    /**
     * Get rowtype for this entity (by default object, but can
     * be an derived class)
     * 
     * @return string 
     */
    public function getRowType() {
        return $this->_rowType;
    }

    /**
     * Execute a multi-line query
     * with fields, where and order clauses if necessary.
     * Aterward, it reset the object for reusing it from scratch
     * 
     * @param boolean $array if true returns an array of array instead of array of objects
     * @return miexed 
     */
    public function fetchall($array = FALSE) {
        $sql = sprintf('SELECT %s FROM %s', $this->_query->renderSelectFields(), $this->_entityName);
        $sql .= $this->_query->renderWhere();
        $sql .= $this->_query->renderOrder();
        $sql .= ';';
        //die($sql);
        $all = $this->_entityManager->fetchall($this, $sql, $this->_query->getPlaceHolders());
        $this->_query->reset();
        if ($array) {
            $all = self::AsArray($all);
        }
        return $all;
    }

    /**
     * Get, if possible, a line from a table knowing its primary key 
     * 
     * @param mixed $idValues
     * @return Object 
     */
    public function find($idValues) {
        $idValues = \is_array($idValues) ? $idValues : array($this->_idNames[0] => $idValues);
        // try to find object in repository
        $object = $this->_entityManager->retrieveObject($this->_entityName, $idValues);
        if (is_null($object)) {
            $this->wherePairs($idValues);
            $object = $this->fetchRow();
        }
        return $object;
    }

    /**
     * Obtains the id of fiirst, previous, next, last element of the table
     * 
     * @staticvar array $ids Conserves the values between calls
     * @param int $position Position of the element 
     * @return mixed
     */
    public function getId($position) {
        throw new \Iris\Exceptions\NotSupportedException('Browser functions needs to be written and tested');
        static $ids = array(NULL, NULL, NULL, NULL);
        if (is_null($ids[$position])) {
            $em = $this->getEntityManager();
            switch ($position) {
                case self::FIRST:
                case self::PREVIOUS:
                    $sql = $em->leftLimits;
                    $aResult = $em->directSQL($sql);
                    $result = $aResult[0];
                    $ids[self::FIRST] = $result['First'];
                    $ids[self::PREVIOUS] = $result['Previous'];
                    break;
                case self::NEXT:
                case self::LAST:
                    $sql = $em->rightLimits;
                    $aResult = $em->directSQL($sql);
                    $result = $aResult[0];
                    $ids[self::NEXT] = $result['Next'];
                    $ids[self::LAST] = $result['Last'];
                    break;
            }
        }
        return $ids[$position];
    }

    /**
     * Get, if possible, the id of last line inserted with an autokey
     */
    public function findLast() {
        $id = $this->lastInsertedId();
        return $this->find($id);
    }

    /**
     *
     * @param type $condition
     * @param Object $value 
     */
    public function fetchRow($condition = NULL, $value = NULL) {
        if (!is_null($condition)) {
            if (is_array($condition)) {
                $this->wherePairs($condition);
            }
            else {
                $this->where($condition, $value);
            }
        }
        $all = $this->fetchall();
        return count($all) ? $all[0] : NULL;
    }

    /* ======================================================================== 
     * WHERE 
     * ========================================================================
     */

    /**
     * Add a pair condition (field + comparator) - value
     * to the where clause
     * 
     * @param mixed $condition
     * @param mixed $value
     * @return _Entity (fluent interface) 
     */
    public function where($condition, $value = NULL) {
        $this->_query->where($condition, $value);
        return $this;
    }

    /**
     * Add a serie of pairs field=>value to the where clause
     * 
     * @param array $conditions
     * @return _Entity (fluent interface)
     */
    public function wherePairs($conditions) {
        foreach ($conditions as $field => $value) {
            $this->_query->where("$field=", $value);
        }
        return $this;
    }

    /**
     * Add an explicit part to where clause
     * 
     * @param string $condition
     * @return _Entity (fluent interface) 
     */
    public function whereClause($condition) {
        $this->_query->whereClause($condition);
        return $this;
    }

    /**
     * Add a LIKE part to where clause (with possible variant)
     * 
     * @param String $field
     * @param String $value
     * @param int $mode : one of Query::LIKE, ::CONTENTS ::BEGINS ::ENDS
     * @return _Entity (fluent interface)
     */
    public function whereLike($field, $value, $mode = Query::LIKE) {
        $this->_query->whereLike($field, $value, $mode);
        return $this;
    }

    /**
     *
     * @return _Entity (fluent interface) 
     */
    public function _AND_() {
        $this->_query->_And_();
        return $this;
    }

    /**
     *
     * @return _Entity  (fluent interface)
     */
    public function _OR_() {
        $this->_query->_Or_();
        return $this;
    }

    /**
     *
     * @return _Entity  (fluent interface)
     */
    public function _NOT_() {
        $this->_query->_Not_();
        return $this;
    }

    /**
     * Add a IS NULL part to Where clause
     * 
     * @param string $field
     * @return _Entity  (fluent interface) 
     */
    public function whereNull($field) {
        $this->whereClause(" $field IS NULL ");
        return $this;
    }

    /**
     * Add a IS NOT NULL part to Where clause
     * 
     * @param string $field
     * @return _Entity  (fluent interface) 
     */
    public function whereNotNull($field) {
        $this->whereClause(" $field IS NOT NULL ");
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $bits
     * @return _Entity  (fluent interface)
     */
    public function whereBitAnd($field, $bits) {
        $this->_query->whereBitAnd($field, $bits, $this->_entityManager);
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $bits
     * @return _Entity  (fluent interface)
     */
    public function whereBitOr($field, $bits) {
        $this->_query->whereBitOr($field, $bits, $this->_entityManager);
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $bits
     * @return _Entity  (fluent interface)
     */
    public function whereBitXor($field, $bits) {
        $this->_query->whereBitXor($field, $bits, $this->_entityManager);
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $value1
     * @param type $value2
     * @return _Entity  (fluent interface)
     */
    public function whereBetween($field, $value1, $value2) {
        $this->_query->whereBetween($field, $value1, $value2);
        return $this;
    }

    /**
     *
     * @param type $field
     * @param type $values
     * @return _Entity  (fluent interface)
     */
    public function whereIn($field, $values) {
        $this->_query->whereIn($field, $values);
        return $this;
    }

    /**
     * Record a order clause for the sql statement
     * 
     * @param string $orderClause
     * @return _Entity  (fluent interface)
     */
    public function order($orderClause) {
        $this->_query->setOrder($orderClause);
        return $this;
    }

    /**
     *
     * @param type $fields
     * @return _Entity  (fluent interface)
     */
    public function select($fields) {
        $this->_query->select($fields);
        return $this;
    }

    /**
     * Create a new row (object) possibly with data in it
     *  
     * @return Object 
     */
    public function createRow($data = NULL) {
        $metadata = $this->getMetadata();
        if (is_null($data)) {
            $data = array();
        }
        foreach ($metadata->getFields() as $item) {
            $field = $item->getFieldName();
            if (!isset($data[$field])) {
                $data[$field] = NULL;
            }
        }
        $objectType = $this->getRowType();
        $object = new $objectType($this, \NULL, $data, TRUE);
        return $object;
    }

    /**
     * Accessor for entity Manager
     * 
     * @return _EntityManager 
     */
    public function getEntityManager() {
        return $this->_entityManager;
    }

    /**
     * Update a line in a table 
     * 
     * @param type $setFields
     * @param type $values
     * @param type $idValues
     * @return boolean : true if no error 
     */
    public function update($setFields, $values, $idValues) {
        $setClause = $this->_query->renderSet($setFields, $values);
        $iId = 0;
        foreach ($idValues as $value) {
            $this->_query->where($this->_idNames[$iId] . "=", $value);
        }
        $whereClause = $this->_query->renderWhere();
        $sql = sprintf("UPDATE %s SET %s %s", $this->_entityName, $setClause, $whereClause);
        $done = $this->_entityManager->exec($sql, $this->_query->getPlaceHolders());
        $this->_query->reset();
        return $done;
    }

    /**
     * Inserts a new line in the table
     * 
     * @param type $insertFields
     * @param type $values
     * @return boolean : true if insertion done
     */
    public function insert($insertFields, $values) {
        list($insertClause, $valueClause) = $this->_query->renderInsert($insertFields, $values);
        $sql = sprintf("INSERT INTO %s(%s) VALUES(%s);", $this->_entityName, $insertClause, $valueClause);
        $done = $this->_entityManager->exec($sql, $this->_query->getPlaceHolders());
        if ($done) {
            $this->_lastInsertedId = $this->_entityManager->getConnexion()->lastInsertId();
        }
        $this->_query->reset();
        return $done;
    }

    /**
     * Delete a line from table with primary key condition
     * 
     * @param array $primaryKeys
     * @return boolean 
     */
    public function delete($primaryKeys) {

        $this->wherePairs($primaryKeys);
        $sql = "DELETE FROM " . $this->_entityName;
        $sql .= $this->_query->renderWhere();
        $done = $this->_entityManager->exec($sql, $this->_query->getPlaceHolders());
        $this->_query->reset();
        return $done;
    }

    /**
     * Accessor get for lastinserted id
     * 
     * @return int 
     */
    public function lastInsertedId() {
        $lastId = $this->_lastInsertedId;
        // if lost the value, try to get it again
        if (is_null($lastId)) {
            $ids = $this->getMetadata()->getPrimary();
            if (count($ids) > 1) {
                return NULL;
            }
            $select = sprintf('SELECT MAX(%s) as NUM FROM %s;', $ids[0], $this->_entityName);
            $lignes = $this->_entityManager->directSQL($select);
            $ligne = $lignes[0];
            return $ligne['NUM'];
        }
    }

    /**
     * Obtain the metadata from either <ul>
     * <li> the result stored after a precedent analysis or
     * <li> a string stored by hand in the entity or
     * <li> an analysis of the database
     * </ul>
     * 
     * @return MetaData 
     */
    public function getMetadata() {
        if (is_null($this->_metadata)) {
            $this->_metadata = $this->_readMetadata();
            // add optional form properties
            foreach ($this->_formProperties as $field => $property) {
                list($name, $value) = $property;
                $this->_metadata[$field]->$name = $value;
            }
            if (count($this->_idNames) == 0) {
                $this->_idNames = $this->_metadata->getPrimary();
            }
        }
        // Metadata can be written in entity class as an unserialized string
        if (is_string($this->_metadata)) {
            $metadata = new Metadata();
            $metadata->unserialize($this->_metadata);
            $this->_metadata = $metadata;
        }
        return $this->_metadata;
    }

    /**
     * Reads the metadata for a table using the suitable entity manager
     * 
     * @return Metadata 
     */
    protected function _readMetadata() {
        /* @var $metadata Metadata */
        $metadata = $this->_entityManager->readFields($this->_entityName);
        foreach ($this->_entityManager->getForeignKeys($this->_reflectionEntity) as $foreignKey) {
            $metadata->addForeign($foreignKey);
        }
        foreach ($this->_idNames as $field) {
            $metadata->addPrimary($field);
        }
        // optionaly reads manually added foreign keys
        foreach ($this->_foreignKeyDescriptions as $key => $description) {
            if (is_string($description)) {
                $metadata->unserialize($description);
            }
            else {
                $foreignKey = new ForeignKey();
                $foreignKey->setNumber($key);
                $foreignKey->setFromKeys($description[0]);
                $foreignKey->setTargetTable($description[1]);
                $foreignKey->setToKeys($description[2]);
            }
        }
        return $metadata;
    }

    public static function AsArray($data) {
        foreach ($data as $object) {
            $array[] = $object->asArray();
        }
        return $array;
    }

    /* ================================================================
     * Depracated
     */

    /**
     *
     * @deprecated
     * @var array 
     */
    protected static $_FieldDesc = array();

    /**
     * @deprecated
     * @return array 
     */
    public function fillContent() {
        throw new ie\DeprecatedException('fillcontent is deprecated');
    }

    /**
     *
     * @return array
     * @depracated 
     */
    public static function GetFieldDesc() {
        throw new ie\DeprecatedException('GetFieldDesc is deprecated');
    }

    /**
     *
     * @return _Entity 
     * @deprecated
     */
    public function makeWhere() {
        throw new ie\DeprecatedException('makeWhere is deprecated');
    }

}

?>
