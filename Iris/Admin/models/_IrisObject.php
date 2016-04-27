<?php
namespace Iris\Admin\models;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A special type of _Entity, creating a sqlite database if necessary
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _IrisObject extends \Iris\DB\_Entity implements \Iris\Design\iDeletable {

    protected static $_InsertionKeys;

    protected static $_EntityNumber = 999;

    /**
     * This pragma enabled referential integrity
     * @var string 
     */
    protected static $_TableDefinition = 'PRAGMA foreign_keys = ON;';

    /**
     * Returns the EM for the system table database.
     * Its is usually in /application/config/admin/params.sqlite
     * If necessary a new database will be created
     * 
     * @return \Iris\DB\_EntityManager 
     */
    public static function DefaultEntityManager() {
        $dbFile = IRIS_PROGRAM_PATH . \Iris\SysConfig\Settings::$InternalDatabase;
        $errorBase = $newBase = FALSE;
        if (!file_exists($dbFile)) {
            if (!is_writable(dirname($dbFile))) {
                $errorBase = \TRUE;
            }
            else {
                touch($dbFile);
                if (!file_exists($dbFile)) {
                    $errorBase = \TRUE;
                }
                else {
                    $newBase = TRUE;
                }
            }
        }
        if ($errorBase) {
            throw new \Iris\Exceptions\FileException("$dbFile cannot be created (verify directory structure and file permissions).");
        }
        else {
            $EM = \Iris\DB\_EntityManager::EMByNumber(999);
            if ($newBase) {
                // table creation
                $connexion = $EM->getConnexion();
                $connexion->exec(TModules::DDLText());
                $connexion->exec(TControllers::DDLText());
                $connexion->exec(TActions::DDLText());
                $connexion->exec(TAdmin::DDLText());
                $connexion->exec(TTodo::DDLText());
            }
            return $EM;
        }
    }

    /**
     * Marks all the records of a table as deleted 
     * 
     * @param string $tableName
     */
    public function markDeleted($tableName, $id = \NULL) {
        $EM = $this->getEntityManager();
        if (is_null($id)) {
            $EM->directSQLExec("Update $tableName set Deleted = 1;");
        }
        else {
            throw new \Iris\Exceptions\NotSupportedException('Mark deleted has not been written to Delete individual records');
        }
    }

    /**
     * 
     * @param string[] $searchValues
     * @param mixed[] $newData
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
