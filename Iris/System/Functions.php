<?php

namespace Iris\System;

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
 * @copyright 2011-2013 Jacques THOORENS
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

    public static function Class2File($className, $model = \FALSE){
        $fileName = str_replace("\\",'/', $className);
        if($model){
            $fileName = 'T'.ucfirst($fileName);
        }
        return $fileName.'.php';
    }
    
}

