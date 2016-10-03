<?php

namespace models;


/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Internal DB of IrisWB (see config/base/config.sqlite)
 * 
 * A wrapper class for \Iris\Structure\_TSequence: it permits instanciation
 * and naming and connexion to database definition. The sequence table contains
 * all the screens of the show.
 * 
 * This class is used as a 
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 * @deprecated since version number
 */
class TAutoform extends \Iris\DB\_Entity {

    protected $_idNames = ['id'];

    public static function CreateObjects() {
        $sql = 'CREATE TABLE autoform (id INTEGER PRIMARY KEY  NOT NULL, Name TEXT, Hidden TEXT, EventDate DATETIME, EventHour DATETIME, Password TEXT, Checkbox BOOL)';
        $em = \Iris\DB\_EntityManager::EMByNumber(1); //GetInstance();
        $list = $em->listTables();
        if (!in_array('autoform', $list)) {
            $em->directSQLExec($sql);
        }
        $elements = 0;
        /// Here add data
        return $elements;
    }

}
