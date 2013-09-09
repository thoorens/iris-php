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
 * This class offers a way to manage settings:<ul>
 * <li> some are predefined during Settings occurrence initialization
 * <li> there are vanilla settings with <b>ge</b>t and  <b>set</b> methods
 * <li> there are boolean settings with <b>has</b>, <b>enable</b> and <b>disable</b>
 * <li> settings can be added at later stage (one at a time or through an ini file)
 * <li> a non defined setting reading throws an exception
 * </ul>
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Settings implements \Iris\Design\iSingleton {

    use \Iris\Engine\tPolySingleton;

    const CLASSESSECTION = "settingclasses";

    /**
     * All the known methods of the _Setting class
     * @var array(string)
     */
    private static $_Functions = [
        'Has',
        'Get',
        'Enable',
        'Disable',
        'Set',
        'Exists',
    ];
    
    /**
     * Each sub class must override the name
     * 
     * @var string
     */
    protected static $_GroupName;
    

    /**
     * The constructor defines some standard settings with a default value. It uses
     * a distinct repository for each Settings class
     */
    protected final function __construct() {
        \Iris\Log::Debug("<u>Creating Setting class for ".static::$_GroupName.'</u>', \Iris\Engine\Debug::SETTINGS);
        _Setting::SetCurrentGroup(static::$_GroupName);
        $this->_init();
        _Setting::ResetGroup();
    }

    protected abstract function _init();

    /**
     * Analyses a pseudo static method and its arguments. The name has a composed structure
     * in the model FunctionSettingname. This method can throw an exception in 3 cases:<ul>
     * <li>if the setting does not exist (except in the case of Exists function)
     * <li>if the setting exists but has no such method
     * <li>indirectly if the setting is not the good type
     * </ul>
     *  
     * @param string $name
     * @param array $arguments
     * @return boolean/mixed
     * @throws \Iris\Exceptions\InternalException
     */
    public static function __callStatic($name, $arguments) {
        self::GetInstance();
        foreach (self::$_Functions as $function) {
            if (strpos($name, $function) === 0) {
                $name = strtolower($name);
                $param = substr($name, strlen($function));
                $arg = count($arguments) ? $arguments[0] : \NULL;
                /* @var $setting _Setting */
                $setting = _Setting::GetSetting($param, static::$_GroupName);
                // Non existent setting throws an exception excepting when existence testing
                if (!$setting->exists()) {
                    if ($function == 'Exists')
                        return \FALSE;
                    else
                        _Setting::SettingError("The requested setting has not been defined: $name");
                }
                return $setting->$function($arg); // can throw an exception in case of type mismatch
            }
        }
        _Setting::SettingError("Settings has no $name method");
    }

    /**
     * Displays all the known settings and die if necessary. The method serves a debugging purpose.
     * 
     * @param boolean $stop if FALSE, the method does not die and return  the message
     * @return string
     */
    public function debug($stop = \TRUE) {
        /* @var $setting _Setting */
        $html = "<table border=\"1\">\n<tr>\n<th>Name</th><th>Type</th><th>Value</th>\n</tr>\n";
        foreach (\Iris\SysConfig\_Setting::GetAllObjects()[self::$_GroupName] as $name => $setting) {
            $html .= "<tr>\n" . $setting->debug() . "</tr>\n";
        }
        $html .= "</table>";
        if ($stop)
            die($html);
        else
            return $html;
    }

    /**
     * 
     * @param Config $configs
     */
    public function iniSettings($configs) {
        $classes = $this->_findClasses($configs);
        foreach ($configs as $sectionName => $config) {
            if ($sectionName != self::CLASSESSECTION) {
                //$this->_treatSection($sectionName, $config, $classes);
            }
        }
    }

    private function _treatSection($sectionName, $config, $classes) {
        if ($sectionName == 'caprout')
            return;
        echo "Section : $sectionName<br>";
        die('OK');
        $class = $classes[$sectionName];
        echo "With $class<br>";
        foreach ($config as $key => $value) {
            $value = $this->_normalize($value);
            echo "Key ($key): value ($value)<br>";
            if (is_bool($value)) {
                $function = "Enable$key";
                Settings::$function();
                die('Zut');
                Settings::GetInstance()->debug();
                die($function);
            }
            else {
                $function = "Set$key";
                $class::GetInstance();
                die($function);
                Settings::$function($value);
            }
        }
        echo "<hr>";
    }

    /**
     * Provides a complete list of Settings subclasses from standard list
     * and trough an analysis of the settingclasses section of the ini file
     * 
     * @param Config $configs
     */
    private function _findClasses($configs) {
        $classes = [
            'main' => '\\Iris\\SysConfig\\Settings',
            'dojo' => '\\Dojo\\Engine\\Settings',
            'error' => '\\Iris\Errors\\Settings',
        ];
        if (isset($configs[self::CLASSESSECTION])) {
            foreach ($configs['settingclasses'] as $section => $className) {
                $classes[$section] = $className;
            }
        }
        return $classes;
    }

    public function _normalize($value) {
        iris_debug($value);
        if ($value == 'FALSE') {
            $value = \FALSE;
        }
        elseif ($value == 'TRUE') {
            $value = \TRUE;
        }
        return $value;
    }

}

