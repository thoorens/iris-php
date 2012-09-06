<?php



namespace Iris\Engine;

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
 * An iterable extension of Memory. Not used currently.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * An iterable extension of Memory. Not used currently.
 * 
 */
class Warehouse extends Memory implements \Iterator {
    
    private static $_Warehouse = NULL;


    private $_indices = array();
    
    private $_pointer = NULL;
    
    
    /**
     *
     * @return Warehouse 
     */
    public static function GetInstance() {
        if (is_null(self::$_Warehouse)) {
            self::$_Warehouse = new Warehouse();
        }
        return self::$_Warehouse;
    }

    public function current() {
        if($this->valid()){
            return $this->_variables[$this->key()];
        }
        return NULL;
    }

    public function key() {
        if($this->valid()){
            return $this->_indices[$this->_pointer];
        }
        return NULL;
    }

    public function next() {
        $this->_pointer++;
    }

    public function rewind() {
        $this->_indices = array_keys($this->_variables);
        $this->_pointer = 0;
        
    }

    public function valid() {
        return $this->_pointer < count($this->_indices);
    }
    
    
    
}

?>
