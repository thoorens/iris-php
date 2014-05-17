<?php

namespace Iris\DB;

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
    protected $_limits = \NULL;

    public function __construct() {
        $this->reset();
    }

    /**
     * Reset all parameters to use entity a second time
     */
    public function reset() {
        $this->_selectedFields = '*';
        $this->_fieldValues = array();
        $this->_preparedFields = array();
        $this->_order = '';
        $this->_fieldPlaceHolders = array();
        $this->_tokenNumber = 0;
        $this->_extended = FALSE;
        $this->_limits = \NULL;
    }

    public function select($fields) {
        if ($this->_selectedFields == '*') {
            $this->_selectedFields = array();
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
        if (!is_int($bits)) {
            throw new ie\DBException('Bit pattern must be an integer');
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
                if (count($stack) < 1)
                    throw New \Iris\Exceptions\DBException('Unbalanced NOT in Where expression');
                $operand = array_pop($stack);
                array_push($stack, "NOT($operand)");
            }
            elseif ($top == 'AND') {
                if (count($stack) < 2)
                    throw New \Iris\Exceptions\DBException('Unbalanced AND in Where expression');
                $operand1 = array_pop($stack);
                $operand2 = array_pop($stack);
                array_push($stack, "($operand2)AND($operand1)");
            }
            elseif ($top == 'OR') {
                if (count($stack) < 2)
                    throw New \Iris\Exceptions\DBException('Unbalanced OR in Where expression');
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

    public function renderOrder() {
        if ($this->_order == '') {
            return '';
        }
        else {
            return ' ORDER BY ' . $this->_order;
        }
    }

    public function renderLimits($syntax) {
        $limits = $this->_limits;
        if(is_null($limits)){
            $sql = '';
        }
        else{
            list($limit, $offset) = $limits;
            if(is_null($syntax)){
                throw new \Iris\Exceptions\DBException('Your RDBMS does not support the LIMIT clause.');
            }
            $sql = sprintf($syntax, $offset, $limit);
        }
        return $sql;
    }
    
    public function renderSet($setFields, $values) {
        $pointer = 0;
        $sets = array();
        foreach ($values as $value) {
            $this->_prepareField($value);
            $prepareField = array_shift($this->_preparedFields);
            $sets[] = $setFields[$pointer++] . " = " . $prepareField;
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
        if ($this->_selectedFields == '*')
            return "*";
        else
            return implode(',', $this->_selectedFields);
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
        $tokenNumber = ++$this->_tokenNumber;
        $this->_preparedFields[] = "$condition :P$tokenNumber";
        $this->_fieldPlaceHolders[":P$tokenNumber"] = $value;
    }

    private function _tokenList($sep = ',') {
        $where = $this->_preparedFields;
// Reset values for multi use
        $this->_preparedFields = array();
        return implode($sep, $where);
    }

    public function limit($limit, $offset) {
        $this->_limits = [$limit, $offset];
    }

    

}


