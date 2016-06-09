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
class TAdmin extends _IrisObject {


    public static function DDLText() {
        return <<<SQL2
CREATE  TABLE "main"."admin" (
        "id" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , 
        "LastUpdate" TIMEDATE,
        "Deleted" BOOLEAN DEFAULT 0);
SQL2;
    }

    public static function LastUpdate() {
        $date = new \Iris\Time\TimeDate;
        $tAdmin =  \Iris\Admin\models\TAdmin::GetEntity();
        $admin = $tAdmin->fetchRow();
        if (is_null($admin)) {
            $admin = $tAdmin->createRow();
        }
        $admin->LastUpdate = $date->toString();
        $admin->save();
    }

    public static function GetLastUpdate($formated = \TRUE) {
        $tAdmin = \Iris\Admin\models\TAdmin::GetEntity();
        $admin = $tAdmin->fetchRow();
        if (is_null($admin)) {
            $date = "Never";
        }
        else {
            $date = new \Iris\Time\TimeDate($admin->LastUpdate);
        }
        if ($formated) {
            return $date == 'Never' ? $date : $date->toString('H:i (j/m/Y)');
        }
        else {
            return $date;
        }
    }

}

