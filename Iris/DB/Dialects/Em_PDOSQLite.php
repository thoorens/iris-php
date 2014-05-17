<?php

namespace Iris\DB\Dialects;

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
 * Implementation of a SQLite entity manager
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Em_PDOSQLite extends \Iris\DB\Dialects\_Em_PDO {

    /**
     * SQLite uses a file as a container
     * 
     * @var string 
     */
    private $_fileName;

    /*
     * INTEGER
     * BOOL
     * REAL
     * DOUBLE
     * FLOAT
     * CHAR
     * TEXT
     * VARCHAR
     * BLOB
     * NUMERIC
     * DATETIME
     */

    /**
     * The constructor add default values to all parameters but one
     * and correct the database filename if it is relative
     * 
     * @param String $dsn : Data Source Name
     * @param String $userName : user login name (not used here)
     * @param String $passwd : user password (not used here)
     * @param mixed[] $options additional options
     */
    protected function __construct($dsn, $userName = \NULL, $passwd = \NULL, &$options = []) {
        list($adapter, $fileName) = explode(':', $dsn);
        $fullFileName = self::FullPathName($fileName);
        if (!file_exists($fullFileName) and \Iris\SysConfig\Settings::HasSqliteCreateMissingFile()) {
            self::CreateFile($fullFileName);
        }
        $this->_fileName = $fullFileName;
        $dsn = "$adapter:$fullFileName";
        parent::__construct($dsn, $userName, $passwd, $options);
    }

    public function getFileName() {
        return $this->_fileName;
    }

    /**
     * Returns a full path for a given file name
     * 
     * @param string $fileName
     * @return string
     */
    public static function FullPathName($fileName){
        if ($fileName[0] != '/') {
            $fullName = IRIS_ROOT_PATH . '/' . $fileName;
        }
        else{
            $fullName = $fileName;
        }
        return $fullName;
    }
    

    public static function CreateFile($fileName){
        $fullFileName = self::FullPathName($fileName);
        if(!file_exists($fullFileName)){
            touch($fullFileName);
        }
    }
    
    public static function PurgeFile($fileName){
        $fullFileName = self::FullPathName($fileName);
        if(file_exists($fullFileName)){
            unlink($fullFileName);
        }
        
    }
    
    /**
     * SQLite manages Dsn differently and override the _GetDsn method.
     * 
     * @param type $param
     * @return type 
     */
    protected static function _GetDsn($param) {
        $fileName = $param->database_dbname;
        return sprintf('%s:%s', $param->database_adapter, $fileName);
    }

    /* PRAGMA TABLE_INFO output example 
      object(stdClass)#44 (6) {
      ["cid"]=> string(1) "0"
      ["name"]=> string(2) "id"
      ["type"]=> string(7) "INTEGER"
      ["notnull"]=> string(1) "1"
      ["dflt_value"]=> NULL
      ["pk"]=> string(1) "1"
      }
     */

    /**
     * 
     * @param string $tableName
     * @return \Iris\DB\Metadata
     */
    public function readFields($tableName) {
        $sql = "PRAGMA table_info($tableName)";
        $results = $this->directSQLQuery($sql);
        $results->setFetchMode(\PDO::FETCH_OBJ);
        $metadata = new \Iris\DB\Metadata($tableName);
        foreach ($results as $line) {
            $MetaItem = new \Iris\DB\MetaItem($line->name);
            $MetaItem->setType($line->type)
                    ->setPrimary($line->pk == 1)
                    ->setNotNull($line->notnull == 1)
                    ->setDefaultValue($line->dflt_value);
            if ($line->pk) {
                try {
                    $metadata->addPrimary($line->name);
                    // exception if no defined sequence in the database
                    $results = $this->directSQLQuery("select * from sqlite_sequence where name ='$tableName';");
                    $lines = $results->fetchall();
                    if (count($lines) == 1) {
                        $MetaItem->setAutoIncrement();
                    }
                }
                catch (\PDOException $exc) {
                    // ok no sqlite_sequence 
                }
            }
            $metadata->addItem($MetaItem);
        }
        return $metadata;
    }

    /* PRAGMA FOREIGN_KEY_LIST output example
      object(stdClass)#47 (8) {
      ["id"]=> string(1) "0"
      ["seq"]=> string(1) "0"
      ["table"]=> string(7) "modules"
      ["from"]=> string(9) "module_id"
      ["to"]=> string(2) "id"
      ["on_update"]=> string(9) "NO ACTION"
      ["on_delete"]=> string(9) "NO ACTION"
      ["match"]=> string(4) "NONE"
      }
     */

    public function getForeignKeys($tableName) {
        $pdo = $this->_connexion;
        $results = $pdo->query("PRAGMA foreign_key_list($tableName)");
        $results->setFetchMode(\PDO::FETCH_OBJ);
        $foreignKeys = array();
        foreach ($results as $line) {
            $id = $line->id;
            $seq = $line->seq;
            if ($seq == 0) {
                $foreignKey = new \Iris\DB\ForeignKey();
                $foreignKeys[$id] = $foreignKey;
            }
            else {
                $foreignKey = $foreignKeys[$id];
            }
            $foreignKey->setTargetTable($line->table);
            $foreignKey->addFromKey($line->from);
            $foreignKey->addToKey($line->to);
        }
        return $foreignKeys;
    }

    /**
     * Returns the table list of the database
     * 
     * @parameter boolean $views if false does not list views
     * @return array
     */
    public function listTables($views = \TRUE) {
        $connexion = $this->getConnexion();
        $noviews = $views ? '' : "type = 'table' and ";
        $results = $connexion->query("SELECT name FROM sqlite_master WHERE $noviews name not like 'sqlite_%'");
        $data = $results->fetchAll(\PDO::FETCH_OBJ);
        $tables = array();
        foreach ($data as $line) {
            $tables[] = $line->name;
        }
        return $tables;
    }

    /**
     * 
     * @param type $entity
     * @return mixed
     */
    public function lastInsertedId($entity) {
        return $this->_connexion->lastInsertId();
    }

    public function bitAnd() {
        throw new \Iris\Exceptions\NotSupportedException('BitAnd not implemented');
    }

    public function bitOr() {
        throw new \Iris\Exceptions\NotSupportedException('BitAnd not implemented');
    }

    public function bitXor() {
        throw new \Iris\Exceptions\NotSupportedException('BitAnd not implemented');
    }

    
    /**
     * Creates a LIMIT clause for SQLite, in mysql syntax. The syntax with
     * OFFSET keyword is not uses.
     * 
     * @return string
     */
    public function getLimitClause() {
        return ' LIMIT %d , %d';
    }
    
}

