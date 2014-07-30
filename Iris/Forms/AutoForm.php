<?php

namespace Iris\Forms;

use Iris\DB as db;

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
 * A self generated form form rapid development. It can serve as
 * a basis for a customized form.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class AutoForm extends Elements\Form {

    /**
     * Incremental form number id
     * @var int
     */
    private static $_Number = 1;
    protected $_labels;
    protected $_formFactory;
    private $_entity;
    private static $_PlausibleDescriptions = array(
        'Description',
        'Name',
        'UserName',
        'Identity',
    );

    /**
     *
     * @var ElementSpecs[]
     */
    private $_elementTypes;

    public function __construct(db\_Entity $entity, $labels = []) {
        $this->_entity = $entity;
        $this->_name = sprintf("iris_autoform_%d", self::$_Number++);
        $this->_labels = $labels;
    }

    /**
     * Creates the field controller and makes the form ready to be rended.
     * 
     * @return \Iris\Forms\AutoForm
     */
    public function prepare() {
        $this->_method = 'post';
        $metadata = $this->_entity->getMetadata();
        $this->_formFactory = _FormFactory::GetDefaultFormFactory();
        $fields = $metadata->getFields();
        //iris_debug($metadata->getFields());
        foreach ($fields as $metaItem) {
            /* @var $metaItem \Iris\DB\MetaItem */
            if (is_null($metaItem->getForeignPointer())) {
                $this->_makeElement($metaItem);
            }
        }
        foreach ($metadata->getForeigns() as $foreign) {
            /* @var $foreign \Iris\DB\ForeignKey */
            $keys = $foreign->getFromKeys();
            // Multikey are not possible
            if (count($keys) > 1) {
                foreach ($keys as $key) {
                    $this->_makeElement($fields[$key]);
                }
            }
            else {
                $this->_makeSelect($fields[$keys[0]], $foreign);
            }
        }
        $element = $this->_formFactory->createSubmit('Submit');
        $element->setValue('Send');
        $element->addTo($this);
        return $this;
    }

    /**
     *
     * @param db\MetaItem $metaItem
     * @param string[] $labels
     */
    private function _makeElement(db\MetaItem $metaItem) {
        if (isset($this->_elementTypes[$metaItem->getFieldName()])) {
            $element = $this->_elementTypes[$metaItem->getFieldName()]->create($this->_formFactory, $metaItem);
            if (is_null($element)) {
                return;
            }
        }
        else {
            switch ($metaItem->getType()) {
                case 'text':
                default :
                    $element = $this->_formFactory->createText($metaItem->getFieldName());
                    $element->setLabel($this->_getLabelText($metaItem));
                    break;
            }
        }
        if ($metaItem->isAutoIncrement()) {
            $element->setDisabled('disabled');
        }
        $element->addTo($this);
    }

    private function _makeSelect(db\MetaItem $metaItem, \Iris\DB\ForeignKey $foreign) {
        if (isset($this->_elementTypes[$metaItem->getFieldName()]) and $this->_elementTypes[$metaItem->getFieldName()]->mustHide()) {
            return;
        }
        /* @var $element \Iris\Forms\Elements\SelectElement */
        $element = $this->_formFactory->createSelect($metaItem->getFieldName());
        $element->setLabel($this->_getLabelText($metaItem));
        $element->addTo($this);
        $entityManager = $this->_entity->getEntityManager();
        $tableName = $foreign->getTargetTable();
        /* @var $targetEntity \Iris\DB\_Entity */
        $targetEntity = \Iris\DB\TableEntity::GetEntity($tableName, $entityManager);
        $idNames = $foreign->getToKeys();
        $idName = $idNames[0];
        $descField = $this->getDescriptionField($targetEntity);
        $targetEntity->select($idName);
        $targetEntity->select($descField);
        $targetEntity->doNotRegister();
        $targetEntity->order(1);
        $data = $targetEntity->fetchAll();

        foreach ($data as $ligne) {
            $data2[$ligne->$idName] = $ligne->$descField;
        }
        $element->addOptions($data2);
    }

    /**
     * Gets the label corresponding to a field. It can be one of three:<ul>
     * <li> a label explicitly defined during instanciation
     * <li> a label added in entity class
     * <li> the fied name (by default) </ul>
     * 
     * @param \Iris\DB\MetaItem $metaItem
     * @return string
     */
    protected function _getLabelText(\Iris\DB\MetaItem $metaItem) {
        $name = $metaItem->getFieldName();
        if (isset($this->_labels[$name])) {
            return $this->_labels[$name];
        }
        else {
            return $metaItem->get('LABEL', $name);
        }
    }

    /**
     * Add 
     * @param mixed $items (one description field name or an array of names)
     */
    public static function AddPlausibleDescriptions($items) {
        if (is_array($items)) {
            foreach ($items as $item) {
                self::AddPlausibleDescriptions($item);
            }
        }
        else {
            self::$_PlausibleDescriptions[] = $items;
        }
    }

    /**
     * Returns the name of the field serving for the description of a concrete
     * object
     * 
     * @param \Iris\DB\_Entity $targetTable
     * @return string
     */
    public function getDescriptionField($targetTable) {
        /**
         * Problem: the description fied is only available in an explicite entity
         */
        if ($targetTable->getDescriptionField() != '') {
            return $targetTable->getDescriptionField();
        }
        /* @var $field  \Iris\DB\MetaItem */
        foreach ($targetTable->getMetadata()->getFields() as $field) {
            $fieldName = $field->getFieldName();
            if (array_search($fieldName, self::$_PlausibleDescriptions) !== \FALSE) {
                return $fieldName;
            }
        }
        // by default return the first part of the primary key
        $ids = $targetTable->getIdNames();
        return $ids[0];
    }

    /**
     * 
     * @param ElementSpecs $elementSpecs
     */
    public function addSpecs($elementSpecs, $params = \NULL) {
        if (!$elementSpecs instanceof ElementSpecs) {
            $elementSpecs = new ElementSpecs($elementSpecs, $params);
        }
        $index = $elementSpecs->getName();
        $this->_elementTypes[$index] = $elementSpecs;
    }

    /**
     * 
     * @param type $from
     * @param type $name
     * @param type $callable
     */
    public function cloneSpecs($from, $name, $callable = \NULL) {
        $original = $this->_elementTypes[$from];
        $copy = clone $original;
        $copy->setName($name);
        if (!is_null($callable)) {
            $callable($copy);
        }
        $this->addSpecs($copy);
    }

}
