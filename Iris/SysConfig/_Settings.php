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

    use \Iris\Engine\tSingleton;

    const CLASSESSECTION = "settingclasses";

    /**
     * All the known methods of the _Setting class
     * @var array(string)
     */
    private static $_Functions = [
        'has' => 3,
        'get' => 3,
        'ena' => 6,
        'dis' => 7,
        'set' => 3,
        'exi' => 6,
    ];

    /**
     * Each sub class must override the name
     * 
     * @var string
     */
    protected static $_GroupName;

    /**
     * Each settings class must be Inited before use
     */
    public static function Init() {
        static::GetInstance();
    }

    /**
     * The constructor defines some standard settings with a default value. It uses
     * a distinct repository for each Settings class
     */
    protected final function __construct() {
        \Iris\Log::Debug("<u>Creating Setting class for " . static::$_GroupName . '</u>', \Iris\Engine\Debug::SETTINGS);
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
        $name = strtolower($name);
        $functionName = substr($name, 0, 3);
        $param = substr($name, self::$_Functions[$functionName]);
        /* @var $setting _Setting */
        $setting = _Setting::GetSetting($param, static::$_GroupName);
        // Non existent setting throws an exception excepting when existence testing
        if (!$setting->exists() and $functionName != 'exi') {
            _Setting::ErrorManagement("The requested setting has not been defined: $name");
        }
        switch ($functionName) {
            case 'has':
                return $setting->has();
            case 'get':
                return $setting->get();
            case 'set':
                if (count($arguments) != 1) {
                    _Setting::ErrorManagement("The requested setting function set expects a new value");
                }
                $setting->set($arguments[0]);
                return $setting;
                break;
            case 'ena':
                $setting->enable();
                return $setting;
                break;
            case 'dis':
                $setting->disable();
                return $setting;
                break;
            case 'exi':
                return $setting->exists();
                break;
        }

        _Setting::ErrorManagement("Settings has no $name method");
    }

    /**
     * Displays all the known current settings and die if necessary. The method serves a debugging purpose.
     * 
     * @param boolean $stop if FALSE, the method does not die and return  the message
     * @return string
     */
    public static function Debug($stop = \TRUE) {
        return _Setting::Debug(self::$_GroupName, $stop);
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

    protected static function _GetObject($groupName, $objectName) {
        echo "GETTING $groupName - $objectName <br>";
        if (!isset(self::$_Repository[$groupName]) or !isset(self::$_Repository[$groupName][$objectName])) {
            echo "CREATION $groupName - $objectName <br>";
            $setting = self::_New($objectName);
            self::$_Repository[$groupName][$objectName] = $setting;
        }
        else {
            echo "LECTURE $groupName - $objectName <br>";

            /* @var $setting \Iris\SysConfig\StandardSetting */
            $setting = self::$_Repository[$groupName][$objectName];
        }
        iris_debug(self::$_Repository);
        return $setting;
    }

}

