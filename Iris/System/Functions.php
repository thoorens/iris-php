<?php

namespace Iris\System;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Some functions
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Functions {

    /**
     * A human readable version number for the framework
     * 
     * @var string
     */
    private static $_IrisVersion = "1.0 RC2";
    /**
     * A numeric version number (for comparison)
     *  
     * @var float
     */
    private static $Version = 100.02;

    /**
     * Computes the complementary color from a given color
     * 
     * @param string $color The color in HTML format (# + 3 or 6 digits)
     * @return string
     */
    public static function GetComplementaryColor($color) {
        if (strlen($color) == 4) {
            $max = 4095;
            $format = "#%03x";
        }
        else {
            $max = 16777215;
            $format = "#%06x";
        }
        $colorValue = hexdec(substr($color, 1));
        $complement = $max - $colorValue;
        return (sprintf($format, $complement));
    }

    /**
     * Returns the version of the framework. The numeric version is
     * meant to be used in comparison. It is computed this way <ul>
     * <li> the version number * 100
     * <li> the subversion number (from 0 to 99)
     * <li> the release number (from 0.000 to 0.99)
     * </ul>
     * 
     * @param boolean $numeric If true, returns a float number
     * @return string/float
     */
    public static function IrisVersion($numeric = \FALSE) {
        if ($numeric)
            return self::$Version;
        else
            return self::$_IrisVersion;
    }

    public static function ClassToFile($className, $model = \FALSE){
        $fileName = str_replace("\\",'/', $className);
        if($model){
            $fileName = 'T'.ucfirst($fileName);
        }
        return $fileName.'.php';
    }
    
    /**
     * Converts a table name to a model class name
     * e.g orders to \models\TOrders
     * 
     * @param string $tableName
     * @return string
     */
    public static function TableToEntity($tableName){
        $nameSpace = \Iris\SysConfig\Settings::$DefaultModelLibrary;
        return $nameSpace.'T'.ucfirst($tableName);
    }
    
}

