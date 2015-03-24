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

