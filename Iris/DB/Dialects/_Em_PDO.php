<?php
namespace Iris\DB\Dialects;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
     * @var \PDO (not only)
     */
    protected $_connexion = NULL;

    
     /**
     * database default port number
     * @var int
     */
    protected static $_DefaultPortNumber = \NULL;
    
    
    protected $_portNumber;
    
    /**
     * The constructor for PDO entity manager.
     *
     * @param String $dsn : Data Source Name
     * @param String $userName : user login name
     * @param String $passwd : user password
     * @param mixed[] $options additional options
     */
    protected function __construct($dsn, $userName, $passwd, &$options = []) {
        $this->setPortNumber(static::$_DefaultPortNumber);
        try {
            $this->_addOption($options);
            $pdo = new \PDO($dsn, $userName, $passwd, $options);
            $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, array('\Iris\DB\Dialects\MyPDOStatement', array($this)));
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->_connexion = $pdo;
            
        }
        catch (\PDOException $exp) {
            $message = "DB error : " . $exp->getMessage() . " dsn: $dsn ";
            /**
             * @todo Explain this gap between the two type of exception
             */
            $code = 0; // Strangely, \PDOException codes are strings and \Exception long
            $myException = new \Iris\Exceptions\DBException($message, 0, $exp);
            throw $myException;
        }
        catch (\Exception $exp) {
            throw $exp;
        }
        parent::__construct($dsn, $userName, $passwd, $options);
    }

    /**
     * PDO gets its connexions from a protected variable
     *
     * @return \PDO
     */
    public function getConnexion() {
        return $this->_connexion;
    }

    /**
     *
     * @param String $sql
     * @param string[] $fieldsPH
     * @return array
     */
    public function getResults($sql, $fieldsPH = []) {
        $statement = $this->_connexion->prepare($sql);
        foreach ($fieldsPH as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Executes a direct SQL query on the connexion
     *
     * @param type $sql
     * @return \PDOStatement
     */
    public function directSQLQuery($sql) {
        return $this->_connexion->query($sql);
    }

    /**
     * Executes a direct SQL exec on the connexion
     * @param type $sql
     * @return int (a true boolean or an integer according to the type of command
     */
    public function directSQLExec($sql) {
        return $this->_connexion->exec($sql);
    }

    public function exec($sql, $fieldsPH) {
        $statement = $this->_connexion->prepare($sql);
        foreach ($fieldsPH as $key => $value) {
            $statement->bindValue($key, $value);
        }
        return $statement->execute();
    }

    /**
     * Some databases have a portNumber
     * @param int $portNumber
     */
    public static function SetDefaultPortNumber($portNumber) {
        static::$_DefaultPortNumber = $portNumber;
    }

    /**
     * An read accessor for the used port number
     * @return int
     */
    public function getPortNumber() {
        return $this->_portNumber;
    }

    public function setPortNumber($portNumber) {
        $this->_portNumber = $portNumber;
    }

    /**
     * If necessary a special option may be added
     * @param array $options
     */
    public function _addOption(&$options) {
        
    }

}

