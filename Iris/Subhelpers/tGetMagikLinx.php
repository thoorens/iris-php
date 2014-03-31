<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Subhelpers;

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
 * This trait complements the iRenderer interface with a subhelper supply
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tGetMagikLinx {

    /**
     * Gets the contents of the magik link from a database or data repository
     * 
     * @param string $name The magik link id
     * @param string $internalFN an optional field name for the "internal" parameter
     * @param string $labelFN an optional field name for the "label" parameter
     * @param string $titleFN an optional field name for the "title" parameter
     * @param string $urlFN an optional field name for the "url" parameter
     * @return array (containing 4 values: internal flag, label, title and url)
     */
    public static function GetMagikLink($name, $internalFN, $labelFN, $titleFN, $urlFN) {
        $eLinks = self::GetEntity();
        $object = $eLinks->find($name);
        if (!is_null($object)) {
            $vLabel = $object->$labelFN == '' ? $name : $object->$labelFN;
            if ($object->$internalFN) {
                $vUrl = $object->$urlFN;
            }
            else {
                $vUrl = "http://" . $object->$urlFN;
            }
            $vTitle = $object->$titleFN;
            $vInternal = $object->$internalFN;
        }
        else {
            $vLabel = $name;
            $vUrl = $vTitle = '';
            $vInternal = \FALSE;
        }
        return [$vInternal, $vLabel, $vTitle, $vUrl];
    }

}
