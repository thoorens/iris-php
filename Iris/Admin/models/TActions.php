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
 * Model for the current application
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TActions extends _IrisObject {

    protected static $_InsertionKeys = ['Name', 'controller_id'];
    
    protected static $_MainField = "action #";

    public static function DDLText() {
        return <<<SQL2
CREATE  TABLE "main"."actions" (
        "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
        "Name" TEXT NOT NULL, 
        "controller_id" INTEGER NOT NULL,
        "Deleted" BOOLEAN DEFAULT 0,
        FOREIGN KEY ("controller_id") REFERENCES "controllers"("id"));
SQL2;
    }

}
