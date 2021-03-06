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

    const EXTRASETTINGS = "extrasettings";

    /**
     * All the known methods of the _Setting class
     * @var string[]
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
    public static function __ClassInit() {
        if (!is_null(static::$_GroupName)) {
            static::GetInstance();
        }
    }

    /**
     * The constructor defines some standard settings with a default value. It uses
     * a distinct repository for each Settings class
     */
    protected final function __construct() {
        \Iris\Engine\Log::Debug("<u>Creating Setting class for " . static::$_GroupName . '</u>', \Iris\Engine\Debug::SETTINGS);
        _Setting::SetCurrentGroup(static::$_GroupName);
        $this->_init();
        _Setting::ResetGroup();
    }

    protected function _init() {
        
    }



    /**
     * Analyses a pseudo static method and its arguments. The name has a composed structure
     * in the model FunctionSettingname. This method can throw an exception in 3 cases:<ul>
     * <li>if the setting does not exist (except in the case of Exists function)
     * <li>if the setting exists but has no such method
     * <li>indirectly if the setting is not the good type
     * </ul>
     *  
     * @param string $name
     * @param mixed[] $arguments
     * @return boolean/mixed
     * @throws \Iris\Exceptions\InternalException
     */
    public static function __callStatic($name, $arguments) {
        try {
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
                case 'ena':
                    $setting->enable();
                    return $setting;
                case 'dis':
                    $setting->disable();
                    return $setting;
                case 'exi':
                    return $setting->exists();
            }
        }
        catch (Exception $exc) {
            _Setting::ErrorManagement("Settings has no $name method");
        }
    }

    /**
     * Displays all the known current settings and die if necessary. The method serves a debugging purpose.
     * 
     * @param boolean $stop if FALSE, the method does not die and return  the message
     * @return string
     */
    public static function Debug($stop = \TRUE) {
        return _Setting::Debug(static::$_GroupName, $stop);
    }

    /**
     * Inserts the value from configs into the settings
     * 
     * @param Config[] $configs
     */
    public static function FromConfigs($configs) {
        $classes = self::_FindClasses($configs);
        foreach ($configs as $sectionName => $config) {
            if ($sectionName != self::EXTRASETTINGS) {
                self::_TreatSection($sectionName, $config, $classes);
            }
        }
    }

    private static function _TreatSection($sectionName, $config, $classes) {
        $class = $classes[$sectionName];
        foreach ($config as $key => $value) {
            $value = self::_Normalize($value);
            if (is_bool($value)) {
                if ($value) {
                    $function = "Enable$key";
                }
                else {
                    $function = "Disable$key";
                }
                $class::$function();
            }
            else {
                $function = "Set$key";
                $class::$function($value);
            }
        }
    }

    /**
     * Provides a complete list of Settings subclasses from standard list
     * and trough an analysis of the settingclasses section of the ini file
     * 
     * @param Config $configs
     */
    private static function _FindClasses($configs) {
        $classes = [
            'main' => '\\Iris\\SysConfig\\Settings',
            'dojo' => '\\Dojo\\Engine\\Settings',
            'error' => '\\Iris\Errors\\Settings',
        ];
        if (isset($configs[self::EXTRASETTINGS])) {
            foreach ($configs[self::EXTRASETTINGS] as $section => $className) {
                $classes[$section] = $className;
            }
        }
        return $classes;
    }

    public static function _Normalize($value) {
        if ($value == 'FALSE') {
            $value = \FALSE;
        }
        elseif ($value == 'TRUE') {
            $value = \TRUE;
        }
        return $value;
    }

}
