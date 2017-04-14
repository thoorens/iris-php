<?php

namespace Iris\DB\Dialects;

use Iris\Exceptions as ie;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Implementation of a Posgresql entity manager
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Em_PDOPostgresql extends \Iris\DB\Dialects\_Em_PDO {

    /**
     * Postgresql default schema name
     * @var string
     */
    private static $_DefaultSchema = 'public';

    /**
     * Postgresql default port number
     * @var int
     */
    public static $lDefaultPortNumber = 5432;

    /**
     * The port number of the active instance
     * @var int
     */
    protected $_portNumber;
    private $_schema;

    /**
     * Each _entityManager has its own type name
     */
    public static function __ClassInit() {
        static::$_TypeName = \Iris\DB\_EntityManager::POSTGRESQL;
    }

    /**
     * Postgresql constructor must add a schema name
     * 
     * @param type $dsn
     * @param type $userName
     * @param type $passwd
     * @param type $options
     */
    public function __construct($dsn, $userName, $passwd, $options) {
        parent::__construct($dsn, $userName, $passwd, $options);
        $this->_schema = static::$_DefaultSchema;
    }

    /**
     * In the case of Posgresql, it is necessary to add an option to have utf8 characters
     *
     * @var array
     */
//    protected static $_Options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

    /*
      Example of SHOW COLUMNS FROM output:
     *
      array(6) {
      ["Field"]=> "id"
      ["Type"]=> "int(11)"
      ["Null"]=> "NO"
      ["Key"]=>  "PRI"
      ["Default"]=> NULL
      ["Extra"]=> "auto_increment"
     */

    /**
     * Gets the metadata corresponding to the table
     *
     * @param string $tableName The name of the table
     * @return \Iris\DB\Metadata
     */
    public function readFields($tableName) {
        //$pdo = $this->_connexion;
//        SELECT *
//FROM information_schema.columns
//WHERE table_schema = 'public'
//  AND table_name   = 'Customers'
        $results = $thiq->_connection->query("show columns from $tableName");
        $fields = $results->fetchAll(\PDO::FETCH_OBJ);
        $metadata = new \Iris\DB\Metadata($tableName);
        foreach ($fields as $line) {
            $MetaItem = new \Iris\DB\MetaItem($line->Field);
            $MetaItem->setType($line->Type)
                    ->setPrimary($line->Key == 'PRI')
                    ->setNotNull($line->Null == 'NO')
                    ->setDefaultValue($line->Default)
                    // if field Extra contains 'auto_increment'
                    ->setAutoIncrement(strpos($line->Extra, 'auto_increment') !== \FALSE);
            if ($line->Key == 'PRI') {
                $metadata->addPrimary($line->Field);
            }
            $metadata->addItem($MetaItem);
        }
        return $metadata;
    }

    /*
     * Example of SHOW CREATE TABLE output
     * CONSTRAINT `ligne_ibfk_2` FOREIGN KEY (`facture_id`) REFERENCES `facture` (`id`),
     */

    public function getForeignKeys($tableName) {
        //@todo find a better way to do it !!!!
        //$pdo = $this->_connexion;
        $results = $this->_connexion->query("SHOW CREATE TABLE $tableName");
        $ligne = $results->fetchAll(\PDO::FETCH_ASSOC);
        $def = explode("\n", $ligne[0]['Create Table']);
        $tab = preg_grep('/FOREIGN KEY/i', $def);
        $foreignKeys = [];
        foreach ($tab as $line) {
            $line = str_replace('`', '', $line);
            $line = str_replace(' ', '', $line);
            preg_match_all('/\((.*?)\)/', $line, $keys);
            preg_match('/REFERENCES(.*)\(.*\)/i', $line, $table);
            //          ....!....!....!....!
            list($from, $to) = $keys[1];
            $table = $table[1];
            $foreignKey = new \Iris\DB\ForeignKey();
            $foreignKey->setTargetTable($table);
            $foreignKey->addKeys($from, $to);
            $foreignKeys[] = $foreignKey;
        }
        return $foreignKeys;
    }

    /**
     * Returns the table list of the database
     *
     * @return array
     */
    public function listTables($views = \TRUE) {
//        SELECT table_name FROM information_schema.tables where table_schema = 'public'
        $connexion = $this->getConnexion();
        $results = $connexion->query("SHOW FULL TABLES");
        $data = $results->fetchAll(\PDO::FETCH_NUM);
        $tables = [];
        foreach ($data as $line) {
            if ($views or $line[1] == 'BASE TABLE') {
                $tables[] = $line[0];
            }
        }
        return $tables;
    }

    public function lastInsertedId($entity) {
        return $this->_connexion->lastInsertId();
    }

    /**
     * bitXor not implmented in mySQL
     * 
     * @return string
     */
    public function bitXor() {
        throw new ie\NotSupportedException('XOR not supported in mySQL');
    }

    public function getLimitClause() {
        return ' LIMIT %d, %d';
    }

    public static function getDefaultSchema() {
        return self::$_DefaultSchema;
    }

    public static function setDefaultSchema($defaultSchema) {
        self::$_DefaultSchema = $defaultSchema;
    }

    public function getSchema() {
        return $this->_schema;
    }

    public function setSchema($schema) {
        $this->_schema = $schema;
    }

    protected static function _GetManagerName() {
        return self::POSTGRESQL;
    }

}
