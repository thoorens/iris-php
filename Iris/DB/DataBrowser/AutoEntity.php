<?php



namespace Iris\DB\DataBrowser;

use Iris\Exceptions\DBException;
use Iris\DB\_Entity;

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
 * Permits to create an instance of _Entity without the need of an explicit class
 * and class file
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class AutoEntity extends _Entity {

    private static $_NewFlag = FALSE;
    
    /**
     * Permits to specify a EM for all autoentities
     * 
     * @var \Iris\DB\_EntityManager
     */
    public static $EntityManager = \NULL;

    /**
     * Constructor does not be called directly, use static method EntityBuilder instead.
     * 
     * @param \Iris\DB\_EntityManager $EM The needed entity manager (if NULL, will take the default one)
     */
    public function __construct($EM = NULL) {
        if (!self::$_NewFlag) {
            throw new DBException('AutoEntity must be created by the static method "EntityBuilder".');
        }
    }

    /**
     * Creates an entity knowing its name, primary key (by def id) and Entity manager
     *  
     * @param string $tableName The table name
     * @param array $idNames an array with the primary key field names
     * @param \Iris\DB\_EntityManager $EM The entity manager to use (if not specified, takes one by default) 
     * @return AutoEntity 
     */
    public static function EntityBuilder($tableName, $idNames = array('id'), $EM = NULL) {
        $path = \Iris\DB\_EntityManager::$entityPath;
        $class = "$path\\T".ucfirst($tableName);
        $loader = \Iris\Engine\Loader::GetInstance();
        // if a class exists, take it
        if($loader->loadClass($class, \FALSE)){
            return new $class($EM);
        }
        // otherwise, it is necessary to use an Autoentity
        self::$_NewFlag = TRUE;
        // If no entity manager is specified, tries to use a specific for Autoentities (otherwise, it will take the default one)
        if(is_null($EM) and ! is_null(self::$EntityManager)){
            $EM = self::$EntityManager;
        }
        $entity = new AutoEntity($EM);
        $entity->setEntityName($tableName);
        $entity->setIdNames($idNames);
        $entity->_initialize($EM);
        self::$_NewFlag = FALSE;
        return $entity;
    }

}

?>
