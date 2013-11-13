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
 * A persistent object class. It has a relation with a table or a view.
 * It is abstract, so it has no direct instances. The instance
 * are created or retrieved by the static method GetEntity and 
 * are all explicit model classes or instances of InnerEntity
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
     * An array repository with all objects
     * 
     * @var Object[] 
     */
    private $_objectRepository = [];

    /**
     *
     * @var Query
     */
    private $_query;

    /**
     *
     * @var String 
     */
    protected $_entityName = \NULL;

    /**
     * A way to identify related entities when they have special names
     * 
     * @var string[]
     */
    protected $_externalEntities = [];

    /**
     * 
     * @var string
     */
    protected $_reflectionEntity = \NULL;

    /**
     * the _Entity manager used to manage the entity (linked to  
     * database dns and  parameters
     * 
     * @var \Iris\DB\_EntityManager
     */
    private $_entityManager = \NULL;

    /**
     * By default, the row are of type Object
     * @var string 
     */
    protected $_rowType = '\\Iris\\DB\\Object';

    /**
     * The primary key fields (while be filled by getMetadata if possible).
     * May safely initialized by hand
     * 
     * @var string[] 
     */
    protected $_idNames = [];

    /**
     * The foreign keys if not defined in the databases. Each
     * 
     * @var array
     * @todo This var does not seem to be initialized
     */
    protected $_foreignKeyDescriptions = [];

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
    protected $_metadata = \NULL;

    /**
     *
     * @var type 
     */
    protected $_formProperties = [];

    /**
     * The last inserted Id (only valid after an insertion
     * with autoincrement)
     * 
     * @var int 
     */
    protected $_lastInsertedId = \NULL;

    /* =======================================================================================================
     * C O N S T R U C T O R    A N D  F A C T O R Y   M E T H O D S
     * =======================================================================================================
     */

    /**
     * Finds an entity from 3 possible types of parameters : strings, entity Manager and metadata.
     * The entity is taken from the repository or created if necessary.
     * 
     * @param mixed analysed in code
     * @return _Entity
     * @throws \Iris\Exceptions\EntityException
     */
    public static function GetEntity() {
        $entityBuilder = new EntityBuilder(get_called_class(), func_get_args());
        return $entityBuilder->createExplicitModel();
    }

    /**
     * The constructor of an entity is protected and final. Every special
     * behavior may be inserted through the _init() method.
     * 
     */
    protected final function __construct($entityName) {
        if (is_null($this->_entityName)) {
            $this->_entityName = $entityName;
        }
        $this->_query = new Query();
        $this->_init();
    }

    /**
     * A substitute for "new" applied on entity. Should never be called directly. It is part of
     * the job of GetEntity(), which will first try to find an existing instance and will
     * initialize various internal properties.
     * 
     * This static method distinguishes the class and the table
     * 
     * @param string $className
     * @param string $entityName
     * @return \Iris\DB\_Entity
     */
    public static function GetNewInstance($className, $entityName) {
        return new $className($entityName);
    }

    /**
     * As the constructor is final, this methods offers a way to add special treatment 
     * after instanciation
     */
    protected function _init() {
        
    }

    
    /**
     * By default, the entity manager is defined by the system. This methods can
     * be overwritten in subclasses.
     * THIS METHOD IS MAINLY CALLED THROUGH call_user_func !!!
     * 
     * @return _EntityManager
     */
    public static function DefaultEntityManager() {
        return _EntityManager::GetInstance();
    }

    /**
     * Analyses parameters to find an entityManager if possible, a plausible
     * entityName and a className and the gets the corresponding entity.
     * from 0 to 3 parameters are expected.
     * 
     * @param mixed[] $params
     * @return array
     * @throws \Iris\Exceptions\EntityException
     */
    protected static function _AnalyseParameters($params) {
        if (count($params) > 3) {
            throw new \Iris\Exceptions\EntityException('To much parameters for GetEntity().');
        }
        $strings = 0;
        $entityName = \NULL;
        $entityManager = \NULL;
        $alternativeClassName = get_called_class();
        $metadata = \NULL;
        foreach ($params as $param) {
            if ($param instanceof \Iris\DB\_EntityManager) {
                $entityManager = $param;
            }
            elseif ($param instanceof \Iris\DB\Metadata) {
                $metadata = $param;
            }
            elseif (is_string($param)) {
                // the first string will be a entity name
                if (is_null($entityName)) {
                    $entityName = $param;
                    $strings++;
                }
                elseif ($strings++ == 1) {
                    $alternativeClassName = $param;
                }
                else {
                    throw new \Iris\Exceptions\EntityException('3 strings parameters given for GetEntity');
                }
            }
        }
        static::_SpecificPlugIn($entityName, $alternativeClassName, $entityManager);
        return [$entityName, $alternativeClassName, $entityManager, $metadata];
    }

    protected static function _SpecificPlugIn(&$entityName, &$alternativeClassName) {
        if ($alternativeClassName == 'Iris\DB\_Entity') {
            if ($entityName[0] != '\\') {

                $alternativeClassName = "\\models\\T" . ucfirst($entityName);
            }
            else {
                $alternativeClassName = $entityName;
            }
        }
        if ((is_null($entityName)) or ($entityName == $alternativeClassName)) {
            $entityName = strtolower(substr(basename(\Iris\System\Functions::Class2File($alternativeClassName), '.php'), 1));
        }
    }

    /**
     * Forces an specific class file for the parent or children
     * 
     * @param string $entityName
     * @return string
     * @todo see if that trick is necessary
     */
    public function getExternalClassName($entityName) {
        if (isset($this->_externalEntities[$entityName])) {
            $className = $this->_externalEntities[$entityName];
        }
        else {
            $className = 'T' . ucfirst($entityName);
        }
        return "\\$className";
    }

    /**
     * Tests if the object created with this entity are read only.
     * Always FALSE, except in Views
     * 
     * @return boolean
     */
    public function isReadOnly() {
        return \FALSE;
    }

    /* =======================================================================================================
     * O B J E C T   M A N A G E M E N T
     * =======================================================================================================
     */

    /**
     * Put an object in the repository
     * 
     * @param string $entityName
     * @param string[] $idValues
     * @param Object $object 
     */
    public function registerObject($idValues, $object) {
        $id = implode('|', $idValues);
        $this->_objectRepository[$id] = $object;
    }

    /**
     * Retrieve an object from the repository (if possible, \NULL otherwise)
     * 
     * @param  string $entityName
     * @param string[] $idValues
     * @return Object 
     */
    public function retrieveObject($idValues) {
        $id = implode('|', $idValues);
        if (isset($this->_objectRepository[$id])) {
            return $this->_objectRepository[$id];
        }
        else {
            return \NULL;
        }
    }

    /**
     * 
     * @param mixed[] $oldIdValues
     * @param mixed[] $newIdValues
     * @param Object $this
     */
    public function updateRegistry($oldIdValues, $newIdValues) {
        $modified = \FALSE;
        foreach ($oldIdValues as $key => $value) {
            if ($newIdValues[$key] != $value) {
                $modified = \TRUE;
            }
        }
        if ($modified) {
            $oldId = implode('|', $oldIdValues);
            $this->registerObject($newIdValues, $this->_objectRepository[$oldId]);
            unset($this->_objectRepository[$oldId]);
        }
    }

    public function hasObjects() {
        return count($this->_objectRepository) > 0;
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
     * Accessor set for the id field list. NEVER erases a previously set value
     * 
     * @param string[] $idNames 
     */
    public function setIdNames($idNames) {
        if (count($this->_idNames) == 0) {
            if (!is_array($idNames)) {
                $idNames = array($idNames);
            }
            $this->_idNames = $idNames;
        }
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
     * Accessor set for the entity (table) name.
     * NEVER erases a previously set value
     * 
     * @param string $entityName
     */
    public function setEntityName($entityName, $force = \FALSE) {
        if (is_null($this->_entityName) or $force) {
            $this->_entityName = $entityName;
        }
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
    public function fetchAll($array = FALSE) {
        $sql = sprintf('SELECT %s FROM %s', $this->_query->renderSelectFields(), $this->_entityName);
        $sql .= $this->_query->renderWhere();
        $sql .= $this->_query->renderOrder();
        $sql .= ';';
        //die($sql);
        $data = $this->_entityManager->fetchAll($this, $sql, $this->_query->getPlaceHolders());
        $this->_query->reset();
        if ($array) {
            foreach ($data as $object) {
                $finalData[] = $object->asArray();
            }
        }
        else {
            $finalData = $data;
        }
        return $finalData;
    }

    /**
     * Get, if possible, a line from a table knowing its primary key 
     * 
     * @param mixed $idValues
     * @return Object 
     */
    public function find($idValues) {
        if (!\is_array($idValues)) {
            $idValues = [$this->_idNames[0] => $idValues];
        }
        // try to find object in repository
        $object = $this->retrieveObject($idValues);
        if (is_null($object)) {
            $this->wherePairs($idValues);
            $object = $this->fetchRow();
        }
        return $object;
    }

    /**
     * Obtains the id of first, previous, next, last element of the table
     * 
     * @staticvar array $ids Conserves the values between calls
     * @param int $position Position of the element 
     * @return mixed
     */
    public function getId($position) {
        throw new \Iris\Exceptions\NotSupportedException('Browser functions needs to be written and tested');
        static $ids = array(NULL, \NULL, \NULL, \NULL);
        if (is_null($ids[$position])) {
            $em = $this->getEntityManager();
            switch ($position) {
                case self::FIRST:
                case self::PREVIOUS:
                    $sql = $em->leftLimits;
                    $aResult = $em->directSQLQuery($sql);
                    $result = $aResult[0];
                    $ids[self::FIRST] = $result['First'];
                    $ids[self::PREVIOUS] = $result['Previous'];
                    break;
                case self::NEXT:
                case self::LAST:
                    $sql = $em->rightLimits;
                    $aResult = $em->directSQLQuery($sql);
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
    public function fetchRow($condition = \NULL, $value = \NULL) {
        if (!is_null($condition)) {
            if (is_array($condition)) {
                $this->wherePairs($condition);
            }
            else {
                $this->where($condition, $value);
            }
        }
        $all = $this->fetchAll();
        return count($all) ? $all[0] : \NULL;
    }

    /* ======================================================================== 
     * W H E R E   M E T H O D S
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
    public function where($condition, $value = \NULL) {
        $this->_query->where($condition, $value);
        return $this;
    }

    /**
     * Add a serie of pairs field=>value to the where clause
     * 
     * @param string[] $conditions
     * @param booelan $operator if True (def), consider an equal operator
     * @return _Entity (fluent interface)
     */
    public function wherePairs($conditions, $operator = '=') {
        foreach ($conditions as $field => $value) {
            $this->_query->where("$field$operator", $value);
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
        $this->_query->whereClause($condition, \TRUE);
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
     * Add a IS \NULL part to Where clause
     * 
     * @param string $field
     * @return _Entity  (fluent interface) 
     */
    public function whereNull($field) {
        $this->whereClause(" $field IS NULL ");
        return $this;
    }

    /**
     * Add a IS NOT \NULL part to Where clause
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

    /* =========================================================================================
     * L O G I C A L   O P E R A T O R S
     * =========================================================================================
     */

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

    /* =======================================================================================================
     * S Q L   E Q U I V A L E N T S
     * =======================================================================================================
     */

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
    public function createRow($data = \NULL) {
        $metadata = $this->getMetadata();
        if (is_null($data)) {
            $data = [];
        }
        foreach ($metadata->getFields() as $item) {
            $field = $item->getFieldName();
            if (!isset($data[$field])) {
                $data[$field] = \NULL;
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
     * @param mixed[] $primaryKeys
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
                return \NULL;
            }
            $select = sprintf('SELECT MAX(%s) as NUM FROM %s;', $ids[0], $this->_entityName);
            
            /* @var $lignes Dialects\MyPDOStatement */
            $lignes = $this->_entityManager->directSQLQuery($select);
            $ligne = $lignes->fetchObject();
            return $ligne->NUM;
        }
    }

    /* ==========================================================================================================
     * M E T A D A T A    M A N A G E M E N T
     * ==========================================================================================================
     */

    /**
     * Obtain the metadata from either <ul>
     * <li> a hardcoded metadata or
     * <li> the result stored after a precedent analysis or
     * <li> a metadata stored by hand in the entity (see overloading of _readMetadata()
     * <li> an analysis of the database
     * </ul>
     * 
     * @return MetaData 
     */
    public final function getMetadata($metadata = \NULL) {
        if (is_null($this->_metadata)) {
            $this->_metadata = $this->_readMetadata($metadata);
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
            $this->setMetadata($metadata);
        }
        return $this->_metadata;
    }

    /**
     * Accessor Set for metadata (caution : NEVER erases a explicit metadata)
     * @param Metadata $metadata
     */
    public function setMetadata($metadata) {
        if (is_null($this->_metadata)) {
            $this->_metadata = $metadata;
        }
    }

    /**
     * Accessor Set for the entity manager used by the class
     * 
     * @param \Iris\DB\_EntityManager $entityManager
     * @return \Iris\DB\_Entity
     */
    public function setEntityManager(\Iris\DB\_EntityManager $entityManager) {
        $this->_entityManager = $entityManager;
        return $this;
    }

    /**
     * Reads the metadata for a table using the suitable entity manager.
     * This method may be overridden to provide a metadata generated elsewhere
     * @see \models\TMetadataModel in WB
     * 
     * @param Metadata $metadata
     * @return Metadata 
     */
    protected function _readMetadata($metadata = \NULL) {
        if (is_null($metadata)) {
            // reflection entities are only used for views
            if (is_null($this->_reflectionEntity)) {
                $this->_reflectionEntity = $this->_entityName;
            }
            /* @var $metadata Metadata */
            $metadata = $this->_entityManager->readFields($this->_reflectionEntity);
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
        }

        return $metadata;
    }

    public function validate() {
        if (is_null($this->_entityName)) {
            throw new \Iris\Exceptions\EntityException('The entity has no associated object in the database.');
        }
        if (is_null($this->_metadata) or count($this->_metadata->getFields()) == 0) {
            throw new \Iris\Exceptions\EntityException('No metadata has been provided or found for this entity.');
        }
        if (count($this->_idNames) == 0) {
            throw new \Iris\Exceptions\EntityException('This entity does not have a specified primary key.');
        }
    }

}

