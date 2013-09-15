<?php

namespace Iris\DB\Dialects;

use Iris\DB\Object,
    Iris\DB\_Entity;

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
 * This class creates PDO entity manager based on DSN, UserName and 
 * Password. It must overridden to access concrete RDBMS.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Em_PDO extends \Iris\DB\_EntityManager {
    
    /**
     * PDO manages the connexion by using a variable
     * 
     * @var PDO (not only) 
     */
    protected $_connexion = NULL;


    /**
     * The constructor for PDO entity manager.
     * 
     * @param String $dsn : Data Source Name
     * @param String $username : user login name
     * @param String $passwd : user password
     * @param boolean $default : if TRUE store this EM as default
     * @param array $options additional options
     */
    protected function __construct($dsn, $username, $passwd, &$options = \NULL) {
        parent::__construct($dsn, $username, $passwd, $options);
        try {
            $pdo = new \PDO($dsn, $username, $passwd, $options);
            $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array('\Iris\DB\Dialects\MyPDOStatement', array($this)));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_connexion = $pdo;
        }
        catch (\PDOException $exp) {
            $message = "DB error : ".$exp->getMessage()." dsn: $dsn " ;
            $code = 0; // Strangely, \PDOException codes are strings and \Exception long
            $myException = new \Iris\Exceptions\DBException($message, 0, $exp);
            throw $myException;
        }
        catch(\Exception $exp){
            throw $exp;
        }
    }

    /**
     * PDO gets its connexions from a protected variable
     * 
     * @return PDO 
     */
    public function getConnexion() {
        return $this->_connexion;
    }

    /**
     *
     * @param String $sql
     * @param array $fieldsPH
     * @return array  
     */
    public function getResults($sql, $fieldsPH=array()) {
        $pdo = $this->_connexion;
        $statement = $pdo->prepare($sql);
        foreach ($fieldsPH as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    public function exec($sql, $fieldsPH) {
        $pdo = $this->_connexion;
        $statement = $pdo->prepare($sql);
        foreach ($fieldsPH as $key => $value) {
            $statement->bindValue($key, $value);
        }
        return $statement->execute();
    }

}


