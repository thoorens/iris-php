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
 * Permits to create an instance of _Entity without the need of an explicit class
 * and class file through the static method GetEntity
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class EntityParams {

    const TABLE = 1;
    const AUTOVIEW = 2;
    const AUTOTABLE = 3;
    const VIEW = 4;
    const ENTITY = 5;

    private $_type;
    private $_fullClassName;
    private $_metadata = \NULL;
    private $_explicitModel = 'I';

    /**
     *
     * @var _EntityManager
     */
    private $_entityManager = \NULL;
    private $_entityName = \NULL;
    private $_entityClass = \NULL;
    private $_reflectionEntity = \NULL;

    public function __construct($fullClassName) {
        $this->_fullClassName = $fullClassName;
        switch ($fullClassName) {
            case 'Iris\\DB\\_Entity':
                throw new \Iris\Exceptions\DBException('GetEntity must be used with \\Iris\\DB\AutoEntity not _Entity.');
                break;
            case 'Iris\\DB\\TableEntity':
                $this->_type = self::AUTOTABLE;
                break;
            case 'Iris\\DB\\ViewEntity':
                $this->_type = self::AUTOVIEW;
                break;
            default :
                $className = substr($fullClassName, 1 + strrpos($fullClassName, '\\'));
                $this->_type = self::ENTITY;
                $this->_entityName = strtolower(substr($className, 1));
        }
    }

    public function analyseParams($params) {
        $strings = [];
        foreach ($params as $param) {
            if ($param instanceof \Iris\DB\_EntityManager) {
                $this->_entityManager = $param;
            }
            elseif ($param instanceof \Iris\DB\Metadata) {
                $this->_metadata = $param;
            }
            else {
                $strings[] = $param;
            }
        }
        switch ($this->_type) {
            case self::ENTITY:
                $this->_analyseParamEntity($strings);
                break;
            case self::AUTOTABLE:
                $this->_analyseParamAutotable($strings);
                break;
            case self::AUTOVIEW:
                $this->_analyseParaAutoview($strings);
                break;
        }
    }

    /**
     * Returns the defaults entity manager corresponding to the class. _Entity uses the static method _EntityManager::GetInstance(),
     * but other model classes may have a specific default manager.
     * 
     * @return _EntityManager
     */
    public function getEntityManager() {
        if (is_null($this->_entityManager)) {
            $this->_entityManager = call_user_func([$this->_fullClassName, 'DefaultEntityManager']);
        }
//iris_debug($this->_entityManager->listTables());
        return $this->_entityManager;
    }

    public function getParameters() {
        return [$this->_entityName, $this->_entityClass, $this->_metadata];
    }

    /**
     * Analyses the params in the case of a subclass of _Entity
     * @param array $strings
     * @throws \Iris\Exceptions\DBException
     */
    private function _analyseParamEntity($strings) {
        if (count($strings)) {
            throw new \Iris\Exceptions\DBException('Explicit model classes cannot have entity specifications. Put parameters in the class.');
        }
        $this->_entityClass = $this->_fullClassName;
    }

    public function _analyseParamAutotable($strings) {
        switch (count($strings)) {
            case 0:
                throw new \Iris\Exceptions\DBException('Implicit model entities need explicit specifications.');
                break;
            case 2:
                $this->_entityClass = $strings[1];
// no break
            case 1:
                $this->_entityName = $strings[0];
                break;
        }
    }

    public function _analyseParaAutoview($strings) {
        if (count($strings) < 2) {
            throw new \Iris\Exceptions\DBException('Implicit model views need explicit specifications: view name et reference entity.');
        }
        $this->_entityName = $strings[0];
        $this->_reflectionEntity = $strings[1];
    }

    public function getEntityName() {
        return $this->_entityName;
    }

    public function getEntityClass() {
        return $this->_entityClass;
    }

    public function getMetadata() {
        return $this->_metadata;
    }

    public function show() {
        echo "<tr> <td>F: $this->_fullClassName";
        switch ($this->_type) {
            case self::TABLE:
                echo "</td> <td>TABLE";
                break;
            case self::AUTOTABLE:
                echo "</td> <td>AUTOTABLE";
                break;
            case self::AUTOVIEW:
                echo "</td> <td>AUTOVIEW";
                break;
            case self::VIEW:
                echo "</td> <td>VIEW";
                break;
            case self::ENTITY:
                echo "</td> <td>ENTITY";
                break;
        }
        echo "</td><td>N: $this->_entityName";
        echo "</td> <td>C: $this->_entityClass";
        echo "</td> <td>R: $this->_reflectionEntity";
        echo "</td> <td>M: $this->_explicitModel";
        echo "</td></tr>";
    }

    public function updateParams($entity) {
        $this->_explicitModel = 'E';
        if ($entity instanceof ViewEntity) {
            $this->_reflectionEntity = $entity->getReflectionEntity();
            $this->_entityName = $entity->getEntityName();
            $this->_type = self::VIEW;
        }
        else {
            $this->_entityName = $entity->getEntityName();
            $this->_type = self::TABLE;
        }
    }

    public function setEntityName($entityName) {
        $this->_entityName = $entityName;
    }

}

