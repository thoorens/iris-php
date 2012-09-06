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

    public function __construct(db\_Entity $entity, $labels = array()) {
        $this->_entity = $entity;
        $this->_name = sprintf("iris_autoform_%d", self::$_Number++);
        $this->_method = 'post';
        $metadata = $entity->getMetadata();
        $this->_labels = $labels;
        $this->_formFactory = _FormFactory::GetDefaultFormFactory();
        $fields = $metadata->getFields();
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
    }

    /**
     *
     * @param db\MetaItem $metaItem
     * @param array $labels
     */
    private function _makeElement(db\MetaItem $metaItem) {
        switch ($metaItem->getType()) {
            case 'text':
            default :
                $element = $this->_formFactory->createText($metaItem->getFieldName());
                $element->setLabel($this->_getLabelText($metaItem));
                break;
        }
        if ($metaItem->isAutoIncrement()) {
            $element->setDisabled('disabled');
        }
        $element->addTo($this);
    }

    private function _makeSelect(db\MetaItem $metaItem, \Iris\DB\ForeignKey $foreign) {
        /* @var $element \Iris\Forms\Elements\SelectElement */
        $element = $this->_formFactory->createSelect($metaItem->getFieldName());
        $element->setLabel($this->_getLabelText($metaItem));
        $element->addTo($this);
        $EM = $this->_entity->getEntityManager();
        $tableName = $foreign->getTargetTable();
        $targetTable = \Iris\DB\DataBrowser\AutoEntity::EntityBuilder($tableName, $foreign->getToKeys(), $EM);
        $idNames = $foreign->getToKeys();
        $idName = $idNames[0];
        //@todo change it
        $descField = 'Name';
        $targetTable->select($idName);
        $targetTable->select($descField);
        $data = $targetTable->fetchall();
        foreach ($data as $ligne) {
            $data2[$ligne->$idName] = $ligne->$descField;
        }
        $element->addOptions($data2);
    }

    protected function _getLabelText(\Iris\DB\MetaItem $metaItem) {
        $name = $metaItem->getFieldName();
        if (isset($this->_labels[$name])) {
            return $this->_labels[$name];
        }
        else {
            return $metaItem->get('LABEL',$name);
        }
    }

}

?>
