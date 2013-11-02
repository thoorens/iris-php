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


    /**
     * The pseudo field parent marker.
     * example : _at_customer_id  
     */
    const AT = '_at_';
    /**
     * The pseudo field children marker
     * example : _children_invoices__id 
     */
    const CHILDREN = '_children_';

    /**
     * The field names (in keys) and offsets (in values)
     * @var int[]
     */
    protected $_fields = array();

    /**
     * The current content of the fields 
     * @var mixed[] 
     */
    protected $_currentContent = array();

    /**
     * The modified content of the field
     * 
     * @var mixed[]
     */
    protected $_modifiedContent = array();

    /**
     * If TRUE, indicates that the object has been modified
     * 
     * @var boolean
     */
    protected $_dirty = FALSE;

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
    private $_parents = array();

    /**
     * An object may have various series of children : each one has the table
     * name + index(es) as index and an array of object as value.
     * 
     * @var Object[]
     */
    protected $_children = array();

    /**
     * Entity corresponding to the object 
     * 
     * @var _Entity 
     */
    protected $_entity;

    /**
     * Constructor for an object
     * 
     * @param \Iris\DB\_Entity $entity The entity corresponding to the object
     * @param string[] $idValues An array with the primary key field values
     * @param mixed[] $data The initials values of the object (in an array)
     * @param boolean $new If true the object is new (by default not)
     */
    public function __construct(_Entity $entity, $idValues, $data, $new = FALSE) {
        $this->_entity = $entity;
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
                    $this->_currentContent[] = NULL;
                    if (count($metadata) != 0) {
                        $item = $metadataFields[$fieldName];
                        $value = $item->initialize($value);
                    }
                    $this->_modifiedContent[] = $value;
                    $this->_dirty = TRUE;
                }
                else {
                    $this->_currentContent[] = $value;
//$this->_newData[] = NULL;
                    $entity->registerObject($idValues, $this);
                }
            }
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
                return $this->_modifiedContent[$offset];
            }
            else {
                return $this->_currentContent[$offset];
            }
        }
        // try to use a pseudo field
        if (strpos($field, IRIS_PARENT) === 0) {
            return $this->_getParent(substr($field, strlen(IRIS_PARENT)));
        }
        if (strpos($field, IRIS_CHILDREN) === 0) {
            return $this->_getChildren(substr($field, strlen(IRIS_CHILDREN)));
        }
        return \NULL;
    }
    
    /**
     * Gets the parent accessible from a foreign key in the object
     * 
     * @param string $keyFields
     * @return Object
     */
    private function _getParent($keyFields) {
        $entityManager = $this->_entity->getEntityManager();
        list($parentEntityName, $fromKeyNames) = $this->_entity->getMetadata()->getParentRowParams($keyFields);
        if (!isset($this->_parents[$parentEntityName])) {
            $parentEntity = \Iris\DB\TableEntity::GetEntity($entityManager, $parentEntityName);
            $i = 0;
            $primaryKey = $parentEntity->getIdNames();
            foreach ($fromKeyNames as $keyName) {
                $primaryKeys[$primaryKey[$i++]] = $this->$keyName;
            }
            $parent = $parentEntity->find($primaryKeys);
            $this->_parents[$parentEntityName] = $parent;
        }
        return $this->_parents[$parentEntityName];
    }

    /**
     * Gets the children whose object is the parent
     * 
     * @param string $keyFields
     * @return array
     */
    private function _getChildren($keyFields) {
        $entityManager = $this->_entity->getEntityManager();
        $fKeys = explode(IRIS_FIELDSEP, $keyFields);
        $chldName = array_shift($fKeys);
//        $chldClassName = $this->_entity->getExternalClassName($chldName);
//        $chldEntity = $entityManager->retrieveEntity($chldName, $chldClassName);
        $chldEntity = \Iris\DB\TableEntity::GetEntity($entityManager, $chldName);
        list($parentFields, $childFields) =
                $chldEntity->getMetadata()->getChildrenParams($this->_entity->getEntityName(), $fKeys);
// placer ici les valeurs et non les noms de champ
        $i = 0;
        foreach ($parentFields as $pField) {
            $value = $this->$pField;
            $conditions[$childFields[$i++]] = $value;
            $childValues[] = $value;
        }
        $chldId = implode(IRIS_FIELDSEP, $childValues) . "@$chldName";
        if (!isset($this->_children[$chldId])) {
            $chldEntity->wherePairs($conditions);
            $children = $chldEntity->fetchAll();
            $this->_children[$chldId] = $children;
        }
        return $this->_children[$chldId];
    }

    /**
     * Analyses and uses the pseudo field _children_parent__foreignKey
     * @param type $pseudoField
     * @return type 
     */
//    protected function _getChildren($pseudoField) {
//        $fKey = substr($pseudoField, strlen(self::CHILDREN));
//        $fKeys = explode(IRIS_FIELDSEP, $fKey);
//        $chldName = array_shift($fKeys);
//        $parentName = $this->_entity->getEntityName();
//        $foreignKey = implode('_', $fKeys);
//        $chldID = $chldName . '_' . $foreignKey;
//        if (isset($this->_children[$chldID])) {
//            return $this->_children[$chldID];
//        }
//        $entityManager = $this->_entity->getEntityManager();
//        $chldClassName = $this->_entity->getExternalClassName($chldName);
//        $chldEntity = $entityManager->retrievetEntity($chldName, $chldClassName);
//        $chldMetadata = $chldEntity->getMetadata();
//        $chldForeigns = $chldMetadata->getForeigns();
//        /*         * @var \Iris\DB\ForeignKey chldForeign */
//        foreach ($chldForeigns as $chldForeign) {
//            if ($chldForeign->getTargetTable() == $parentName and
//                    implode('_', $chldForeign->getFromKeys()) == $foreignKey) {
//                break;
//            }
//        }
//        $chldToParent = $chldForeign->getFromKeys();
//        $condition = array_combine($chldToParent, $this->primaryKeyValue());
//        $chldEntity->wherePairs($condition);
//        $result = $chldEntity->fetchAll();
//        $this->_children[$chldID] = $result;
//        return $result;
//    }

    /**
     * Change a field value if necessary, marking the object
     * for save
     * 
     * @param string $field
     * @param mixed $value 
     * @todo Verify if some RDBMS wants to manage true boolean
     */
    public function __set($field, $value) {
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        $offset = $this->_fields[$field];
        if ($this->_currentContent[$offset] != $value) {
            $this->_modifiedContent[$offset] = $value;
            $this->_dirty = TRUE;
        }
    }

    public function extraField($fieldName, $value) {
        $nextNumber = count($this->_currentContent);
        $this->_currentContent[$nextNumber] = $value;
        $this->_modifiedContent[$nextNumber] = NULL;
        $this->_fields[$fieldName] = $nextNumber;
    }

    /**
     * Save an object in the database, if necessary
     */
    public function save() {
// Deleted object can't be saved
        if ($this->_ORMState == self::ORM_DELETED) {
            throw new \Iris\Exceptions\ORMException('Trying to save a deleted object');
        }

        if ($this->_ORMState == self::ORM_TRANSIENT) {
            $done = $this->_insert();
            $this->_entity->lastInsertedId();
//@todo manage AUTOINCREMENT instead
//            if(is_numeric($newId) and count($this->_entity->getIdNames())==1){
//                $idFields =$this->_entity->getIdNames(); 
//                $idField = $idFields[0];
//                // writing directly in data NOT in newData
//                $this->_data[$this->_fields[$idField]] = $newId;
//            }
        }
        elseif ($this->_dirty) {
            $done = $this->_update();
        }
        else {
            $done = TRUE;
        }
        if ($done) {
            $this->_dirty = FALSE;
//@todo : mettre à jour les objets dépendants.....
        }
        return $done;
    }

    /**
     * Creates a new object in the database
     * 
     * @return boolean If TRUE, insertion has been successfully done
     */
    protected function _insert() {
        $insert = array();
        $values = array();
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
            $primary = $metadata->getPrimary();
            if (count($primary) == 1) {
                $pkName = $primary[0];
                $primaryKeyField = $metadata->$pkName;
                if ($primaryKeyField->isAutoincrement()) {
                    $EM = $this->_entity->getEntityManager();
                    $newId = $EM->lastInsertedId(NULL);
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
     * @return boolean If TRUE, update has been successfully done
     */
    protected function _update() {
        $setFields = array();
        $values = array();
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
     * @return booelan If TRUE, deletion has been successfully done
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
        $idValues = array();
        foreach ($this->_entity->getMetadata()->getPrimary() as $idN) {
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

}

