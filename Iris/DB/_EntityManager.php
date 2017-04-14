<?php

namespace Iris\DB;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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
 * @version $Id: 16/07/08 10:51 $ */
abstract class _EntityManager {

    use \Iris\System\tRepository;
    
    const FK_TABLE = 0;
    const FK_FROM = 1;
    const FK_TO = 2;
    const PATH = '\Iris\DB\Dialects\\';

    /**
     * The recognized DBMS
     */
    const MYSQL = 'mysql';
    const SQLITE = 'sqlite';
    const POSTGRESQL = 'postgresql';
    const ORACLE = 'oracle';
    const DEFAULT_DBMS = 'sqlite';

    /**
     * The corresponding class names
     * 
     * @var string[]
     */
    private static $_DBClasses = [
        self::MYSQL => 'Em_PDOmySQL',
        self::SQLITE => 'Em_PDOSQLite',
        self::POSTGRESQL => 'Em_PDOPostgresql',
        self::ORACLE => 'EmPDO_Oracle'
    ];

    
    //protected static $_DBtype;

    protected static $_DBNameToNumber = [
            self::MYSQL => 1,
            self::SQLITE => 2,
            self::POSTGRESQL => 3,
            self::ORACLE => 4,
        ];
    
    /**
     *
     * @var type 
     */
    public static $LeftLimits = "SELECT min(%s) First, max(%s) Previous FROM %s WHERE %s < '%s'";

    /**
     *
     * @var type 
     */
    public static $RightLimits = "SELECT max(%s) Last, min(%s) Next FROM %s WHERE %s > '%s'";

    /**
     *
     * @var type 
     */
    private static $_EMRepository = [];

    /**
     *
     * @var type 
     */
    protected static $_TypeName = \NULL;

    /**
     *
     * @var type 
     */
    public $Type = '';

    /**
     * Mainly for test purpose (permits to place models in other places)
     * @var string 
     */
    protected static $_EntityPath;

    /**
     * Instance privilégiée
     * @deprecated since version 2016-06
     * @var _EntityManager
     */
    //private static $_Instance = NULL;

    /**
     *
     * @var type 
     * @todo : deprecated ???
     */
    protected static $_Options = [];

    /**
     * An array repository with all entities 
     * 
     * @var _Entity[]
     */
    private $_entityRepository = [];

    /**
     * Converts a data base system number to its equivalent name
     * 
     * @param int $type
     * @return string
     */
//    public static function DBName($type) {
//        return self::$_DBNames[$type];
//    }

    /**
     * Converts a data base system number to its equivalent class name
     * 
     * @param int $type
     * @return string
     */
    public static function DBClass($type) {
        return self::$_DBClasses[$type];
    }

    //public static function 
    
    /**
     * Only first instance is registred. Another instance
     * will be replaced
     * 
     * @param _Entity $entity 
     */
    public function registerEntity(&$entity) {
        $entityName = $entity->getEntityName();
        // if another instance exists, replace the new one
        if (isset($this->_entityRepository[$entityName])) {
            $entity = $this->_entityRepository[$entityName];
        }
        else {
            $this->_entityRepository[$entityName] = $entity;
            $entity->setEntityManager($this);
        }
    }

    /**
     * Tries to unregister an entity. Throws an exception if it exists and
     * contains objects. This methods should only be used with caution
     * and in try catch context
     * 
     * @param string $entityName
     * @throws \Iris\Exceptions\EntityException
     */
    public function unregisterEntity($entityName) {
        if (isset($this->_entityRepository[$entityName])) {
            $entity = $this->_entityRepository[$entityName];
            if ($entity->hasObjects()) {
                throw new \Iris\Exceptions\EntityException('You cannot unregister an entity when it has instanciated objects');
            }
            unset($this->_entityRepository[$entityName]);
        }
    }

    /**
     * Counts how many entities are registred
     * 
     * @return int
     */
    public function entityCount() {
        return count($this->_entityRepository);
    }

    public function extractEntity($entityName) {
        if (isset($this->_entityRepository[$entityName])) {
            $entity = $this->_entityRepository[$entityName];
        }
        else {
            $entity = \NULL;
        }
        return $entity;
    }

    protected static function _GetNew($manager, $id, $dsn, $username, $passwd, &$options = []) {
        //print "Creating $id<br/>";
        if (!isset(self::$_EMRepository[$id])) {
            //$entityManager = new $manager($dsn, $username, $passwd, $options);
            self::$_EMRepository[$id] = new $manager($dsn, $username, $passwd, $options);
        }
        return self::$_EMRepository[$id];
    }

    /**
     * The constructor mustn't be used except in a factory
     * 
     * @param String $dsn : Data Source Name
     * @param String $username : user login name
     * @param String $passwd : user password
     * @param string[] $options additional options
     */
    protected function __construct($dsn, $username, $passwd, $options) {
        $this->Type = static::$_TypeName;
        foreach ($options as $key => $value) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Return the default instance (creating it if necessary)
     * 
     * @return _EntityManager 
     * @deprecated since version 2016-06
     */
//    public static function GetInstance() {
//        if (is_null(self::$_Instance)) {
//            self::$_Instance = self::_AutoInstance();
//        }
//        return self::$_Instance;
//    }

    /**
     * Sets a default instance, by bypassing the global parameters.
     * Use with caution!
     * 
     * @param _EntityManager $instance
     * @deprecated since version 2016-06
     */
//    public static function SetInstance($instance) {
//        self::$_Instance = $instance;
//    }

    /**
     * Creates the default entity manager as defined in Memory (by means
     * of a parameter file)
     * 
     * @return _EntityManager 
     * @deprecated since version 2016-06
     */
//    protected static function _AutoInstance() {
//        $memory = \Iris\Engine\Memory::GetInstance();
//        $siteMode = \Iris\Engine\Mode::GetSiteMode();
//        $params = $memory->Get('param_database', \NULL);
//        if (is_null($params)) {
//            throw new \Iris\Exceptions\DBException('No database parameters found');
//        }
//        /* @var $param \Iris\SysConfig\Config */
//        $param = $params[$siteMode];
//        //$dsn = self::_DsnFormater($param);
//        $managerClass = '\\Iris\\DB\\Dialects\\' . self::$_DBClasses[$param->database_adapter];
//        $dsn =  $managerClass::_GetDsn($param);
//        $username = $param->database_username;
//        $passwd = $param->database_password;
//        return self::EMFactory($dsn, $username, $passwd);
//    }

    /**
     *
     * @param \Iris\SysConfig\Config $param
     * @return type 
     */
//    private static function _DsnFormater($param) {
//        die($param->database_adapter);
//        $ManagerClass = '\\Iris\\DB\\Dialects\\' . self::_GetDBType($param->database_adapter);
//        return $ManagerClass::_GetDsn($param);
//    }

    /**
     *
     * @param \Iris\SysConfig\Config $param
     * @return string 
     */
    protected static function _GetDsn($param) {
        return sprintf("%s:host=%s;dbname=%s;", $param->database_adapter, $param->database_host, $param->database_dbname);
    }

    public static function GetEntityPath() {
        if (is_null(self::$_EntityPath)) {
            self::$_EntityPath = \Iris\SysConfig\Settings::$DefaultModelLibrary;
        }
        return self::$_EntityPath;
    }

    public static function SetEntityPath($entityPath) {
        self::$_EntityPath = $entityPath;
    }

    /**
     *
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param boolean $default
     * @param mixed $options
     * @return _EntityManager
     * @deprecated since january 2016 
     */
    public static function EMFactory($dsn, $username = \NULL, $passwd = \NULL, $options = []) {
        $type = strtok($dsn, ':');
        //$typeNumber = self::DBNumber($type);
        return self::_EMFactory($type, -1, $dsn, $username, $passwd, $options);
    }

    /**
     * 
     * @param type $type
     * @param type $id
     * @param type $dsn
     * @param type $username
     * @param type $passwd
     * @param type $options
     * @return type
     * @throws \Iris\Exceptions\NotSupportedException
     * @throws \Iris\Exceptions\DBException
     */
    protected static function _EMFactory($type, $id, $dsn, $username = \NULL, $passwd = \NULL, $options = []) {
        //print "EM number $id<br/>";
        if (isset(self::$_EMRepository[$id])) {
            $entityManager = self::$_EMRepository[$id];
        }
        else {
            //print "Creating EM $id<br/>";
            if (!is_string($dsn)) {
                throw new \Iris\Exceptions\NotSupportedException('No analyse written of config');
                //@todo extraire le dsn, username et password
            }
            if (!empty($dsn)) {
                if (!isset(self::$_DBClasses[$type])) {
                    throw new \Iris\Exceptions\NotSupportedException('Invalid database type');
                }
                $manager = '\Iris\DB\Dialects\\' . self::DBClass($type);
            }
            try {
                $entityManager = self::_GetNew($manager, $id, $dsn, $username, $passwd, $options);
            }
            catch (Exception $exc) {
                $message = sprintf('Error opening the database. Check parameters. Msg = "%s" Code = %d', $exc->getMessage(), $exc->getCode());
                throw new \Iris\Exceptions\DBException($message);
            }
        }
        return $entityManager;
    }

    /**
     * This obsolete method returns the entity manager defined by the default ini file
     * 
     * @return _EntityManager
     * @deprecated since version june 2016
     */
    public static function GetInstance() {
        return self::EMByNumber(0);
    }

    /**
     * Get the alternative EM used in IrisWB
     * 
     * @return _EntityManager
     */
    public static function GetAlternativeEM(){
        return self::EMByNumber(-2);
    }
    
    /**
     * Creates or retrieves an entity manager by it number
     * 
     * @param type $entityNumber the number may be predefined or a small number to get params from a config file
     * @param type $options options used during the creation of the EM
     * @return _EntityManager The entity manager retrieved in repository or created by its number
     */
    public static function EMByNumber($entityNumber = 0, &$options = []) {
        // retrieves an previously identified entity manager 
        if (isset(self::$_EMRepository[$entityNumber])) {
            $entityManager = self::$_EMRepository[$entityNumber];
        }
        // gets an internal entity manager 
        elseif ($entityNumber > 80) {
            $internalScanner = [\Iris\SysConfig\Settings::$InternalDBClass, 'InternalDB'];
            $p = $internalScanner($entityNumber); //
            $entityManager = self::_EMFactory($p['type'], $p['id'], $p['dsn'], $p['username'], $p['passwd'], $options);
        }
        // finds a sqlite filename among the options
//        elseif ($entityNumber == -1) {
//            $dsn = self::SQLITE . ":" . $options['fileName'];
//            unset($options['fileName']);
//            $entityManager = self::_EMFactory(self::SQLITE, -1, $dsn, '', '', $options);
//        }
        elseif ($entityNumber == -2) {
            $entityManager = \models\TInvoices::GetEM();
        }
        // reads the parameters from an ini
        else {
            $varName = 'param_database';
            // database.ini may have an appended number
            if ($entityNumber != 0) {
                $varName .= $entityNumber;
            }
            $params = \Iris\Engine\Memory::Get($varName);
            $param = $params[\Iris\Engine\Mode::GetSiteMode()];
            $adapterName = $param->database_adapter;
            $host = $param->database_host;
            $dbname = $param->database_dbname;
            if ($adapterName == self::SQLITE) {
                $dsn = "$adapterName:$param->database_file";
            }
            else {
                $dsn = "$adapterName:host=$host;dbname=$dbname";
            }
            $username = $param->database_username;
            $password = $param->database_password;
            $entityManager = self::_EMFactory($adapterName, $entityNumber, $dsn, $username, $password, $options);
        }
        return $entityManager;
    }

    /**
     * Each adapter must provide a way to obtain a connexion
     * 
     * @return PDO (not always)
     */
    abstract public function getConnexion();

    public function getType() {
        return $this->Type;
    }

    /**
     * Returns the adapter class name by analysing the dsn
     * 
     * @param string $dsn
     * @return string 
     * @deprecated since version 2016-06
     */
//    private static function _GetDBType($dsn) {
//        print "Using deprecated GetDBType<br/>";
//        $prefix = strtok($dsn, ':');
//        switch ($prefix) {
//            case 'mysql':
//                $type = 'Em_PDOmySQL';
//                break;
//            case 'sqlite':
//                $type = 'Em_PDOSQLite';
//                break;
//            default:
//                throw new \Iris\Exceptions\NotSupportedException('DB not supported or unknown.');
//        }
//        return $type;
//    }

    /**
     * Executes a direct SQL query on the connexion
     * 
     * @param type $sql
     * @return \PDOStatement
     */
    public abstract function directSQLQuery($sql);

    /**
     * Executes a direct SQL query on the connexion
     * 
     * @param type $sql
     * @return \PDOStatement
     */
    public abstract function directSQLExec($sql);

    /**
     * Execute a select query on the current database, returning an array of
     * Objects (found in the repository or freshly created)
     * 
     * @param _Entity $entity
     * @param string $sql
     * @param type $fieldsPH
     * @return array(Object) 
     */
    public function fetchAll(_Entity $entity, $sql, $fieldsPH = []) {
        $results = $this->getResults($sql, $fieldsPH);
        $objects = [];
        $objectType = $entity->getRowType();
        foreach ($results as $result) {
            $identifier = $this->_getIdentifier($entity, $result);
            $object = $entity->retrieveObject($identifier);
            if (is_null($object)) {
                $object = new $objectType($entity, $identifier, $result);
            }
            $objects[] = $object;
        }
        return $objects;
    }

    private function _getIdentifier($entity, $result) {
        $identifier = [];
        foreach ($entity->getIdNames() as $id) {
            if (isset($result[$id])) {
                $identifier[$id] = $result[$id];
            }
        }
        if (count($identifier) == 0) {
            foreach ($result as $field => $value) {
                $identifier[$field] = $value;
            }
        }
        return $identifier;
    }

    public function getAllEntities() {
        return $this->_entityRepository;
    }

    /**
     * @return array 
     */
    abstract public function getResults($sql, $fieldsPH = []);

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

    /**
     * Returns the table list of the database
     * 
     * @parameter boolean $views if false does not list views
     * @return array
     */
    public abstract function listTables($views = \TRUE);

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
     * 
     * @return string
     */
    public function bitOr() {
        return "%s | %s";
    }

    /**
     * Returns a format string to manage bitwise XOR operations
     * 
     * @return string
     */
    public abstract function bitXor();

    /**
     * By default, LIMIT is not supported. May be overriden in some EM.
     * 
     * @return string
     */
    public function getLimitClause() {
        return \NULL;
    }

}
