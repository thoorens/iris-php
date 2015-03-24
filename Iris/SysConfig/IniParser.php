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
 * A parser (read and write) for config in a ini file.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 * One method inspired by Jeremy Giberson
 * 
 */
class IniParser extends _Parser {

    /**
     * An extended version of parse_ini_file which takes into account
     * inheritance. Adapted from Jeremy Giberson
     * 
     * @author Jeremy Giberson.
     * @see http://php.net/manual/fr/function.parse-ini-file.php
     *  Original version :
     *  ================
      //
      //    function parse_ini_file_extended($filename) {
      //        $p_ini = parse_ini_file($filename, TRUE);
      //        $config = array();
      //        foreach ($p_ini as $namespace => $properties) {
      //            list($name, $extends) = explode(':', $namespace);
      //            $name = trim($name);
      //            $extends = trim($extends);
      //            // create namespace if necessary
      //            if (!isset($config[$name]))
      //                $config[$name] = array();
      //            // inherit base namespace
      //            if (isset($p_ini[$extends])) {
      //                foreach ($p_ini[$extends] as $prop => $val)
      //                    $config[$name][$prop] = $val;
      //            }
      //            // overwrite / set current namespace values
      //            foreach ($properties as $prop => $val)
      //                $config[$name][$prop] = $val;
      //        }
      //        return $config;
      //    }
      //
     * @param type $filename file name to scan
     * @param type $sectionName section to consider (or FALSE for all)
     * @param int $inheritance copy inherited values (or ref to parent)
     * @return Config (object or array)
     */
    public function processFile($filename, $sectionName = FALSE, $inheritance = _Parser::COPY_AND_LINK) {
        // in case of single section, it is necessary to copy values
        $inheritance = $sectionName ? self::COPY_INHERITED_VALUES : $inheritance;
        $parsedData = parse_ini_file($filename, TRUE, INI_SCANNER_RAW);
        $configs = array();
        foreach ($parsedData as $namespace => $properties) {
            // Needs a dummy ancestor 
            list($name, $parent) = explode(':', $namespace . ":");

            $name = trim($name);
            $parent = trim($parent); // may be empty
            // create namespace if necessary
            if ($sectionName === FALSE or $sectionName == $name) {
                if (!isset($configs[$name])) {
                    $configs[$name] = new Config($name);
                }
                // inherit base namespace
                if (isset($configs[$parent])) {
                    if ($inheritance == self::COPY_AND_LINK or
                            $inheritance == self::LINK_TO_PARENT) {
                        $configs[$name]->setParent($configs[$parent], $inheritance);
                    }
                    foreach ($configs[$parent] as $prop => $val) {
                        if ($inheritance == self::COPY_AND_LINK or
                                $inheritance == self::COPY_INHERITED_VALUES) {
                            $configs[$name]->$prop = $val;
                        }
                        else {
                            // put prop in iterator
                            $configs[$name]->getIterator()->add($prop);
                        }
                    }
                }
                // overwrite / set current namespace values
                foreach ($properties as $prop => $val) {
                    $configs[$name]->$prop = $val;
                }
            }
        }
        // if no section selected returns an array, otherwise a Config
        if ($sectionName === FALSE) {
            return $configs;
        }
        else {
            return $configs[$sectionName];
        }
    }

    /**
     * Exports an array of configs to a text file
     * 
     * @param string $filename file name to write
     * @param Config[] $configs the configs to write to the file
     * @param int $inheritance copy inherited values (or ref to parent)
     */
    public function exportFile($fileName, $configs, $inheritance = self::LINK_TO_PARENT) {
        $data = $this->_makeIniText($configs, $inheritance);
        $text = implode("\n", $data);
        file_put_contents($fileName, $text);
    }

    /**
     * Transforms a config or an array of configs in an array of line of text
     * 
     * @param mixed $configs
     * @param int $inheritance
     * @return string
     */
    protected function _makeIniText($configs, $inheritance) {
        $results = array();
        if (is_array($configs)) {
            foreach ($configs as $section => $config) {
                if (is_numeric($section)) {
                    $section = $config->getName();
                }
                if (is_null($config->getParent()) or $inheritance == self::NO_INHERITANCE) {
                    $results[] = "[$section]";
                }
                else {
                    $parentName = $config->getParent()->getName();
                    $results[] = "[$section:$parentName]";
                }
                $resSection = $this->_makeIniText($config, $inheritance);
                $results = array_merge($results, $resSection);
                $results[] = '';
            }
        }
        else {
            $parent = $configs->getParent();
            foreach ($configs as $key => $val) {
                if (!is_null($parent) and $inheritance == self::LINK_TO_PARENT) {
                    if ($val == $parent->$key) {
                        continue;
                    }
                }
                $results[] = "$key = " . (is_numeric($val) ? $val : '"' . $val . '"');
            }
        }
        return $results;
    }

}


