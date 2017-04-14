<?php

namespace models_internal;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * An old version misplaced of the test for magickforms 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 * @deprecated placed in normal models
 */
class TAutoform extends \Iris\DB\_Entity {

    protected $_idNames = ['id'];

    /**
     * Verifies an entity Autoform exists or creates it
     * 
     * @return \Iris\DB\_EntityManager
     */
    public static function Verify() {
        $sql = 'CREATE TABLE autoform (id INTEGER PRIMARY KEY  NOT NULL, Name TEXT, Hidden TEXT, EventDate DATETIME, EventHour DATETIME, Password TEXT, Checkbox BOOL)';
        //$em = \Iris\DB\_EntityManager::EMByNumber(1); //GetInstance();
        $em = \models\TInvoices::GetEM();
        $list = $em->listTables();
        if (!in_array('autoform', $list)) {
            $em->directSQLExec($sql);
        }
        return $em;
    }

}
