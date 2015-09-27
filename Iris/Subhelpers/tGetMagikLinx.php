<?php

namespace Iris\Subhelpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
