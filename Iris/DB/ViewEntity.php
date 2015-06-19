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
 * A special entity for a database view. It can automatically
 * gets its metadata from another table. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ViewEntity extends _Entity {

    /**
     * A reflection Entity is an entity used to create the metadata
     * 
     * @var string
     */
    protected $_reflectionEntity = \NULL;

    /**
     * By default, a view is read only.
     * 
     * @var boolean
     */
    protected $_readOnly = \TRUE;

    public static function GetEntity() {
        $entityBuilder = new EntityBuilder(get_called_class(), func_get_args());
        if ($entityBuilder->getClass() == 'Iris\\DB\\ViewEntity') {
            return $entityBuilder->createView();
        }
        else {
            return $entityBuilder->createExplicitView();
        }
    }

    /**
     * This overridden method uses the reflection entity to get a new metadata
     * 
     * @param Metadata $metadata
     * @return Metadata
     */
    protected function _readMetadata($metadata = \NULL) {
        if (is_null($this->_metadata)) {
            $masterEntity = TableEntity::GetEntity($this->_reflectionEntity, $this->getEntityManager());
            $metadata = $masterEntity->getMetadata($this->_metadata);
            return $metadata;
        }
        return parent::_readMetadata($metadata);
    }

    public function getReflectionEntity() {
        return $this->_reflectionEntity;
    }

    /**
     * Tries to init the reflection entity name. This accessor will never
     * erase a previously inited name (set in the model definition for instance)
     * 
     * @param string $reflectionEntity
     * @return \Iris\DB\ViewEntity (fluent interface)
     */
    public function setReflectionEntity($reflectionEntity) {
        if (!is_null($reflectionEntity)) {
            $this->_reflectionEntity = $reflectionEntity;
        }
        return $this;
    }

    /**
     * Tests if the object created with this entity are read only
     * 
     * @return boolean
     */
    public function isReadOnly() {
        return $this->_readOnly;
    }


}

