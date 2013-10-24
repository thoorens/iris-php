<?php

namespace models;

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
 * Internal DB of IrisWB (see config/base/config.sqlite)
 * 
 * All screen in the sequence table are placed in a section for easier acces.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class TSections extends \Iris\DB\_Entity {
    /*
     * CREATE TABLE "sections" (
     * "id" INTEGER PRIMARY KEY, -- the id of the section
     *  "GroupName" VARCHAR -- the name of the section
     * );
     */

    public static function getListById() {
        $tCategories = self::GetEntity();
        $categories = $tCategories->fetchAll();
        foreach ($categories as $cat) {
            //if ($cat->id != 0)
            $list[$cat->id] = $cat->GroupName;
        }
        return $list;
    }

    public static function GetSection($idSection) {
        $tSections = TSections::GetEntity();
        return $tSections->fetchRow("id='$idSection'");
    }
    
}

