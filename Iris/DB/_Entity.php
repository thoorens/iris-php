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
    const CONT = -1;
    const INDEXED = 2;

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
     * The name of the entity (the table or view used in SQL queries)
     * 
     * @var String 
     */
    protected $_entityName = \NULL;
    protected static $_TableName = \NULL;

    /**
     * The number of the entity manager linked to the database
     * If it is 0 the database defined in config/xxdatabase.ini will be used
     * 
     * @var int
     */
    protected static $_EntityNumber = 0;

    /**
     * the _Entity manager used to manage the entity (linked to  
     * database dns and  parameters)
     * 
     * @var \Iris\DB\_EntityManager
     */
    private $_entityManager = \NULL;

    /**
     * 
     * @var String[]
     */
    public static $_Bridges = [];

    /**
     * For extension purpose
     * @var _EntityManager
     */
    protected static $_AlternateEntityManager = \NULL;

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
     * By default, the row are of type Object
     * @var string 
     */
    protected $_rowType = '\\Iris\\DB\\Object';

    /**
     * The primary key fields (while be filled by getMetadata if possible).
     * May safely initialized by hand
     * 
     * @var string[] 
     * @deprecated
     */
    protected $_idNames = [];

    /**
     *
     * @var PrimaryKey
     */
    protected $_primaryKey = \NULL;

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
    protected static $_MainField = 'Element #';

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

    /**
     * If FALSE, do not register objects on fetch/find
     * 
     * @var boolean
     */
    protected $_register = \TRUE;
    protected $_parents = [];

    /* =======================================================================================================
     * C O N S T R U C T O R    A N D  F A C T O R Y   M E T H O D S
     * =======================================================================================================
     */

    /**
     * Finds an entity from 3 possible types of parameters : strings, entity Manager and metadata.
     * The entity is taken from the repository or created if necessary.
     * 
     * @param mixed analysed in code
     * @return \Iris\DB\_Entity
     * @throws \Iris\Exceptions\EntityException
     */
    public static function GetEntity() {
        $args = func_get_args();
        if (static::$_EntityNumber > 0) {
            $args[] = static::$_EntityNumber;
        }
        $entityBuilder = new EntityBuilder(get_called_class(), $args);
        return $entityBuilder->createExplicitModel();
    }

    /**
     * The constructor of an entity is protected and final. Every special
     * behavior may be inserted through the _init() method.
     * 
     */
    protected final function __construct($entityName) {
        //print "Entity construction : $entityName<br>";
        if (is_null(static::$_TableName)) {
            $this->_entityName = $entityName;
            //static::$_TableName = $entityName;
        }
        if (count($this->_idNames)) {
            $primaryKey = new PrimaryKey();
            foreach ($this->_idNames as $field) {
                $primaryKey->addField($field);
            }
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
        return _EntityManager::EMByNumber(); //GetInstance();
    }

    /**
     * This method offers a way to modify the entity number
     * outside the sub class definition
     * 
     * @param type $EntityNumber
     */
    static function SetEntityNumber($EntityNumber) {
        self::$_EntityNumber = $EntityNumber;
    }

    static function SetTableName($TableName) {
        self::$_TableName = $TableName;
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

    /**
     * Not documented
     * 
     * @param string $entityName
     * @param string $alternativeClassName
     */
    protected static function _SpecificPlugIn(&$entityName, &$alternativeClassName) {
        if ($alternativeClassName == 'Iris\DB\_Entity') {
            if ($entityName[0] != '\\') {
                $alternativeClassName = \Iris\System\Functions::TableToEntity($entityName);
            }
            else {
                $alternativeClassName = $entityName;
            }
        }
        if ((is_null($entityName)) or ( $entityName == $alternativeClassName)) {
            $entityName = strtolower(substr(basename(\Iris\System\Functions::ClassToFile($alternativeClassName), '.php'), 1));
        }
    }

    /**
     * Forces an specific class file for the parent or children 
     * 
     * @param string $entityName
     * @return string
     * 
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
     * @param string[] $idFieldsValues
     * @param Object $object 
     */
    public function registerObject($idFieldsValues, $object) {
        if ($this->_register) {
            $id = implode('|', $idFieldsValues);
            $this->_objectRepository[$id] = $object;
        }
    }

    public function doNotRegister() {
        $this->_register = \FALSE;
    }

    /**
     * Retrieve an object from the repository (if possible, \NULL otherwise)
     * 
     * @param string[] $idFieldsValues
     * @return Object 
     */
    public function retrieveObject($idValues) {
        $id = implode('|', $idValues);
        //show_nd($idValues);
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
//$oldId = implode('|', $oldIdValues);
            $oldId = serialize($idFieldsValues);
            $this->registerObject($newIdValues, $this->_objectRepository[$oldId]);
            unset($this->_objectRepository[$oldId]);
        }
    }

    public function hasObjects() {
        return count($this->_objectRepository) > 0;
    }

    /**
     * Gives the list of the primary key fields
     * 
     * @return array
     */
    public function getIdNames() {
//return $this->getIdNames();
        if (is_null($this->_primaryKey)) {
            return [];
        }
        else {
            return $this->_primaryKey->getFields();
        }
    }

    /**
     * 
     * @return PrimaryKey
     */
    public function getPrimaryKey() {
        return $this->_primaryKey;
    }

    /**
     * Accessor set for the primary key. NEVER erases a previously set value.
     * 
     * @param PrimaryKey $primaryKey
     * @return \Iris\DB\_Entity
     */
    public function setPrimaryKey($primaryKey) {
        if (is_null($this->_primaryKey)) {
            $this->_primaryKey = $primaryKey;
        }
        return $this;
    }

    /**
     * Returns the name of the field serving for the description of a concrete
     * object
     * 
     * @return string 
     */
    public static function GetMainField() {
        return static::$_MainField;
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
     * Erases a previously set value only 
     * if the force paramaeter is set to \TRUE
     * 
     * @param string $entityName
     * @param bolean $force
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
     * @return Object[] 
     */
    public function fetchAll() {
        $sql = sprintf('SELECT %s FROM %s', $this->_query->renderSelectFields(), $this->getEntityName());
        $sql .= $this->_query->renderWhere();
        $sql .= $this->_query->renderOrder();
        $sql .= $this->_query->renderLimits($this->_entityManager->getLimitClause());
        $sql .= ';';
        $data = $this->_entityManager->fetchAll($this, $sql, $this->_query->getPlaceHolders());
        $this->_query->reset();
        $this->_register = \TRUE;
        return $data;
    }

    /**
     * 
     * @param type $fieldArray
     * @return type
     */
    public function fetchAllIndexed($fieldArray) {
        if (count($this->getIdNames()) > 1) {
            
        }
        $primaryKey = $this->_primaryKey->getFields()[0];
        $this->select($primaryKey);
        foreach ($fieldArray as $field) {
            $this->select($field);
        }
        $data = $this->fetchAll();
//        iris_debug($data);
        foreach ($data as $object) {
            if (count($fieldArray) == 1) {
                $finalData[$object->$primaryKey] = $object->$fieldArray[0];
            }
            else {
                $objectArray = [];
                foreach ($fieldArray as $field) {
                    $objectArray[] = $object->$field;
                }
                $finalData[$object->$primaryKey] = $objectArray;
            }
        }
        return $finalData;
    }

    /**
     * 
     * @param type $array
     * @return type
     * @deprecated since version number
     */
    public function fetchAll01($array = FALSE) {
        $data = $this->fetchAll();
        foreach ($data as $object) {
            $finalData[$index] = $array($object);
        }
        return $finalData;
    }

    public function fetchAllInArray() {
        $data = $this->fetchAll();
        foreach ($data as $object) {
            $finalData[] = $object->asArray();
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
        $standartIdValues = $this->_primaryKey->getNamedValues($idValues);
// try to find object in repository
        $object = $this->retrieveObject($standartIdValues);
        if (is_null($object)) {
            $this->wherePairs($standartIdValues);
            $object = $this->fetchRow();
        }
        $this->_register = \TRUE;
        return $object;
    }

    /**
     * Obtains the id of first, previous, next, last element of the table
     * 
     * @staticvar array $ids Conserves the values between calls
     * @param int $position Position of the element 
     * @return mixed
     */
    public function getId($position, $current) {
        static $ids = [NULL, \NULL, \NULL, \NULL];
        if (is_null($ids[$position])) {
            $em = $this->getEntityManager();
            $idNames = $this->getIdNames();
            if (count($idNames) > 1) {
                throw new \Iris\Exceptions\EntityException('Browsing method getId does not function with multi field primary keys.');
            }
            $idName = $idNames[0];
            $tableName = $this->getEntityName();
            switch ($position) {
                case self::FIRST:
                case self::PREVIOUS:
                    $query = $em::$LeftLimits;
                    $sql = sprintf($query, $idName, $idName, $tableName, $idName, $current);
                    $aResult = $em->directSQLQuery($sql);
                    $result = $aResult->fetchObject();
                    $ids[self::FIRST] = $result->First;
                    $ids[self::PREVIOUS] = $result->Previous;
                    break;
                case self::NEXT:
                case self::LAST:
                    $query = $em::$RightLimits;
                    $sql = sprintf($query, $idName, $idName, $tableName, $idName, $current);
                    $aResult = $em->directSQLQuery($sql);
                    $result = $aResult->fetchObject();
                    $ids[self::NEXT] = $result->Next;
                    $ids[self::LAST] = $result->Last;
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
            $this->_query->where("$field $operator", $value);
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

    public function selectDistinct($fields) {
        $this->_query->selectDistinct($fields);
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
//        $em = $this->_entityManager;
//        if(is_object($em)){
//            return $em;
//        }
//        else{
//        $this->setEntityManager($entityManager);
        return $this->_entityManager;
//        }
    }

    /**
     * For extension purpose
     * 
     * @param type $alternateEntityManager
     */
    public static function SetAlternateEntityManager($alternateEntityManager) {
        static::$_AlternateEntityManager = $alternateEntityManager;
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
        $normalizedId = $this->_primaryKey->getNamedValues($idValues);
        $this->wherePairs($normalizedId);
        $whereClause = $this->_query->renderWhere();
        $sql = sprintf("UPDATE %s SET %s %s", $this->getEntityName(), $setClause, $whereClause);
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
        $sql = sprintf("INSERT INTO %s(%s) VALUES(%s);", $this->getEntityName(), $insertClause, $valueClause);
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
        $sql = "DELETE FROM " . $this->getEntityName();
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
            $ids = $this->getMetadata()->getPrimary()->getFields();
            if (count($ids) > 1) {
                return \NULL;
            }
            $select = sprintf('SELECT MAX(%s) as NUM FROM %s;', $ids[0], $this->getEntityName());

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
            if (count($this->getIdNames()) == 0) {
                $this->_primaryKey = $this->_metadata->getPrimary();
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
     * 
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
//        if (is_null($entityManager)) {
//
//            $defaultEM = _EntityManager::GetInstance();
//        }
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
                $this->_reflectionEntity = $this->getEntityName();
            }
            /* @var $metadata Metadata */
            $metadata = $this->_entityManager->readFields($this->_reflectionEntity);
            foreach ($this->_entityManager->getForeignKeys($this->_reflectionEntity) as $foreignKey) {
                $metadata->addForeign($foreignKey);
            }
            foreach ($this->getIdNames() as $field) {
                $metadata->addPrimary($field);
            }
// optionaly reads manually added foreign keys
            foreach ($this->_foreignKeyDescriptions as $key => $description) {
                if (is_string($description)) {
                    $foreignKey = new ForeignKey($description);
                }
                else {
                    $foreignKey = new ForeignKey();
                    $foreignKey->setNumber($key);
                    $foreignKey->setTargetTable($description[0]);
                    $foreignKey->addKeys($description[1], $description[2]);
                }
                $metadata->addForeign($foreignKey);
            }

            $desc = static::GetMainField();
            if (!empty($desc)) {
//                //show_nd($this->_entityName);
//                //show_nd(get_called_class());
//                //show_nd(static::GetMainField());
//                //show_nd('=========================');
                $metadata->addDescription($desc);
            }
            //if necessary adds bridges
//            $class = get_called_class();
//            foreach(static::$_Bridges as $bridge){
//                $metadata->addBridge($bridge);
//            }
        }

        return $metadata;
    }

    /**
     * Verifies an entity has a complete definition: <ul>
     * <li> an entity name
     * <li> a primary key
     * <li> a metadata object with all other specification</ul>
     * 
     * @throws \Iris\Exceptions\EntityException
     */
    public function validate() {
        if (is_null($this->getEntityName())) {
            throw new \Iris\Exceptions\EntityException('The entity has no associated object in the database.');
        }
        if (is_null($this->_metadata) or count($this->_metadata->getFields()) == 0) {
            throw new \Iris\Exceptions\EntityException('No metadata has been provided or found for this entity.');
        }
        if (count($this->getIdNames()) == 0) {
            throw new \Iris\Exceptions\EntityException('This entity does not have a specified primary key.');
        }
    }

    /**
     * 
     * @param type $limit
     * @param type $offset
     * @return \Iris\DB\_Entity
     */
    public function limit($limit, $offset = 0) {
        $indexName = "DB_OFFSET_" . $this->getEntityName();
// Recuperate previous offset from Session
        if ($offset == self::CONT) {
            $offset = \Iris\Engine\Superglobal::GetSession($indexName, 0);
        }
        $this->_query->limit($limit, $offset);
        $_SESSION[$indexName] = $offset + $limit;
        return $this;
    }

    public function GetParentList($key) {
        return $this->_parents;
    }

    public function SetParentList($values) {
        $this->_parents = $values;
    }

}
