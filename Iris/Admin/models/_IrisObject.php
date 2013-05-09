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
abstract class _IrisObject extends \Iris\DB\_Entity implements \Iris\Design\iDeletable{

    const DB_PARAM_FILE = "/config/base/adminparams.sqlite";

    protected static $_InsertionKeys;

    /**
     * This pragma enabled referential integrity
     * @var string 
     */
    protected static $_TableDefinition =
            'PRAGMA foreign_keys = ON;';

//    protected static function _AnalyseParameters($params) {
//        die('ToolBar ?');
//        $params = parent::_AnalyseParameters($params);
//        $params[self::ENTITYMANAGER] = $this->_getSystemEM();
//    }

        
    /**
     * Returns the EM for the system table database.
     * Its is in /application/config/admin/params.sqlite
     * 
     * @return \Iris\DB\_EntityManager 
     */
    protected static function _DefaultEntityManager() {
        $dbFile = IRIS_PROGRAM_PATH .self::DB_PARAM_FILE;
        $newBase = FALSE;
        if (!file_exists($dbFile)) {
            touch($dbFile);
            if (!file_exists($dbFile)) {
                throw new \Iris\Exceptions\FileException("$dbFile cannot be created (verify directory structure and file permissions.");
            }
            $newBase = TRUE;
        }
        $dsn = "sqlite:" . $dbFile;
        $EM = \Iris\DB\_EntityManager::EMFactory($dsn);
        if ($newBase) {
            // table creation
            $connexion = $EM->getConnexion();
            $connexion->exec(TModules::DDLText());
            $connexion->exec(TControllers::DDLText());
            $connexion->exec(TActions::DDLText());
            $connexion->exec(TAdmin::DDLText());
        }
        return $EM;
    }

    
    
    
    /**
     * Marks all the records of a table as deleted 
     * 
     * @param string $tableName
     */
    public function markDeleted($tableName, $id = \NULL) {
        $EM = $this->getEntityManager();
        if (is_null($id)) {
            $EM->directSQL("Update $tableName set Deleted = 1;");
        }
        else{
            throw new \Iris\Exceptions\NotSupportedException('Mark deleted has not been written to Delete individual records');
        }
            
    }

    /**
     * 
     * @param array $searchValues
     * @param array $newData
     */
    public function undeleteOrInsert($values) {
        $assoc = array_combine(static::$_InsertionKeys, $values);
        foreach ($assoc as $key => $value) {
            $this->where("$key=", $value);
        }
        // search object
        $object = $this->fetchRow();
        // if necessary create and init it
        if (is_null($object)) {
            $object = $this->createRow();
            foreach ($assoc as $key => $value)
                $object->$key = $value;
        }
        // in anay case, mark as not deleted
        $object->Deleted = \FALSE;
        $object->save();
    }

    /**
     * Returns the DDL creation command of an object in database.
     * 
     * @param string $name The object name
     * @return string
     */
    public static function DDLSpecial($name) {
        switch ($name) {
            // a view
            case 'modcont':
                $sql = <<<SQL
CREATE VIEW "modcont" AS 
select modules.Name, controllers.Name, controllers.Deleted
from modules inner join controllers
on modules.id = controllers.module_id
where modules.Deleted=0
order by 1,2;
SQL;
                break;
        }
    }

            

}
