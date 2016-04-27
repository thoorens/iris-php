<?php

namespace Iris\DB\Dialects;

use Iris\Exceptions as ie;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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

    /**
     * By default, Oracle only recognise BITAND() function. To use
     * BITOR() or BITXOR(), the SGBD has to be extended as described in
     * https://community.oracle.com/thread/498773?start=0&tstart=0
     * To use these extended functions, the application must set
     * \DB\Dialects\Em_PDOOracle::$BitWiseExtensionEnabled = \TRUE
     * in a config file.
     * 
     * @var boolean
     */
    public static $BitWiseExtensionEnabled = \FALSE;

    protected function __construct($dsn, $userName, $passwd, &$options = []) {
        throw new ie\NotSupportedException('Not yet implemented');
        parent::__construct($dsn, $userName, $passwd, $options);
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

    /**
     * Returns a format string to manage bitwise AND operations
     *
     * @return sting
     */
    public function bitAnd() {
        return ' BITAND(%s,%s) ';
    }

    /**
     * Returns a format string to manage bitwise OR operations
     * 
     * @return string
     */
    public function bitOr() {
        if (self::$BitWiseExtensionEnabled) {
            return ' BITOR(%s,%s) ';
        }
        else {
            throw new \Iris\Exceptions\NotSupportedException('BitOr not supportad by default in Oracle');
        }
    }

    /**
     * Returns a format string to manage bitwise XOR operations
     * 
     * @return string
     */
    public function bitXor() {
        if (self::$BitWiseExtensionEnabled) {
            return ' BITXOR(%s,%s) ';
        }
        else {
            throw new \Iris\Exceptions\NotSupportedException('BitXor not supported by Oracle');
        }
    }

}
