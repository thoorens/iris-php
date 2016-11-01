<?php

namespace Iris\SysConfig;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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

    /**
     * the ini file is not considered as an inheritance structure
     */
    const NO_INHERITANCE = 0;
    const COPY_INHERITED_VALUES = 1;
    const LINK_TO_PARENT = 2;
    const COPY_AND_LINK = 3;

    /**
     *
     * @param type $type
     * @return _Parser 
     */
    public static function ParserBuilder($type){
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
    public abstract function processFile($fileName, $sectionName = \FALSE, $inheritance = self::COPY_AND_LINK);

    /**
     * Exports an array of configs to a text file
     * 
     * @param string $filename file name to write
     * @param Config[] $configs the configs to write to the file
     * @param int $inheritance copy inherited values (or ref to parent)
     */
    public function exportFile($fileName, $configs,  $inheritance=self::LINK_TO_PARENT) {
        throw new \Iris\Exceptions\NotSupportedException('Export not yet supported');
    }

}


