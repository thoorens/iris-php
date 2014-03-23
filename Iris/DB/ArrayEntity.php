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
 * An array entity is a sort of ViewEntity with the data contained in 
 * internal arrays
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ArrayEntity extends ViewEntity {

    protected $_data = [];
    
    protected $_fields = [];
    
    protected $_idNames = ['id'];
    
    public function find($id) {
        if (!isset($this->_data[$id])) {
            return \NULL;
        }
        $data = $this->_data[$id];
        if(count($data) == count($this->_fields) - 1){
            array_unshift($data, $id);
        }
        $object = new \Iris\DB\Object($this, [$id], array_combine($this->_fields, $data));
        return $object;
    }

    protected function _readMetadata($metadata = \NULL) {
        $metadata = new \Iris\DB\Metadata();
        foreach ($this->_fields as $field) {
            $metadata->addItem(new \Iris\DB\MetaItem($field));
        }
        foreach($this->_idNames as $id){
            $metadata->addPrimary($id);
        }
        return $metadata;
    }

}
