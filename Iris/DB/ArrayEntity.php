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
        foreach($this->getIdNames() as $id){
            $metadata->addPrimary($id);
        }
        return $metadata;
    }

}
