<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An iterable extension of Memory. Not used currently.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
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


