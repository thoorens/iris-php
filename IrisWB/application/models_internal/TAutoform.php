<?php

namespace models_internal;


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

    public static function Create() {
        $sql = 'CREATE TABLE autoform (id INTEGER PRIMARY KEY  NOT NULL, Name TEXT, Hidden TEXT, EventDate DATETIME, EventHour DATETIME, Password TEXT, Checkbox BOOL)';
        $em = \Iris\DB\_EntityManager::EMByNumber(1); //GetInstance();
        $list = $em->listTables();
        if (!in_array('autoform', $list)) {
            $em->directSQLExec($sql);
        }
    }

}
