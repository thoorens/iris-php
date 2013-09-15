<?php

namespace Iris\DB\Dialects;

use Iris\Exceptions as ie;

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
 * Implementation of an Oracle Entity Manager. 
 * IT IS NOT USABLE NOW.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Implementation of an Oracle Entity Manager. 
 * IT IS NOT USABLE NOW.
 * 
 */
class Em_PDOOracle extends \Iris\DB\Dialects\_Em_PDO {

    protected function __construct($dsn, $username = NULL, $passwd = NULL, $default = TRUE, &$options = NULL) {
        throw new ie\NotSupportedException('Not yet implemented');
        parent::__construct($dsn, '', '', $default, $options);
    }

    /** @todo need to be implemented */
    public function readFields($tableName) {
        throw new ie\NotSupportedException('Not yet implemented');
    }

    /**
     * 
     * @param type $tableName
     * @throws ie\NotSupportedException
     * @todo Need to be implemented
     */

    /** @todo need to be implemented */
    public function getForeignKeys($tableName) {
        throw new ie\NotSupportedException('Not yet implemented');
    }

    /** @todo need to be implemented */
    public function listTables() {
        throw new ie\NotSupportedException('Not yet implemented');
    }

    /** @todo need to be implemented */
    public function lastInsertedId($entity) {
        throw new ie\NotSupportedException('Not yet tested');
        $this->_connexion->lastInsertId();
    }

    /** @todo need to be implemented */
    public function bitAnd() {
        return ' BITAND(%s,%s) ';
    }

    /** @todo need to be implemented */
    public function bitOr() {
        throw new \Iris\Exceptions\NotSupportedException('BitOr not supportad by Oracle');
    }

    /** @todo need to be implemented */
    public function bitXor() {
        throw new \Iris\Exceptions\NotSupportedException('BitXor not supported by Oracle');
    }

}


