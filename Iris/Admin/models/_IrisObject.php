<?php

namespace Iris\Admin\models;

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
 */

/**
 * A special type of _Entity, creating a sqlite database if necessary
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _IrisObject extends \Iris\DB\_Entity {
    const DB_PARAM_FILE = "admin/params.sqlite";

    /**
     * This pragma enabled referential integrity
     * @var string 
     */
    protected static $_TableDefinition =
            'PRAGMA foreign_keys = ON;';

    /**
     * The constructor tries to create the database if necessary
     * 
     * @param \Iris\DB\_EntityManager $EM 
     */
    public function __construct($EM=NULL) {
        if (is_null($EM)) {
            $EM = $this->_getSystemEM();
        }
        parent::__construct($EM);
    }

    /**
     * Returns the EM for the system table database
     * Its is in/program/config/admin/params.sqlite
     * 
     * @return \Iris\DB\_EntityManager 
     */
    protected function _getSystemEM($normal=TRUE) {
        $configPath = IRIS_PROGRAM_PATH . "/config";
        if ($normal === TRUE) {
            $db = "$configPath/" . self::DB_PARAM_FILE;
        } else {
            $db = "$configPath/" . $normal;
        }
        $newBase = FALSE;
        if (!file_exists($db)) {
            touch($db);
            if (!file_exists($db)) {
                throw new \Iris\Exceptions\FileException("$db cannot be created (verify directory structure and file permissions.");
            }
            $newBase = TRUE;
        }
        $dsn = "sqlite:" . $db;
        $EM = \Iris\DB\_EntityManager::EMFactory($dsn);
        if ($newBase) {
            // table creation
            $connexion = $EM->getConnexion();
            $connexion->exec(self::$_TableDefinition);
            $connexion->exec(TModules::$_TableDefinition);
            $connexion->exec(TControllers::$_TableDefinition);
            $connexion->exec(TActions::$_TableDefinition);
        }
        $tables = $EM->listTables();
        return $EM;
    }

}

?>
