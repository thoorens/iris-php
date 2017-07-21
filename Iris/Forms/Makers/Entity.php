<?php

namespace Iris\Forms\Makers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * A form factory using an entity or its metadata
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 */
class Entity extends \Iris\Forms\_FormMaker {

    public static $Mode = self::MODE_ENTITY;

    /**
     * 
     * @param type $parameter
     * @param type $factoryType
     */

    /**
     * 
     * @param mixed $entity an entity or its metadata
     * @param string $factoryType
     * @param string $formName
     */
    public function __construct($entity, $factoryType = \NULL, $formName = 'form') {
        $this->_initFactory($factoryType);
        $this->setFormName($factoryType);
        $this->scanEntity($entity);
    }

    /**
     * Retrieves the metadata of the given entity
     * 
     * @param \Iris\DB\_Entity $entity the entity to take the metadata from
     */
    public function scanEntity($entity) {
        $this->_specifySource(self::MODE_ENTITY);
        if ($entity instanceof \Iris\DB\_Entity) {
            $metadata = $entity->getMetadata();
        }
        else {
            $metadata = $entity;
        }
        $this->_metadata = $metadata;
        ElementMaker::SetForeignPointers($metadata->getForeigns());
        foreach ($metadata->getFields() as $metaItem) {
            // Primary Treatment
            if ($this->_mode == self::MODE_ENTITY) {
                $AE_Element = new ElementMaker($metaItem);
                $AE_Element->setEntity($entity);
                $fieldName = $AE_Element->getName();
                $this->_fieldList[$fieldName] = $AE_Element;
                $this->_fieldOrder[] = $fieldName;
            }
            // Complementary treatment
            else {
                $fieldName = $metaItem->getFieldName();
                i_d('Complement AutoEntityElement here');
            }
        }
    }

    protected function _insertElements() {
        ElementMaker::Prepare($this->_form);
        foreach ($this->_fieldOrder as $position => $fieldName) {
            $field = $this->_fieldList[$fieldName];
            $field->addElement();
        }
    }

    /**
     * 
     * @param \Iris\DB\MetaItem $metaItem
     * @deprecated since version number
     */
    public function _convert($metaItem) {
        $factory = $this->_formFactory;
        $fieldName = $metaItem->getFieldName();
        $creator = $this->_getType($metaItem, $fieldName);
        die('_convert');
        $element = $factory->$creator($fieldName);
        i_d($element);
    }

    /**
     * 
     * @param \Iris\DB\MetaItem $metaItem
     */
    public function _getType($metaItem, $name) {
        die('OK getType');
        i_d($this->_elementList);
        $type = strtolower($metaItem->getType());
        if ($this->_subMode == self::MODE_INI) {
            
        }
        else if ($this->_subMode == self::MODE_HANDMADE) {
            if (isset($this->_elementList[$name])) {
//                i_d($this->_subMode);
                $field = $this->_elementList[$name];
//                i_d($field);
            }
        }
        else {
            
        }
        return "create" . ucfirst($type);
    }

}
