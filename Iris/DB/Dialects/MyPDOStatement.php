<?php

namespace Iris\DB\Dialects;

use Iris\DB\Object,
    Iris\DB\_Entity;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class extends PDOStatement to provide a mean of seing the actual
 * SQL string sent to the database for debugging purpose. It is adapted from a class found on a 
 * forum. My adaptation concerns the replacement of logQuery by its integration
 * in the Iris Log system.
 *  
 * @author Unknow (Yosh on http://forum.phpfrance.com)
 * @see http://forum.phpfrance.com/php-oriente-objet/pdo-query-execute-t252754.html
 * 
 * 
 */
// The original class:
//
//class myPDOStatement extends PDOStatement {
//		private $preBindingStatement;
//		private function __construct($stmt) {
//    	$this->stmt = $stmt;
//			$this->preBindingStatement = $this->queryString;
//    }
//		public function bindParam($stmt_parameter, $stmt_value, $dataType = PDO::PARAM_STR) {        
//        $this->preBindingStatement = str_replace("{$stmt_parameter}", "'{$stmt_value}'", $this->preBindingStatement);
//				return parent::bindParam($stmt_parameter, $stmt_value, $dataType);
//    }
//		private function logQuery() {
//			echo "<div style=\"background-color:#5298d4; color:#FFFFFF; margin:10px; padding:5px\"><strong>DEBUG:</strong><hr>".$this->preBindingStatement."</div>";
//		}
//		public function execute($input_parameters = []) {
//			
//			if(count($input_parameters)>0) {
//				$this->preBindingStatement = $this->queryString;
//				foreach($input_parameters as $stmt_parameter => $stmt_value) {
//					$this->preBindingStatement = str_replace("{$stmt_parameter}", "'{$stmt_value}'", $this->preBindingStatement);
//				}
//				$result = parent::execute($input_parameters);
//			} else {
//				$result = parent::execute();
//			}			
//			if(DEBUG) {
//				$this->logQuery();
//			}
//			return $result;
//		}
//	}

class MyPDOStatement extends \PDOStatement {

    private $_preBindingStatement;
    public static $LastSQL = \NULL;

    private function __construct($stmt) {
        $this->stmt = $stmt;
        $this->_preBindingStatement = $this->queryString;
    }

    public function bindParam($stmt_parameter, &$stmt_value, $dataType = \PDO::PARAM_STR, $length = NULL, $driver_options = NULL) {
        $this->_preBindingStatement = str_replace("{$stmt_parameter}", "'{$stmt_value}'", $this->_preBindingStatement);
        return parent::bindParam($stmt_parameter, $stmt_value, $dataType);
    }

    public function bindValue($stmt_parameter, $stmt_value, $dataType = \PDO::PARAM_STR, $length = NULL, $driver_options = NULL) {
        $this->_preBindingStatement = str_replace("{$stmt_parameter}", "'{$stmt_value}'", $this->_preBindingStatement);
        return parent::bindValue($stmt_parameter, $stmt_value, $dataType);
    }

//        private function logQuery() {
//            print "<div style=\"background-color:#5298d4; color:#FFFFFF; margin:10px; padding:5px\">
//                <strong>DEBUG:</strong><hr>" . $this->preBindingStatement . "</div>";
//        }

    public function execute($input_parameters = []) {
        if (count($input_parameters) > 0) {
            $this->_preBindingStatement = $this->queryString;
            foreach ($input_parameters as $stmt_parameter => $stmt_value) {
                $this->_preBindingStatement = str_replace("{$stmt_parameter}", "'{$stmt_value}'", $this->_preBindingStatement);
            }
            \Iris\Log::Debug($this->_preBindingStatement, \Iris\Engine\Debug::DB, 'SQL');
            $result = parent::execute($input_parameters);
        }
        else {
            \Iris\Log::Debug($this->_preBindingStatement, \Iris\Engine\Debug::DB, 'SQL');
            $result = parent::execute();
        }

//            if (DEBUG) {
//                $this->logQuery();
//            }

        self::$LastSQL = $this->_preBindingStatement;
        return $result;
    }

}

