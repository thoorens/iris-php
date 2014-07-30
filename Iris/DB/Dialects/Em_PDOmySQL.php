<?php

namespace Iris\DB\Dialects;

use Iris\Exceptions as ie;

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
 * @copyright 2011-2014 Jacques THOORENS
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
     * In the case of mySQL, it is necessary to add an option to have ytf8 characters
     *
     * @var array
     */
    protected static $_Options = array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

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
        $pdo = $this->_connexion;
        $results = $pdo->query("show columns from $tableName");
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
        $pdo = $this->_connexion;
        $results = $pdo->query("SHOW CREATE TABLE $tableName");
        $ligne = $results->fetchAll(\PDO::FETCH_ASSOC);
        $def = explode("\n", $ligne[0]['Create Table']);
        $tab = preg_grep('/FOREIGN KEY/i', $def);
        $foreignKeys = array();
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
        throw new ie\NotSupportedException('XOR not supported in mySQL');
    }

    
    public function getLimitClause() {
        return ' LIMIT %d, %d';
    }

}

