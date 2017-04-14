<?php
namespace Iris\DB\Dialects;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Implementation of a mySQL entity manager
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Em_PDOmySQL extends \Iris\DB\Dialects\_Em_PDO {

    /**
     *
     * @var type 
     */
    protected $_portNumber;
    
    /**
     * Each _entityManager has its own type name
     */
    public static function __ClassInit() {
        static::$_TypeName = \Iris\DB\_EntityManager::MYSQL;
    }
    
    protected function __construct($dsn, $userName, $passwd, &$options = []) {
        // In the case of mySQL, it is necessary to add an option to have utf8 characters
        $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
        parent::__construct($dsn, $userName, $passwd, $options);
    }

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
        $results = $this->_connexion->query("show columns from $tableName");
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
        throw new \Iris\Exceptions\NotSupportedException('XOR not supported in mySQL');
    }

    public function getLimitClause() {
        return ' LIMIT %d, %d';
    }

    /**
     * In the case of mySQL, it is advisable to add an option to have utf8 characters
     * @param array $options
     */
    public function _addOption(&$options) {
        $options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
    }

    /**
     * 
     * @return string The name of the RDMS 
     */
    protected static function _GetManagerName() {
        return self::MYSQL;
    }

}
