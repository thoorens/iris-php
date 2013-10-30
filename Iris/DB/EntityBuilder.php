<?php

namespace Iris\DB;

use Iris\Exceptions\DBException;

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

    const TABLE = 1;
    const AUTOVIEW = 2;
    const AUTOTABLE = 3;
    const VIEW = 4;
    const ENTITY = 5;
    const METADATA = 6;

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
     * @var type 
     */
    private $_explicitModel = 'I';

    /**
     *
     * @var _EntityManager
     */
    private $_entityManager = \NULL;

    /**
     *
     * @var type 
     */
    private $_entityName = \NULL;

    /**
     *
     * @var type 
     */
    private $_entityClass = \NULL;

    /**
     *
     * @var type 
     */
    private $_reflectionEntity = \NULL;
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
     * @throws \Iris\Exceptions\DBException
     */
    public function __construct($className, $params) {
        $this->_initialClassName = $className;
        switch ($className) {
            // GetEntity cannot be used on the abstract mother class _Entity
            case 'Iris\\DB\\_Entity':
                throw new \Iris\Exceptions\DBException('GetEntity must be used with \\Iris\\DB\\TableEntity or \\Iris\\DB\\ViewEntity or a model class.');
                break;
            case 'Iris\\DB\\TableEntity':
                $this->_type = self::AUTOTABLE;
                break;
            case 'Iris\\DB\\ViewEntity':
                $this->_type = self::AUTOVIEW;
                break;
            // must be a models\\...\\Xclassname (with intitial T or V)
            default :
                $className = substr($className, 1 + strrpos($className, '\\'));
                if ($className[0] == 'V') {
                    $this->_type = self::VIEW;
                }
                // table entity should begin with T, but anything but V is accepted
                else {
                    $this->_type = self::TABLE;
                }
                $this->_entityName = strtolower(substr($className, 1));
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

    public function createExplicitModel() {
        // error analysis
        $strings = $this->_strings;
        if (count($strings)) {
            $message = 'An explicit model class cannot have entity specifications. Put parameters in the class if necessary.';
            throw new DBException($message, DBException::$ErrorCode + 1);
        }
        if ($this->_hasMetadata()) {
            $message = 'An explicit model class cannot have a metadata parameter';
            throw new DBException($message, DBException::$ErrorCode + 2);
        }

        $entity = $this->_seekEntity($this->_entityName);
        if (is_null($entity)) {
            $className = $this->_initialClassName;
            $entity = $this->_createInstance($className, $this->_entityName);
            // ?? put the rest in createInstance
            $metadata = $entity->getMetadata();
            $entity->setMetadata($metadata);
            $entity->setEntityName($metadata->getTablename());
            $entity->setIdNames($metadata->getPrimary());
        }
        
        
        return $entity;
    }

    public function createTable() {
        // error analysis
        $strings = count($this->_strings);
        if(is_null($this->_metadata)){
            if($strings != 1){
                throw new DBException('TableEntity::CreateEntity() expects a metadata or a table name as parameter.');
            }
        }
        else{
            if($strings != 0){
                throw new DBException('TableEntity::CreateEntity() with a metadata parameter cannot have a table name as parameter.');
            }
        }
        // later
        if(is_null($this->_metadata)){
            $this->_entityName = $this->_strings[0];
        }
        else{
            $this->_entityName = $this->_metadata->getTablename();
        }
        
        $entity = $this->_seekEntity($this->_entityName);
        if (is_null($entity)) {
            $className = $this->_initialClassName;
            $entity = $this->_createInstance($className, $this->_entityName);
            // ?? put the rest in createInstance
            $metadata = $entity->getMetadata();
            $entity->setMetadata($metadata);
            $entity->setEntityName($metadata->getTablename());
            $entity->setIdNames($metadata->getPrimary());
        }
        return $entity;
    }

    public function createView(){
        
    }
    
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
        $this->_entityManager->registerEntity($entity);
        $entity->setEntityManager($this->_entityManager);
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

    

}

