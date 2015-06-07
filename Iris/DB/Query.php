<?php

namespace Iris\DB;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An internal representation of a SQL query which can be
 * interactively modified before rendering parts of it
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Query {

    const LIKE = 1;
    const BEGINS = 2;
    const ENDS = 3;
    const CONTENTS = 4;

    /**
     * the fields for the SELECT clause
     * @var string[]
     */
    protected $_selectedFields;

    /**
     * The values to be bind to the prepared statement
     * in same order that _fieldPlaceHolders
     * @var mixed[]
     */
    protected $_fieldValues;

    /**
     * the fields in WHERE/INSERT/SET clause
     * @var string[] 
     */
    protected $_preparedFields;

    /**
     * the content of the ORDER clause in query
     * @var string 
     */
    protected $_order = '';

    /**
     * The place holders for the fields in the prepared statement
     * @var string[] 
     */
    protected $_fieldPlaceHolders;

    /**
     * an incremental number to mark tokens in statements 
     * @var int 
     */
    protected $_tokenNumber;
    protected $_extended;

    /**
     * @var array The values used by the limit clause : ex. [offset, last] or \NULL
     */
    protected $_limits = \NULL;

    /**
     * The constructor uses reset to init all parameters
     */
    public function __construct() {
        $this->reset();
    }

    /**
     * Reset all parameters to use entity from scratch (on construction or after a request)
     */
    public function reset() {
        $this->_selectedFields = '*';
        $this->_fieldValues = [];
        $this->_preparedFields = [];
        $this->_order = '';
        $this->_fieldPlaceHolders = [];
        $this->_tokenNumber = 0;
        $this->_extended = FALSE;
        $this->_limits = \NULL;
    }

    /**
     * 
     * @param string[] $fields
     */
    public function select($fields) {
        if ($this->_selectedFields == '*') {
            $this->_selectedFields = [];
        }
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->select($field);
            }
        }
        else {
            $this->_selectedFields[] = $fields;
        }
    }

    public function selectDistinct($fields) {
        if (is_array($fields)) {
            foreach ($fields as $field) {
                $this->selectDistinct($field);
            }
        }
        else {
            $this->select("DISTINCT $fields");
        }
    }

    /**
     * Add a pair condition (field + comparator) - value
     * to the where clause
     * 
     * @param mixde $condition
     * @param mixed $value
     */
    public function where($condition, $value = NULL) {
        // in case of multiple primary key (compatibility)
        if (is_array($condition)) {
            return $this->wherePairs($condition);
        }
        // textual where (compatibility)
        if (is_null($value)) {
            return $this->whereClause($condition);
        }
        $this->_prepareField($value, $condition);
    }

    /**
     * Add an explicit part to where clause
     * @param string $condition
     */
    public function whereClause($condition, $direct = \FALSE) {
        if ($this->_extended and $direct) {
            throw new \Iris\Exceptions\DBException('WhereClause is not compatible with logical operators NOT, AND and OR.');
        }
        $this->_preparedFields[] = $condition;
    }

    /**
     * Add a LIKE part to where clause (with possible variant)
     * 
     * @param String $field
     * @param String $value
     * @param int $mode : par défaut on prend la chaîne comme elle est
     */
    public function whereLike($field, $value, $mode = Query::LIKE) {
        switch ($mode) {
            case Query::BEGINS:
                $value = "$value%";
                break;
            case Query::ENDS:
                $value = "%$value";
                break;
            case Query::CONTENTS:
                $value = "%$value%";
                break;
        }
        $this->where("$field LIKE ", $value);
    }

    public function whereBetween($field, $value1, $value2) {
        $tokenNumber1 = ++$this->_tokenNumber;
        $tokenNumber2 = ++$this->_tokenNumber;
        $this->_preparedFields[] = "$field BETWEEN :P$tokenNumber1 AND :P$tokenNumber2 ";
        $this->_fieldPlaceHolders[":P$tokenNumber1"] = $value1;
        $this->_fieldPlaceHolders[":P$tokenNumber2"] = $value2;
    }

    public function whereIn($field, $values) {
        $index = ++$this->_tokenNumber;
        foreach ($values as $value) {
            $tokenNumber[$index] = ":P$index";
            $this->_fieldPlaceHolders[$tokenNumber[$index++]] = $value;
        }
        $list = implode(',', $tokenNumber);
        $this->_preparedFields[] = "$field IN($list)";
    }

    public function _And_() {
        $this->whereClause('AND');
        $this->_extended = TRUE;
    }

    public function _Or_() {
        $this->whereClause('OR');
        $this->_extended = TRUE;
    }

    public function _Not_() {
        $this->whereClause('NOT');
        $this->_extended = TRUE;
    }

    protected function _whereBit($sql, $field, $bits) {
        $bits = 0 + $bits;
        if (!is_int($bits)) {
            throw new \Iris\Exceptions\DBException('Bit pattern must be an integer');
        }
        $this->_preparedFields[] = sprintf($sql, $field, $bits);
    }

    public function whereBitAnd($field, $bits, $entityManager) {
        $sql = $entityManager->bitAnd();
        $this->_whereBit($sql, $field, $bits);
    }

    public function whereBitOr($field, $bits, $entityManager) {
        $sql = $entityManager->bitOr();
        $this->_whereBit($sql, $field, $bits);
    }

    public function whereBitXor($field, $bits, $entityManager) {
        $sql = $entityManager->bitXor();
        $this->_whereBit($sql, $field, $bits);
    }

    public function renderWhere() {
        if ($this->_extended) {
            $sql = $this->_recWhere($this->_preparedFields);
        }
        else {
            $sql = $this->_tokenList(' AND ');
        }
        if ($sql != '') {
            return " WHERE $sql";
        }
        else {
            return '';
        }
    }

    protected function _recWhere(&$stack) {
        if (count($stack) > 1) {
            $top = array_pop($stack);
            $this->_recWhere($stack);
            if ($top == 'NOT') {
                if (count($stack) < 1) {
                    throw New \Iris\Exceptions\DBException('Unbalanced NOT in Where expression');
                }
                $operand = array_pop($stack);
                array_push($stack, "NOT($operand)");
            }
            elseif ($top == 'AND') {
                if (count($stack) < 2) {
                    throw New \Iris\Exceptions\DBException('Unbalanced AND in Where expression');
                }
                $operand1 = array_pop($stack);
                $operand2 = array_pop($stack);
                array_push($stack, "($operand2)AND($operand1)");
            }
            elseif ($top == 'OR') {
                if (count($stack) < 2) {
                    throw New \Iris\Exceptions\DBException('Unbalanced OR in Where expression');
                }
                $operand1 = array_pop($stack);
                $operand2 = array_pop($stack);
                array_push($stack, "($operand2)OR($operand1)");
            }
            else {
                array_push($stack, $top);
            }
        }
        return $stack[0];
    }

    public function renderInsert($insertFields, $values) {
        if (count($values)) {
            $insertClause = implode(',', $insertFields);
            foreach ($values as $value) {
                $this->_prepareField($value);
            }
        }
        return array($insertClause, $this->_tokenList());
    }

    /**
     * Rendering the order part of the query
     * 
     * @return string Soit vide soit texte ORDER BY ....
     */
    public function renderOrder() {
        if ($this->_order == '') {
            return '';
        }
        else {
            return ' ORDER BY ' . $this->_order;
        }
    }

    /**
     * Tries to produce a Limit clause if the operating system can do it
     * 
     * @param type $limitClause the limit clause with %d for the two argument
     * @return type
     * @throws \Iris\Exceptions\DBException
     */
    public function renderLimits($limitClause) {
        $limits = $this->_limits;
        if (is_null($limits)) {
            $sql = '';
        }
        else {
            list($limit, $offset) = $limits;
            if (is_null($limitClause)) {
                throw new \Iris\Exceptions\DBException('Your RDBMS does not support the LIMIT clause.');
            }
            $sql = sprintf($limitClause, $offset, $limit);
        }
        return $sql;
    }

    /**
     * Produces the SET clause in an update
     * 
     * @param array $setFields the fields name in an array
     * @param array $values the new values corresponding to the different fields
     * @return string
     */
    public function renderSet($setFields, $values) {
        $pointer = 0;
        $sets = [];
        foreach ($values as $value) {
            if ($value == Object::NULL_VALUE) {
                $sets[] = $setFields[$pointer++] . " = NULL ";
            }
            else {
                $this->_prepareField($value);
                $prepareField = array_shift($this->_preparedFields);
                $sets[] = $setFields[$pointer++] . " = " . $prepareField;
            }
        }
        $set = implode(',', $sets);
        return $set;
    }

    public function setOrder($orderClause) {
        $this->_order = $orderClause;
    }

    /**
     * 
     * @return string 
     */
    public function renderSelectFields() {
        if ($this->_selectedFields == '*') {
            return "*";
        }
        else {
            return implode(',', $this->_selectedFields);
        }
    }

    public function getPlaceHolders() {
        return $this->_fieldPlaceHolders;
    }

    /**
     *
     * @param type $value
     * @param type $condition
     * @return _Entity 
     */
    protected function _prepareField($value, $condition = '') {
        if ($value === Object::NULL_VALUE) {
            $this->_preparedFields[] = 'NULL';
        }
        else {
            $tokenNumber = ++$this->_tokenNumber;
            $this->_preparedFields[] = "$condition :P$tokenNumber";
            $this->_fieldPlaceHolders[":P$tokenNumber"] = $value;
        }
    }

    private function _tokenList($sep = ',') {
        $where = $this->_preparedFields;
// Reset values for multi use
        $this->_preparedFields = [];
        return implode($sep, $where);
    }

    /**
     * Permits to specify the parameters of a LIMIT clause
     * 
     * @param int $limit the value of the last clause
     * @param int $offset
     */
    public function limit($limit, $offset) {
        $this->_limits = [$limit, $offset];
    }

}
