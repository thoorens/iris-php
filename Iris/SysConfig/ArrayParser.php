<?php

namespace Iris\SysConfig;

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
 * A parser for array (presently only for import)
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ArrayParser extends _Parser {

    
    
    
    /**
     * Reads a file containing an print_r 'ed array into a new config 
     * 
     * @param string $filename file name to scan
     * @param string $sectionName section to consider (or FALSE for all)
     * @param int $inheritance copy inherited values (or ref to parent)
     * @return Config (object or array)
     */
    public function processFile($fileName, $sectionName = FALSE, $inheritance = self::COPY_AND_LINK) {
        $lines = file($fileName);
        $config = new Config('Test');
        foreach ($lines as $line) {
            $parts = explode('=>', $line);
            if (count($parts) == 2) {
                $fieldName = preg_replace('/[\s]*\[(.*)\] [\s]*/', '$1', $parts[0]);
                $fieldValue = trim($parts[1]);
                $config->$fieldName = $fieldValue;
            }
        }
        return $config;
    }

}

