<?php

namespace Iris\DB;

use Iris\Exceptions\EntityException;

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
class EntityBuilder {

    /**
     *
     * @var int
     */
    private $_type;

    /**
     *
     * @var type 
     */
    private $_initialClassName;

    /**
     *
     * @var type 
     */
    private $_metadata = \NULL;

    /**
     *
     * @var _EntityManager
     */
    private $_entityManager = \NULL;

    /**
     *
     * @var type 
     */
    private $_proposedEntityName = \NULL;

    /**
     *
     * @var type 
     */
    private $_reflectionEntityName = \NULL;

    /**
     *
     * @var string[]
     */
    private $_strings = [];

    /**
     * The constructor provides a first analysis of the context. It determines the type of entity class: <ul>
     * <li> a model class
     * <li> a table entity
     * <li> a view entity
     * </ul>
     * It scans the parameters of the method GetEntity() to find <ul>
     * <li> an optionnal entity manager
     * <li> an optional metadata as an object or a serialized string
     * <li> 0, 1 or 2 remaining string parameters considered as table or view name
     * </ul>
     *  
     * @param string $className
     * @param string $params
     * @throws \Iris\Exceptions\EntityException
     */
    public function __construct($className, $params) {
        $this->_initialClassName = $className;
        switch ($className) {
            // GetEntity cannot be used on the abstract mother class _Entity
            case 'Iris\\DB\\_Entity':
                throw new \Iris\Exceptions\EntityException('GetEntity must be used with \\Iris\\DB\\TableEntity or \\Iris\\DB\\ViewEntity or a model class.');
                break;
            case 'Iris\\DB\\TableEntity':
            case 'Iris\\DB\\ViewEntity':
                break;
            // must be a models\\...\\className
            default :
                $className = substr($className, 1 + strrpos($className, '\\'));
                // tries to guess the entityName
                $this->_proposedEntityName = strtolower(substr($className, 1));
        }
        foreach ($params as $param) {
            if ($param instanceof \Iris\DB\_EntityManager) {
                $this->_entityManager = $param;
            }
            elseif ($param instanceof \Iris\DB\Metadata or strpos($param, 'TABLE@') === 0) {
                if (is_string($param)) {
                    $metadata = new Metadata();
                    $metadata->unserialize($param);
                    $this->_metadata = $metadata;
                }
                else {
                    $this->_metadata = $param;
                }
            }
            else {
                $this->_strings[] = $param;
            }
        }
    }

    /**
     * 
     * @return _Entity
     */
    public function createExplicitModel() {
        $entity = $this->_getModel(\FALSE);
        $metadata = $entity->getMetadata();
        $entity->setEntityName($metadata->getTablename(), \TRUE);
        $entity->setIdNames($metadata->getPrimary());
        $entity->validate();
        return $entity;
    }

    /**
     * 
     * @return _Entity
     */
    public function createExplicitView() {
        $entity = $this->_getModel(\TRUE);
        $metadata = $this->_metadata;
        if (is_null($metadata)) {
            $entity->setReflectionEntity($this->_reflectionEntityName);
            $metadata = $entity->getMetadata();
        }
        $entity->setIdNames($metadata->getPrimary());
        $entity->validate();
        return $entity;
    }

    /**
     * Takes a model entity from the repository or if necessary, creates it
     * 
     * @param boolean $view
     * @return ViewEntity
     * @throws EntityException
     */
    private function _getModel($view) {
        // error analysis
        $strings = $this->_strings;
        if (count($strings)) {
            if ($view) {
                $message = 'An explicit view model class cannot have entity specifications. Put parameters in the class if necessary.';
            }
            else {
                $message = 'An explicit model class cannot have entity specifications. Put parameters in the class if necessary.';
            }
            throw new EntityException($message, EntityException::$ErrorCode + 1);
        }
        if ($this->_hasMetadata()) {
            if ($view) {
                $message = 'An explicit view model class cannot have a metadata parameter';
            }
            else {
                $message = 'An explicit model class cannot have a metadata parameter';
            }
            throw new EntityException($message, EntityException::$ErrorCode + 2);
        }
        $entity = $this->_seekEntity($this->_proposedEntityName);
        if (is_null($entity)) {
            $className = $this->_initialClassName;
            try{
            $entity = $this->_createInstance($className, $this->_proposedEntityName);
            }
            catch (\Exception $ex){
                die('Table inconnue');
            }
        }
        return $entity;
    }

    /**
     * Tries to retrive an table entity by using its name or its metadata. If necessary,
     * it creates it. It does not use any predefined model class.
     * 
     * @return _Entity
     * @throws EntityException
     */
    public function createTable() {
        $stringNumber = count($this->_strings);
        // no metadata : 1 and only 1 string
        if (is_null($this->_metadata)) {
            if ($stringNumber != 1) {
                throw new EntityException('TableEntity::CreateEntity() expects a metadata or a table name as parameter.');
            }
            $this->_proposedEntityName = $this->_strings[0];
        }
        // if metadata : no string
        else {
            if ($stringNumber != 0) {
                throw new EntityException('TableEntity::CreateEntity() with a metadata parameter cannot have a table name as parameter.');
            }
            $this->_proposedEntityName = $this->_metadata->getTablename();
        }

        $entity = $this->_seekEntity($this->_proposedEntityName);
        if (is_null($entity)) {
            $className = $this->_initialClassName;
            $entity = $this->_createInstance($className, $this->_proposedEntityName);
            $metadata = $entity->getMetadata();
            $entity->setEntityName($metadata->getTablename());
            $entity->setIdNames($metadata->getPrimary());
            $entity->validate();
        }
        return $entity;
    }

    /**
     * Tries to retrieve a view entity by using its name and the metadata to manage it by using explicit metadata or find them in
     * a second entity known by its name.
     * 
     * @return _Entity
     * @throws EntityException
     */
    public function createView() {
        // error analysis
        $stringNumber = count($this->_strings);
        if (is_null($this->_metadata)) {
            if ($stringNumber != 2) {
                throw new EntityException('View::CreateEntity()  without metadata expects an entity name and a reflection entity name as parameters.');
            }
            $this->_proposedEntityName = $this->_strings[0];
            $this->_reflectionEntityName = $this->_strings[1];
        }
        else {
            if ($stringNumber != 1) {
                throw new EntityException('TableEntity::CreateEntity() with metadata expects an entity view name as parameter.');
            }
            $this->_proposedEntityName = $this->_strings[0];
            $this->_reflectionEntityName = $this->_metadata->getTablename();
        }
        $entity = $this->_seekEntity($this->_proposedEntityName);
        if (is_null($entity)) {
            $className = $this->_initialClassName;
            $entity = $this->_createInstance($className, $this->_proposedEntityName);
            $metadata = $this->_metadata;
            if (is_null($metadata)) {
                $entity->setReflectionEntity($this->_reflectionEntityName);
                $metadata = $entity->getMetadata();
            }
            $entity->setIdNames($metadata->getPrimary());
            $entity->setMetadata($metadata);
            $entity->validate();
        }
        return $entity;
    }

    /**
     * Creates a new instance of entity using its classname and entity name.
     * 
     * @param string $className
     * @param string $entityName
     * @return _Entity
     * @throws \Iris\Exceptions\EntityException
     */
    private function _createInstance($className, $entityName) {
        $classFile = \Iris\System\Functions::Class2File($className);
        $simpleFile = IRIS_PROGRAM_PATH . "/$classFile";
        $specialFile = IRIS_ROOT_PATH . '/' . IRIS_LIBRARY . "/$classFile";
        // creates a new entity
        if (file_exists($simpleFile)) {
            $entity = _Entity::GetNewInstance($className, $entityName);
        }
        elseif (file_exists($specialFile)) {
            $entity = _Entity::GetNewInstance($className, $entityName);
        }
        else {
            throw new \Iris\Exceptions\EntityException("There is non $className entity class.");
        }
        $this->_entityManager->registerEntity($entity);
        return $entity;
    }

    /**
     * Tries to find an entity by its name in the repository
     * @param string $entityName
     * @return _Entity
     */
    private function _seekEntity($entityName) {
        $entityManager = $this->getEntityManager();
        $entity = $entityManager->extractEntity($entityName);
        return $entity;
    }

    /**
     * Returns the defaults entity manager corresponding to the class. _Entity uses the static method _EntityManager::GetInstance(),
     * but other model classes may have a specific default manager.
     * 
     * @return _EntityManager
     */
    public function getEntityManager() {
        if (is_null($this->_entityManager)) {
            $this->_entityManager = call_user_func([$this->_initialClassName, 'DefaultEntityManager']);
        }
        return $this->_entityManager;
    }

    private function _hasMetadata() {
        return !is_null($this->_metadata);
    }

    public function getClass() {
        return $this->_initialClassName;
    }

}

