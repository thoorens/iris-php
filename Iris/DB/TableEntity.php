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
 * A special entity for a database table. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TableEntity extends _Entity {

    /**
     * Creates or finds an entity corresponding to the parameters given. $string exclusive_or $metadata must be provided
     * 
     * @param string $table A table name
     * @param string/Metadata $metadata An optional Metadata definition as an object or a serialized string (opt)
     * @param _EntityManager $entityManager An optional entity manager (if not provided, the default EM is used)
     * @return _Entity
     * @throws \Iris\Exceptions\EntityException
     */
    public static function GetEntity() {
        $entityBuilder = new EntityBuilder(get_called_class(), func_get_args());
        return $entityBuilder->createTable();
    }

}

