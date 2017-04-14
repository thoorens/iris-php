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
 *  An object is a memory representation of a line of a table. It
 * has 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Object {

    /**
     * The object exists in memory, but has no relation to the database
     */
    const ORM_TRANSIENT = 1;

    /**
     * The object has been inited from data in the database
     */
    const ORM_PERSISTENT = 2;

    /**
     * The object taken from the database, has been deleted
     */
    const ORM_DELETED = 3;
// The value are linked to the prefixe lengths
    const DATAROW = 4;
    const DATAROWSET = 10;
    const NULL_VALUE = '##NULL##';

    /**
     * IRIS_PARENT, IRIS_CHILDREN, _BRIDGE_ and IRIS_FILESEP are used to detect pseudo fields. 
     * In case they conflict with an existing field, it is possible to use the method they call
     */

    /**
     *
     * @var string 
     */
    const PARENT_PREFIX = '_at_';

    /**
     *
     * @var string 
     */
    const CHILDREN_PREFIX = '_children_';

    /**
     *
     * @var string 
     */
    const BRIDGE_PREFIX = '_bridge_';

    /**
     * The field names (in keys) and offsets (in values)
     * @var int[]
     */
    protected $_fields = [];

    /**
     * The current content of the fields 
     * @var mixed[] 
     */
    protected $_currentContent = [];

    /**
     * The modified content of the field
     * 
     * @var mixed[]
     */
    protected $_modifiedContent = [];

    /**
     * If \TRUE, indicates that the object has been modified
     * 
     * @var boolean
     */
    protected $_dirty = \FALSE;

    /**
     * The state of the object relative to the database
     * 
     * @var int
     */
    protected $_ORMState;

    /**
     * An object may have various parents : each one has the foreign field(s)
     * as an index
     * 
     * @var Object[]
     */
    private $_parents = [];

    /**
     * An object may have various series of children : each one has the table
     * name + index(es) as index and an array of object as value.
     * 
     * @var Object[]
     */
    protected $_children = [];

    /**
     * Entity corresponding to the object 
     * 
     * @var _Entity 
     */
    protected $_entity;

    /**
     *
     * @var boolean
     */
    protected $_readOnly;

    /**
     * Constructor for an object
     * 
     * @param \Iris\DB\_Entity $entity The entity corresponding to the object
     * @param string[] $idValues An array with the primary key field values
     * @param mixed[] $data The initials values of the object (in an array)
     * @param boolean $new If true the object is new (by default not)
     */
    public function __construct(_Entity $entity, $idValues, $data, $new = \FALSE) {
        $this->_entity = $entity;
        $this->_readOnly = $entity->isReadOnly();
        if ($new) {
            $this->_ORMState = self::ORM_TRANSIENT;
        }
        else {
            $this->_ORMState = self::ORM_PERSISTENT;
        }
        $metadata = $this->_entity->getMetadata();
        $metadataFields = $metadata->getFields();
        foreach ($data as $fieldName => $value) {
            if (count($metadata) == 0 OR isset($metadata->$fieldName)) {
                $fields[] = $fieldName;
                if ($new) {
                    $this->_currentContent[] = \NULL;
                    if (count($metadata) != 0) {
                        $item = $metadataFields[$fieldName];
                        $value = $item->initialize($value);
                    }
                    $this->_modifiedContent[] = is_null($value) ? self::NULL_VALUE : $value;
                    $this->_dirty = \TRUE;
                }
                else {
                    $this->_currentContent[] = $value;
                }
            }
        }
        if (!$new) {
            $primaryKey = $metadata->getPrimary();
            $orderedKey = $primaryKey->getNamedValues($idValues);
            $entity->registerObject($orderedKey, $this);
        }
        $this->_fields = array_flip($fields);
    }

    public function replaceData($data) {
        $metadata = $this->_entity->getMetadata();
        foreach ($data as $fieldName => $value) {
            if (count($metadata) == 0 OR isset($metadata->$fieldName)) {
                $this->$fieldName = $value;
            }
        }
    }

    /**
     * Transforms 
     * @return array 
     */
    public function asArray() {
        foreach ($this->_fields as $field => $dummy) {
            $array[$field] = $this->__get($field);
        }
        return $array;
    }

    /**
     * Magic method to get the value of a field or a pseudo field (_at_xxxx
     * or _children_xxxx_yyyy).
     * 
     * @param string $field the field name
     * @return mixed
     */
    public function __get($field) {
        // treat an normal field
        if (isset($this->_fields[$field])) {
            $offset = $this->_fields[$field];
            if ($this->_dirty and isset($this->_modifiedContent[$offset])) {
                $value = $this->_modifiedContent[$offset];
            }
            else {
                $value = $this->_currentContent[$offset];
            }
        }
        else {
            // try to use a pseudo field
            $value = \NULL;
            if (strpos($field, self::PARENT_PREFIX) === 0) {
                $value = $this->getParent(substr($field, strlen(self::PARENT_PREFIX)));
            }
            elseif (strpos($field, self::CHILDREN_PREFIX) === 0) {
                $arguments = substr($field, strlen(self::CHILDREN_PREFIX));
                $value = $this->getChildren(explode(Metadata::FIELD_SEP, $arguments));
            }
            elseif (strpos($field, self::BRIDGE_PREFIX) === 0) {
                $value = $this->fromBridge(substr($field, strlen(self::BRIDGE_PREFIX)));
            }
            else {
                $value = \NULL;
                //throw new \Iris\Exceptions\BadParameterException("Field or pseudo field unknow : " .$field);
            }
        }
        return $value;
    }

    /**
     * 
     * @param string $bridgeName name of the bridge entity
     */
    public function fromBridge($bridgeName) {
        $bridgeClass = \Iris\System\Functions::TableToEntity($bridgeName);
        $entity = $this->getEntity();
        /* @var $bridge _Entity */
        $bridge = $bridgeClass::GetEntity($entity->getEntityManager());
        return $bridge->getMetadata()->manageBridge($entity, $this, $bridge->getEntityName());
    }

    /**
     * Gets the parent accessible from a foreign key in the object
     * 
     * @param string $keyFields
     * @return Object
     */
    public function getParent($keyFields) {
        $entity = $this->getEntity();
        $parents = $entity->GetParentList($keyFields);
        $localId = implode('-',$this->primaryKeyValue());
        if(!isset($parents[$keyFields][$localId])){
            $entityManager = $this->_entity->getEntityManager();
            list($parentEntityName, $foreignKeyPairs) = $this->_entity->getMetadata()->getParentRowParams($keyFields);
            $parentEntity = \Iris\DB\TableEntity::GetEntity($entityManager, $parentEntityName);
            foreach ($foreignKeyPairs as $foreignName => $primaryName) {
                $primaryKeys[$primaryName] = $this->$foreignName;
            }
            $parent = $parentEntity->find($primaryKeys);
            $parents[$keyFields][$localId] = $parent;
            $entity->SetParentList($parents);
        }
        return $parents[$keyFields][$localId];
    }

    /**
     * Gets the children whose object is the parent
     * 
     * @param string $keyFields
     * @return array
     */
    public function getChildren($params) {
        $chldName = array_shift($params);
        $entityManager = $this->_entity->getEntityManager();
        $fKeys = $params;
        $chldEntity = \Iris\DB\TableEntity::GetEntity($entityManager, $chldName);
        list($parentFields, $childFields) = $chldEntity->getMetadata()->getChildrenParams($this->_entity->getEntityName(), $fKeys);
        $i = 0;
        foreach ($parentFields as $pField) {
            $value = $this->$pField;
            $conditions[$childFields[$i++]] = $value;
            $childValues[] = $value;
        }
        $chldId = implode(Metadata::FIELD_SEP, $childValues) . "@$chldName";
        if (!isset($this->_children[$chldId])) {
            $chldEntity->wherePairs($conditions);
            $children = $chldEntity->fetchAll();
            $this->_children[$chldId] = $children;
        }
        return $this->_children[$chldId];
    }

    /**
     * Change a field value if necessary, marking the object
     * for save
     * 
     * @param string $field
     * @param mixed $value 
     * @todo Verify if some RDBMS wants to manage true boolean
     */
    public function __set($field, $value) {
        if ($this->_readOnly) {
            throw new \Iris\Exceptions\DBException('A read only object cannot be modified');
        }
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        if ($value === \NULL) {
            $value = self::NULL_VALUE;
        }
        $offset = $this->_fields[$field];
        if ($this->_currentContent[$offset] !== $value) {
            $this->_modifiedContent[$offset] = $value;
            $this->_dirty = \TRUE;
        }
    }

    public function isEmpty($field) {
        $var = $this->__get($field);
        return empty($var);
    }

    /**
     * Creates a new field in the object (as if it would have been read
     * from Database and inits it
     * 
     * @param string $fieldName
     * @param mixed $value
     */
    public function extraField($fieldName, $value) {
        $nextNumber = count($this->_currentContent);
        $this->_currentContent[$nextNumber] = $value;
        $this->_modifiedContent[$nextNumber] = \NULL;
        $this->_fields[$fieldName] = $nextNumber;
    }

    /**
     * Save an object in the database, if necessary
     */
    public function save() {
// Deleted object can't be saved
        if ($this->_ORMState == self::ORM_DELETED) {
            throw new \Iris\Exceptions\DBException('Trying to save a deleted object');
        }
        if ($this->_ORMState == self::ORM_TRANSIENT) {
            $done = $this->_insert();
        }
        elseif ($this->_dirty) {
            $done = $this->_update();
        }
        else {
            $done = \TRUE;
        }

        if ($done) {
            $this->_dirty = \FALSE;
//@todo : mettre à jour les objets dépendants.....
        }
        foreach ($this->_parents as $parent) {
            $parent->save();
        }
        //iris_debug($this->_children);
        foreach ($this->_children as $children) {
//            foreach ($children as $child) {
//                $child->save();
//            }
        }
        return $done;
    }

    /**
     * Creates a new object in the database
     * 
     * @return boolean If \TRUE, insertion has been successfully done
     */
    protected function _insert() {
        $insert = [];
        $values = [];
        foreach ($this->_fields as $field => $dummy) {
            $offset = $this->_fields[$field];
            if (isset($this->_modifiedContent[$offset])) {
                $insert[] = $field;
                $values[] = $this->_modifiedContent[$offset];
                $this->_currentContent[$offset] = $this->_modifiedContent[$offset];
                unset($this->_modifiedContent[$offset]);
            }
        }
        $done = $this->_entity->insert($insert, $values);
        if ($done) {
            $this->_ORMState = self::ORM_PERSISTENT;
            $metadata = $this->_entity->getMetadata();
            $primary = $metadata->getPrimary()->getFields();
            if (count($primary) == 1) {
                $pkName = $primary[0];
                $primaryKeyField = $metadata->$pkName;
                if ($primaryKeyField->isAutoincrement()) {
                    $EM = $this->_entity->getEntityManager();
                    $newId = $EM->lastInsertedId(\NULL);
                    $this->_currentContent[$this->_fields[$pkName]] = $newId;
                }
            }
            $idValues = $this->primaryKeyValue();
            $this->_entity->registerObject($idValues, $this);
        }
        return $done;
    }

    /**
     * Updates an object in the database
     * 
     * @return boolean If \TRUE, update has been successfully done
     */
    protected function _update() {
        $setFields = [];
        $values = [];
        // id must be the old value
        $idValues = $this->primaryKeyValue();
        foreach ($this->_fields as $field => $dummy) {
            $offset = $this->_fields[$field];
            if (isset($this->_modifiedContent[$offset])) {
                $setFields[] = $field;
                $values[] = $this->_modifiedContent[$offset];
                $this->_currentContent[$offset] = $this->_modifiedContent[$offset];
                unset($this->_modifiedContent[$offset]);
            }
        }
        $newIdValues = $this->primaryKeyValue();
        $this->_entity->updateRegistry($idValues, $newIdValues);
        return $this->_entity->update($setFields, $values, $idValues); // need to update
    }

    /**
     * Deletes an object out of the database
     * 
     * @return booelan If \TRUE, deletion has been successfully done
     * @throws \Iris\Exceptions\DBException
     */
    public function delete() {
        if ($this->_ORMState != self::ORM_PERSISTENT) {
            throw new \Iris\Exceptions\DBException('A not persistent object cannot be deleted');
        }
        $done = $this->_entity->delete($this->primaryKeyValue());
        if ($done) {
            $this->_ORMState = self::ORM_DELETED;
        }
        return $done;
    }

    /**
     * Returns an associative array with the primary key values
     *  
     * @return array 
     */
    public function primaryKeyValue() {
        $idValues = [];
        foreach ($this->_entity->getMetadata()->getPrimary()->getFields() as $idN) {
            if (isset($this->_fields[$idN])) {
                $idValues[$idN] = $this->_currentContent[$this->_fields[$idN]];
            }
        }
        return $idValues;
    }

    /**
     * Returns the entity corresponding to the object
     * 
     * @return _Entity
     */
    public function getEntity() {
        return $this->_entity;
    }

    /**
     * Says if the object is readonly
     * @return boolean
     */
    public function isReadOnly() {
        return $this->_readOnly;
    }

}
