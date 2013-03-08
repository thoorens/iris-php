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
 * This class creates objects based on DSN, UserName and 
 * Password. One of them is conserved as the default.
 * Each _EntityManager can be used to access data in
 * a database. Concrete entity managers are instancied
 * from classes in namespace dialects.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _EntityManager {
    const FK_TABLE = 0;
    const FK_FROM = 1;
    const FK_TO = 2;

    public static $leftLimits = "SELECT min(%s) First, max(%s) Previous FROM %s WHERE %s < '%s'";
    public static $rightLimits = "SELECT max(%s) Last, min(%s) Next FROM %s WHERE %s > '%s'";

    /**
     * Mainly for test purpose (permits to place models in other places)
     * @var string 
     */
    public static $entityPath = '\\models';

    /**
     * Instance privilégiée
     * @var _EntityManager
     */
    private static $_Instance = NULL;

    /**
     *
     * @var type 
     * @todo : deprecated ???
     */
    protected static $_Options = array();

    /**
     * An array repository with all entities and objects
     * 
     * EN# = entity Name
     * OK# = object ID (with _ separating parts)
     * 
     * array[EN1]['@'] -> entity1
     * array[EN1][OK1] -> object1
     * array[EN1][OK2] -> object2
     * array[EN2]['@'] -> entity2
     * array[EN2][OK3] -> object3
     * 
     * @var array 
     */
    private $_objectRepository = array();

    /**
     * Only first instance is registred
     * @param _Entity $entity 
     */
    public function registerEntity(_Entity $entity) {
        $entityName = $entity->getEntityName();
        if (!isset($this->_objectRepository[$entityName])) {
            $this->_objectRepository[$entityName]['@'] = $entity;
        }
    }

    /**
     *
     * @param string $entityName
     * @param _EntityManager $EM
     * @return _Entity 
     */
    public static function GetEntity($entityName, _EntityManager $EM=NULL) {
        if (is_null($EM)) {
            $EM = self::$_Instance;
        }
        if (isset($EM->_objectRepository[$entityName]['@'])) {
            return $EM->_objectRepository[$entityName]['@'];
        }
        else {
            $entityFullPath = self::$entityPath . '\\T' . ucfirst($entityName);
            // Caution : auto registering !  
            return new $entityFullPath();
        }
    }

    /**
     * Put an object in the repository
     * 
     * @param string $entityName
     * @param array $idValues
     * @param Object $object 
     */
    public function registerObject($entityName, $idValues, $object) {
        $id = implode('|', $idValues);
        $this->_objectRepository[$entityName][$id] = $object;
    }

    /**
     * Retrieve an object from the repository (if possible, NULL otherwise)
     * 
     * @param  string $entityName
     * @param array $idValues
     * @return Object 
     */
    public function retrieveObject($entityName, $idValues) {
        $id = implode('|', $idValues);
        if (isset($this->_objectRepository[$entityName][$id])) {
            return $this->_objectRepository[$entityName][$id];
        }
        else {
            return NULL;
        }
    }

    /**
     * The constructor mustn't be used except in a factory
     * 
     * @param String $dsn : Data Source Name
     * @param String $username : user login name
     * @param String $passwd : user password
     * @param boolean $default : if TRUE store this EM as default
     * @param array $options additionnal options
     */
    protected function __construct($dsn, $username, $passwd, &$options=\NULL) {
        if (!is_null($options)) {
            $options = array_merge(static::$_Options, $options);
        }
        else {
            $options = static::$_Options;
        }
    }

    /**
     * Return the default instance (creating it if necessary)
     * 
     * @return _EntityManager 
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            self::$_Instance = self::_AutoInstance();
        }
        return self::$_Instance;
    }

    /**
     * Create the default entity manager as defined in Memory (by means
     * of a parameter file)
     * @return _EntityManager 
     */
    protected static function _AutoInstance() {
        $memory = \Iris\Engine\Memory::GetInstance();
//        if (!isset($memory->param_database)) {
//            throw new \Iris\Exceptions\DBException('No parameter defined');
//        }
        $mode = \Iris\Engine\Mode::GetSiteMode();
        $params = $memory->param_database;
        $param = $params[$mode];
        $dsn = self::_DsnFormater($param);
        $username = $param->database_username;
        $passwd = $param->database_password;
        return self::EMFactory($dsn, $username, $passwd);
    }

    /**
     *
     * @param type $param
     * @return type 
     */
    private static function _DsnFormater($param) {
        $ManagerClass = '\\Iris\\DB\\Dialects\\' . self::_GetDBType($param->database_adapter);
        return $ManagerClass::_GetDsn($param);
    }

    /**
     *
     * @param array $param
     * @return string 
     */
    protected static function _GetDsn($param) {
        return sprintf("%s:host=%s;dbname=%s;", $param->database_adapter, $param->database_host, $param->database_dbname);
    }

    /**
     *
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param boolean $default
     * @param mixed $options
     * @return _EntityManager 
     */
    public static function EMFactory($dsn, $username=NULL, $passwd=NULL, $options = array()) {
        if (!is_string($dsn)) {
            throw new \Iris\Exceptions\NotSupportedException('No analyse written of config');
            //@todo extraire le dsn, username et password
        }
        $manager = '\\Iris\\DB\\Dialects\\' . self::_GetDBType($dsn);
        try {
            $em = new $manager($dsn, $username, $passwd, $default, $options);
        }
        catch (Exception $exc) {
            $message = $exc->getMessage();
            $code = $exc->getCode();
            throw new \Iris\Exceptions\DBException('Error opening the database. Check parameters');
        }
        return $em;
    }

    /**
     * Each adapter must provide a way to obtain a connexion
     * 
     * @return PDO (not always)
     */
    abstract public function getConnexion();

    /**
     * Returns the adapter class name by analysing the dsn
     * 
     * @param string $dsn
     * @return string 
     * CAUTION: this method must be modified each time a new dialect is added
     * to the framework (see todo below)
     */
    private static function _GetDBType($dsn) {
        //@todo It should be possible to scan Dialects folder to found all
        // adapters
        $prefix = strtok($dsn, ':');
        switch ($prefix) {
            case 'mysql':
                $type = 'Em_PDOmySQL';
                break;
            case 'sqlite':
                $type = 'Em_PDOSQLite';
                break;
            default:
                throw new \Iris\Exceptions\NotSupportedException('DB not supported or unknown.');
        }
        return $type;
    }

    /**
     * Executes a directs SQL query on the connexion.
     * 
     * @param type $sql
     * @return array(\PDORow)
     */
    public function directSQL($sql) {
        $result = $this->_connexion->query($sql);
        return $result;
    }

    /**
     * Execute a select query on the current database, returning an array of
     * Objects (found in the repository or freshly created)
     * 
     * @param _Entity $entity
     * @param string $sql
     * @param type $fieldsPH
     * @return array(Object) 
     */
    public function fetchall(_Entity $entity, $sql, $fieldsPH=array()) {
        $results = $this->getResults($sql, $fieldsPH);
        $objects = array();
        $objectType = $entity->getRowType();
        foreach ($results as $result) {
            $idValues = array();
            foreach ($entity->getIdNames() as $id) {
                $idValues[$id] = $result[$id];
            }
            $object = $this->retrieveObject($entity->getEntityName(), $idValues);
            if (is_null($object)) {
                $object = new $objectType($entity, $idValues, $result);
            }
            $objects[] = $object;
        }
        return $objects;
    }

    /**
     * @return array 
     */
    abstract public function getResults($sql, $fieldsPH=array());

    abstract public function exec($sql, $value);

    /**
     * @param string $tableName The table name corresponding to the entity
     * @return \Iris\DB\Metadata The metadata corresponding to the table
     */
    public abstract function readFields($tableName);

    /**
     * @return array(ForeignKey)
     */
    public abstract function getForeignKeys($tableName);

    public abstract function listTables();

    public abstract function lastInsertedId($entity);

    /**
     * Returns a format string to manage bitwise AND operations
     *
     * @return sting
     */
    public function bitAnd() {
        return "%s & %s";
    }

    /**
     * Returns a format string to manage bitwise OR operations
     * @return string
     */
    public function bitOr() {
        return "%s | %s";
    }

    public abstract function bitXor();
}

?>
