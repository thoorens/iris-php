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
 * @copyright 2012 Jacques THOORENS
 */

/**
 * An abstract parser defining some constants, method prototypes 
 * and a static builder.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Parser {

    const NO_INHERITANCE = 0;
    const COPY_INHERITED_VALUES = 1;
    const LINK_TO_PARENT = 2;
    const COPY_AND_LINK = 3;

    /**
     *
     * @param type $type
     * @return _Parser 
     */
    public static function ParserBuilder($type) {
        switch ($type) {
            case 'ini':
                return new IniParser();
                break;
            case 'db':
                return new DBParser();
                break;
        }
    }

    /**
     * 
     */

    /**
     * Reads a file into a new config or array of configs
     * 
     * @param type $filename file name to scan
     * @param type $sectionName section to consider (or FALSE for all)
     * @param int $inheritance copy inherited values (or ref to parent)
     * @return Config (object or array)
     */
    public abstract function processFile($fileName, $sectionName = FALSE, $inheritance = self::COPY_AND_LINK);

    /**
     * Exports an array of configs to a text file
     * 
     * @param string $filename file name to write
     * @param array $configs the configs to write to the file
     * @param int $inheritance copy inherited values (or ref to parent)
     */
    public function exportFile($fileName, $configs,  $inheritance=self::LINK_TO_PARENT) {
        throw new \Iris\Exceptions\NotSupportedException('Export not yet supported');
    }

}


