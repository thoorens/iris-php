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

