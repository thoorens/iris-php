<?php

namespace Iris\Forms;

use Iris\DB as db;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A self generated form form rapid development. It can serve as
 * a basis for a customized form.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class AutoForm2 {

    /**
     * Incremental form number id
     * @var int
     */
    private static $_Number = 1;
    protected $_labels;

    /**
     *
     * @var _FormFactory
     */
    protected $_formFactory;
    /**
     *
     * @var _Form
     */
    protected $_form;
    private $_entity;
    private $_done = [];
    private static $_PlausibleDescriptions = [
        'Description',
        'Name',
        'UserName',
        'Identity',
    ];

    /**
     *
     * @var ElementSpecs[]
     */
    private $_elementTypes = [];

    /**
     * 
     * @param \Iris\DB\_Entity $entity the entity responsible of the form
     * @param type $labels an optional list of labels for the form
     */
    public function __construct(db\_Entity $entity, $labels = []) {
        $this->_formFactory = \Dojo\Forms\FormFactory::GetDefaultFormFactory();
        $this->_form = $this->_formFactory->createForm(sprintf("iris_autoform_%d", self::$_Number++));
        $entityName = $entity->getEntityName();
        $paraForm = \Iris\Engine\Memory::Get('param_forms', []);
        if (isset($paraForm[$entityName])) {
            $params = $paraForm[$entityName];
            foreach ($params as $name => $field) {
                $elementSpecs = new ElementSpecs($name);
                $elementSpecs->setSpecs($field);
                $this->_elementTypes[$name] = $elementSpecs;
                if ($name == '_Mode_') {
                    $this->_form->setMethod($params[0]);
                }
                else {
                    $type = $elementSpecs->getType();
                    echo $type;
                    $this->_addComponent($name, $type, $elementSpecs);
                }
            }
        }
//        iris_debug($this->_elementTypes);
//        foreach ($this->_elementTypes as $name => $field) {
//            if ($name == '_Mode_') {
//                $this->_form->setMethod($params[0]);
//            }
//            else {
//                $type = $field->getType();
//                echo $name;
//                $this->_addComponent($name, $type, $field);
//            }
//        }
        $this->_entity = $entity;
        $this->_labels = $labels;
    }

    protected function _addComponent($name, $type, $params) {
        $this->_done[] = $name;
        switch ($params->getType()) {
            case ' ':
            case '':    
                break;
            case 'T':
                $element = $this->_formFactory->createText($name);
                $element->addTo($this->_form);
                break;
            case 'E':
                $element2 = $this->_formFactory->createPassword($name);
                $element2->addTo($this->_form);
                break;
        }
    }

    /**
     * Creates the field controller and makes the form ready to be rended.
     * 
     * @return \Iris\Forms\AutoForm
     */

    /**
     * 
     * @return type
     */
    public function prepare() {
        $this->_method = 'post';
        $metadata = $this->_entity->getMetadata();
        $fields = $metadata->getFields();
        //iris_debug($metadata->getFields());
        foreach ($fields as $metaItem) {
            if (!in_array($metaItem->getFieldName(), $this->_done)) {
                /* @var $metaItem \Iris\DB\MetaItem */
                if (is_null($metaItem->getForeignPointer())) {
                    $this->_makeElement($metaItem);
                }
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
        $element->addTo($this->_form);
        return $this->_form;
    }

    public function render() {
        return $this->_form->render();
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
        $element->addTo($this->_form);
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
//    public static function AddPlausibleDescriptions($items) {
//        if (is_array($items)) {
//            foreach ($items as $item) {
//                self::AddPlausibleDescriptions($item);
//            }
//        }
//        else {
//            self::$_PlausibleDescriptions[] = $items;
//        }
//    }

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
