<?php

namespace wbClasses;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This is a special class to create and 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class AutoEM {

    /**
     * The recognized DBMS
     */
    const MYSQL = 1;
    const SQLITE = 2;
    const POSTGRESQL = 3;
    
    const DF_FILENAME = 'library/IrisWB/application/config/base/invoice.sqlite';
    const DF_HOST = 'localhost';
    const DF_BASE = 'wb_db';
    const DF_USER = 'wb_user';
    const DF_PW= 'wbwp';

    /**
     * The unique instance of WBModels created or loaded by GetInstance
     * 
     * @var wbClasses\AutoEM
     */
    protected static $_Instance = \NULL;
    
    /**
     * This internal data is an indication of the type of the RDBMS
     * used by the demo entities of the invoice system
     * 
     * @var int
     */
    private $_dbType = NULL;

    /**
     * This array contains all the parameters used to cread the entity manager
     * uses by all the demo entities of the invoice system
     * @var array
     */
    private $_sqlParameters = [];
    /**
     *
     * @var \Iris\DB\_EntityManager
     */
    private $_em = \NULL;

    /**
     * The class initializer creates an entity manager according <ul>
     * <li> the default values (an SQLite file
     * <li> the values put precendently in the session memory
     * </ul>
     */
    

    public function getEm() {
        return $this->_em;
    }

        
    
    
    public function setSqlParameters($sqlParameters) {
        $this->_sqlParameters = $sqlParameters;
        return $this;
    }

    public function getSqlParameters() {
        return $this->_sqlParameters;
    }

    /**
     * 
     * @return wbClasses\AutoEM
     */
    public static function GetInstance() {
        if (is_null(self::$_Instance)) {
            $instance = new \wbClasses\AutoEM();
            $session = \Iris\Users\Session::GetInstance();
            $instance->_dbType = $session->getValue('entityType', self::SQLITE);
            $instance->_sqlParameters = $session->getValue('SQLParams', ['file'=>  self::DF_FILENAME]);
            $instance->setEm();
            self::$_Instance = $instance;
        }
        return self::$_Instance;
    }

//    public static function SetInstance($entityType, $entityName) {
//        if (is_null(self::$_Instance)) {
//            $entity = new WBModels($entityName);
//            $entity->setDefaultDbType($entityType);
//            $entity->setSqlParameters(self::$_DefaultSQLParameters);
//            self::$_Instance = $entity;
//        }
//    }

    public function getDbType() {
        return $this->_dbType;
    }

    public function setDbType($defaultDbType) {
        $this->_dbType = $defaultDbType;
        return $this;
    }

    /**
     * The default file name for Sqlite database. The full name will be completed by EMFactory
     * 
     * @var string
     */

    /*
     * The SQL command to create a new mySQL database
     * 
     * CREATE USER 'wb_user'@'localhost' IDENTIFIED BY 'wbwp';
     * GRANT USAGE ON * . * TO 'wb_user'@'localhost' IDENTIFIED BY 'wbwp' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
     * CREATE DATABASE IF NOT EXISTS `wb_db` ;
     * GRANT ALL PRIVILEGES ON `wb_db` . * TO 'wb_user'@'localhost';
     * 
     * 
     * Here we have default parameters
     */

    public static function GetEntityManager(){
        $instance = self::GetInstance();
        return $instance->$_em;
    }

    public function setEm($force = \FALSE) {
        if ($force or is_null($this->_em)) {
            switch ($this->_dbType) {
                case self::SQLITE:
                    $file = $this->_sqlParameters['file'];
                    $this->_em = \Iris\DB\_EntityManager::EMFactory("sqlite:$file");
                    break;
                case self::MYSQL:
                    $host = $this->_sqlParameters['host'];
                    $base = $this->_sqlParameters['base'];
                    $user = $this->_sqlParameters['user'];
                    $password = $this->_sqlParameters['password'];
                    $this->_em = \Iris\DB\Dialects\Em_PDOmySQL::EMFactory("mysql:host=$host;dbname=$base", $user, $password);
                    break;
            }
        }
        return $this->_em;
    }

    /**
     * Creates un new table in the database
     */
//    public static function Create() {
//        $em = static::GetEM2();
//        $dbType = static::$_DefaultDbType;
//        $sql = static::$_SQLCreate[$dbType];
//        $em->directSQLExec($sql);
//    }

    

}
