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
    const ORM_TRANSIENT = 1;
    const ORM_PERSISTENT = 2;
    const ORM_DELETED = 3;
    const ORM_UNLINKED = 4;

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
     * @var array 
     */
    protected $_fields = array();

    /**
     * 
     * @var array 
     */
    protected $_data = array();
    protected $_newData = array();
    protected $_dirty = FALSE;
    protected $_ORMState;

    /**
     *
     * @var 
     */
    private $_parents = array();
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
     * @param array $idValues An array with the primary key field values
     * @param array $data The initials values of the object (in an array)
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
                    $this->_data[] = NULL;
                    if (count($metadata) != 0) {
                        $item = $metadataFields[$fieldName];
                        $value = $item->initialize($value);
                    }
                    $this->_newData[] = $value;
                    $this->_dirty = TRUE;
                }
                else {
                    $this->_data[] = $value;
                    $this->_newData[] = NULL;
                    $entity->getEntityManager()->registerObject($entity->getEntityName(), $idValues, $this);
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
            $array[$field] = $this->$field;
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
            if ($this->_dirty and isset($this->_newData[$offset])) {
                return $this->_newData[$offset];
            }
            else {
                return $this->_data[$offset];
            }
        }
        // may be an object or an array of objects (or NULL)
        return $this->_getPseudoField($field);
    }

    /**
     * Two pseudo fields are defined <ul>
     * <li> _at_parent_id : returns an object corresponding to the parent
     * <li> _children_entity_id : returns an array corresponding to the children
     * </ul>
     * 
     * @param string $field The pseudo field name
     * @return mixed
     */
    private function _getPseudoField($field) {
        $offset = strpos($field, self::CHILDREN);
        if ($offset === 0) {
            $type = self::DATAROWSET;
        }
        else {
            $offset = strpos($field, self::AT);
            if ($offset === 0) {
                $type = self::DATAROW;
            }
            else {
                // Unknown field
                return \NULL;
            }
        }
        $EM = $this->_entity->getEntityManager();
        $links = substr($field, $type);
        // get parent
        if ($type == self::DATAROW) {
            list($parentEntityName, $fromKeyNames) = $this->_entity->getMetadata()->getParentRowParams($links);
            
            if (!isset($this->_parents[$parentEntityName])) {
                
                $parentEntity = $EM::GetEntity($parentEntityName, $EM);
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
        // get children
        else {
            $fKeys = explode('__', $links);
            $chldName = array_shift($fKeys);
            $chldEntity = $EM->getEntity($chldName, $EM);
            list($parentFields, $childFields) =
                    $chldEntity->getMetadata()->getChildrenParams($this->_entity->getEntityName(), $fKeys);
            // placer ici les valeurs et non les noms de champ
            $i = 0;
            foreach ($parentFields as $pField) {
                $value = $this->$pField;
                $conditions[$childFields[$i++]] = $value;
                $childValues[] = $value;
            }
            $chldId = implode('__', $childValues) . "@$chldName";
            if (!isset($this->_children[$chldId])) {
                $chldEntity->wherePairs($conditions);
                $children = $chldEntity->fetchall();
                $this->_children[$chldId] = $children;
            }
            return $this->_children[$chldId];
        }
    }

    /**
     * Analyses and uses the pseudo field _children_parent__foreignKey
     * @param type $pseudoField
     * @return type 
     */
    protected function _getChildren($pseudoField) {
        $fKey = substr($pseudoField, strlen(self::CHILDREN));
        $fKeys = explode('__', $fKey);
        $chldName = array_shift($fKeys);
        $parentName = $this->_entity->getEntityName();
        $foreignKey = implode('_', $fKeys);
        $chldID = $chldName . '_' . $foreignKey;
        if (isset($this->_children[$chldID])) {
            return $this->_children[$chldID];
        }
        $EM = $this->_entity->getEntityManager();
        $chldEntity = $EM->getEntity($chldName, $EM);
        $chldMetadata = $chldEntity->getMetadata();
        //iris_debug($chldMetadata);
        $chldForeigns = $chldMetadata->getForeigns();
        /*         * @var \Iris\DB\ForeignKey chldForeign */
        foreach ($chldForeigns as $chldForeign) {
            if ($chldForeign->getTargetTable() == $parentName and
                    implode('_', $chldForeign->getFromKeys()) == $foreignKey) {
                break;
            }
        }
        $chldToParent = $chldForeign->getFromKeys();
        $condition = array_combine($chldToParent, $this->primaryKeyValue());
        $chldEntity->wherePairs($condition);
        $result = $chldEntity->fetchall();
        $this->_children[$chldID] = $result;
        return $result;
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
//        if(is_null($value)){
//            $value = 'null';
//        }
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        $offset = $this->_fields[$field];
        if ($this->_data[$offset] != $value) {
            $this->_newData[$offset] = $value;
            $this->_dirty = TRUE;
        }
    }

    public function extraField($fieldName, $value) {
        $nextNumber = count($this->_data);
        $this->_data[$nextNumber] = $value;
        $this->_newData[$nextNumber] = NULL;
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
            $newId = $this->_entity->lastInsertedId();
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
     * Create a new object in the database
     */
    protected function _insert() {
        $insert = array();
        $values = array();
        foreach ($this->_fields as $field => $dummy) {
            $offset = $this->_fields[$field];
            if (!is_null($this->_newData[$offset])) {
                $insert[] = $field;
                $values[] = $this->_newData[$offset];
                $this->_data[$offset] = $this->_newData[$offset];
                $this->_newData[$offset] = NULL;
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
                    $this->_data[$this->_fields[$pkName]] = $newId;
                }
            }
            $idValues = $this->primaryKeyValue();
            $this->_entity->getEntityManager()->registerObject($this->_entity->getEntityName(), $idValues, $this);
        }
        return $done;
    }

    protected function _update() {
        $setFields = array();
        $values = array();
        foreach ($this->_fields as $field => $dummy) {
            $offset = $this->_fields[$field];
            if (!is_null($this->_newData[$offset]) and
                    $this->_data[$offset] != $this->_newData[$offset]) {
                $setFields[] = $field;
                $values[] = $this->_newData[$offset];
            }
        }
        $idValues = $this->primaryKeyValue();
        return $this->_entity->update($setFields, $values, $idValues); // need to update
    }

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
                $idValues[$idN] = $this->_data[$this->_fields[$idN]];
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

?>
