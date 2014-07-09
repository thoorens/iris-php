<?php

namespace models;

use modules\db\controllers\_db as DB;

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
 * Small invoice manager for test purpose: the Customers table
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TMetadataModel extends \Iris\DB\_Entity {

    
    /**
     * Offers a way to define a table through a metadata. 3 ways to do it<ul>
     * <li> a string formated as in the example below (the output of the serialize() method). This
     * is the more plausible method.
     * <li> a metadata object made by hand
     * <li> a copy of the metadata from another table
     * </ul>
     * @param \Iris\DB\Metadata $metadata
     * @return type
     */
    protected function _readMetadata($metadata = NULL) {
        $NULL = \Iris\DB\MetaItem::S_NULL;
        $TRUE = \Iris\DB\MetaItem::S_TRUE;
        $FALSE = \Iris\DB\MetaItem::S_FALSE;
        switch (DB::FROM_STRING) {
            case DB::FROM_STRING:
                $string = <<<STRING
TABLE@customers 
FIELD@fieldName:id!type:INTEGER!size:0!defaultValue:!notNull:$TRUE!autoIncrement:$TRUE!primary:$TRUE!foreignPointer:$NULL 
FIELD@fieldName:Name!type:TEXT!size:0!defaultValue:!notNull:$TRUE!autoIncrement:$FALSE!primary:$FALSE!foreignPointer:$NULL 
FIELD@fieldName:Address!type:TEXT!size:0!defaultValue:!notNull:$TRUE!autoIncrement:$FALSE!primary:$FALSE!foreignPointer:$NULL 
FIELD@fieldName:Email!type:TEXT!size:0!defaultValue:!notNull:$TRUE!autoIncrement:$FALSE!primary:$FALSE!foreignPointer:$NULL 
PRIMARY@id
AUTOPK@$TRUE
STRING;
                $metadata = new \Iris\DB\Metadata();
                $metadata->unserialize($string);
                break;
            case DB::FROM_OBJECT:
                $metadata = DB::GetSampleMetadata(DB::FROM_OBJECT);
                break;
            case DB::FROM_TABLE:
                $metadata = DB::GetSampleMetadata(DB::FROM_TABLE);
                break;
        }
        return $metadata;
    }

}

