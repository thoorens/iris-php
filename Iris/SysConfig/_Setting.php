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
 * An abstract _Setting has 5 methods which all throw exception and an exists methods allways returning TRUE. 
 * The concrete Setting will overriden them according to their nature
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
abstract class _Setting {

    /**
     * All the settings are in arrays stored in an array
     * 
     * @var array of array
     */
    protected static $_Repository = [];

    /**
     * At Settings init, a default group is predefined
     * 
     * @var string
     */
    private static $_CurrentGroup = \NULL;

    /**
     * Each subclass has its own type
     * 
     * @var string
     */
    protected static $_Type = \NULL;

    /**
     * If true, the setting cannot be modified
     * 
     * @var boolean
     */
    protected $_locked = \FALSE;

    /**
     * The value of the setting
     * 
     * @var mixed
     */
    protected $_value;

    /**
     * The full camelcase name of the setting
     * 
     * @var string
     */
    protected $_fullName;

    /**
     * A setting is not defined except when created by CreateSetting
     * 
     * @var boolean
     */
    private $_defined = \FALSE;

    /* ===========================================================================================
     * Static methods for management of the setting repository
     * ===========================================================================================
     */

    /**
     * A convenient way to create a setting (and register it in the repository)
     * The group name correspond to a specific subclass of _Settings. It can
     * be predefined by SetGroup static method
     * 
     * @param string $camelcaseName The name in camelcase
     * @param mixed $initialValue An initial value
     * @param string $groupName The group name of the setting 
     */
    public static function CreateSetting($camelcaseName, $initialValue, $groupName = \NULL) {
        $groupName = self::_GetGroupName($groupName);
        if (get_called_class() == 'Iris\\SysConfig\\BooleanSetting') {
            $setting = new BooleanSetting();
        }
        else {
            $setting = new StandardSetting();
        }
        \Iris\Log::Debug("Initing setting $camelcaseName with $initialValue", \Iris\Engine\Debug::SETTINGS);
        $setting->_fullName = $camelcaseName;
        $setting->_value = $initialValue;
        $setting->_defined = \TRUE;
        self::$_Repository[$groupName][strtolower($camelcaseName)] = $setting;
    }

    /**
     * Get a setting by its name and optionally its group. If $exception is set to TRUE, a non
     * existing setting rises an exception. Otherwise, a dummy setting is returned (to test its _defined internal value)
     * CAUTION : the setting are normaly tested or modified through a static method of a subclass of _Settings. This method
     * is intended to be called internally.  
     * 
     * @param string $settingName The name of the setting
     * @param string $groupName The group name of the setting
     * @param boolean $exception If not found rises an exception or not
     * @return type
     */
    public static function GetSetting($settingName, $groupName = \NULL, $exception = \FALSE) {
        $groupName = self::_GetGroupName($groupName);
        $settingName = strtolower($settingName);
        $defined = \TRUE;
        if (!isset(self::$_Repository[$groupName]) or !isset(self::$_Repository[$groupName][$settingName])) {
            self::CreateSetting($settingName, \NULL, $groupName);
            $defined = \FALSE;
        }
        /* @var $setting _Setting */
        $setting = self::$_Repository[$groupName][$settingName];
        if (!$defined) {
            $setting->_defined = \FALSE;
            if ($exception) {
                self::ErrorManagement("The setting $settingName does not seem to exist in $groupName");
            }
        }
        return $setting;
    }

    /**
     * Returns the settings for a specified group (or defaultgroup)
     * 
     * @param type $groupName
     * @return array
     */
    public static function GetGroup($groupName = \NULL) {
        $groupName = self::_GetGroupName($groupName);
        if (isset(self::$_Repository[$groupName])) {
            return self::$_Repository[$groupName];
        }
        else {
            return [];
        }
    }

    /**
     * Returns all the settings as an array
     * 
     * @return array
     */
    public static function GetAllObjects() {
        return self::$_Repository;
    }

    /**
     * Temporaryli sets the current group name
     * 
     * @param string $groupName 
     */
    public static function SetCurrentGroup($groupName) {
        self::$_CurrentGroup = $groupName;
    }

    /**
     * No more Current group
     */
    public static function ResetGroup() {
        self::$_CurrentGroup = \NULL;
    }

    /**
     * Verifies whether the group name is not null, supplying the current group 
     * if it exists (otherwise sends an error)
     * 
     * @param string $groupName
     * @return string
     */
    private static function _GetGroupName($groupName) {
        if (is_null($groupName)) {
            if (is_null(self::$_CurrentGroup)) {
                iris_debug(debug_backtrace());
                self::ErrorManagement("'SetCurrentRepository' must be used before creating new settings.");
            }
            $groupName = self::$_CurrentGroup;
        }
        return $groupName;
    }

    /* ==========================================================================
     * Boolean accessors of a setting
     * ==========================================================================
     */

    /**
     * Will return TRUE if the setting has been enabled
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function has() {
        self::ErrorManagement("The requested setting is not boolean");
    }

    /**
     * Enables a boolean setting  if it is not locked
     * 
     * @param boolean $lock If true, the setting is modified and protected
     * @throws \Iris\Exceptions\InternalException
     */
    /**
     * 
     */
    public function enable($lock = \FALSE) {
        self::ErrorManagement("The requested setting is not boolean");
    }

    /**
     * Disables a boolean setting if it is not locked
     * 
     * @param boolean $lock If true, the setting is modified and protected
     * @throws \Iris\Exceptions\InternalException
     */
    public function disable($lock = \FALSE) {
        self::ErrorManagement("The requested setting is not boolean");
    }

    /* ==========================================================================
     * Standard accessors of the setting
     * ==========================================================================
     */

    /**
     * Returns the value of the setting
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function get() {
        self::ErrorManagement("The requested setting is boolean");
    }

    /**
     * Sets the new value of a setting
     * 
     * @param mixed $value The new value
     * @param boolean $lock If true, the setting is modified and protected
     * @throws \Iris\Exceptions\InternalException
     */
    public function set($value, $lock = \FALSE) {
        self::ErrorManagement("The requested setting is boolean");
    }

    /**
     * Not all exists (dummy ones do not)
     * 
     * @return boolean
     */
    public function exists() {
        return $this->_defined;
    }

    /**
     * A way to lock a setting (it cannot be modified anymore)
     */
    protected function _lock(){
        $this->_locked = \TRUE;
    }
    
    /* =============================================================================
     * Debugging tools
     * =============================================================================
     */
    
    /**
     * Generates an html array containing all the setting for a group
     * or all groups if groupName is NULL. Immediate or not
     * 
     * @param string $groupName the name of the required group (all groups if null)
     * @param boolean $immediate if false returns a string
     * @return string
     */
    public static function Debug($groupName = \NULL, $immediate = \TRUE) {
        // treat all groups
        if (is_null($groupName)) {
            $html = '';
            foreach (self::$_Repository as $group => $dummy) {
                $html .= "<h1>Settings in $group<h1>";
                $html .= self::Debug($group, \FALSE);
            }
        }
        // only the required group
        else {
            /* @var $setting _Setting */
            $html = "<table border=\"1\">\n<tr>\n<th>Name</th><th>Type</th><th>Value</th>\n</tr>\n";
            foreach (self::$_Repository[$groupName] as $name => $setting) {
                $html .= "<tr>\n" . $setting->_debugOne() . "</tr>\n";
            }
            $html .= "</table>";
        }
        if ($immediate)
            die($html);
        else
            return $html;
    }

    /**
     * Returns the name + type + value of a setting (for debugging purpose)
     * 
     * @param string $name
     * @return string
     */
    private function _debugOne() {
        $html[] = $this->_fullName;
        $html[] = $this->_showType();
        $html[] = $this->_showValue();
        return "<td>" . implode("</td>\n<td>", $html) . "</td>\n";
    }

    /**
     * Will return the setting type according to the Setting class
     */
    protected function _showType() {
        return static::$_Type;
    }

    /**
     * Returns the required value (overridden in BooleanSetting)
     * 
     * @return mixed
     */
    protected function _showValue() {
        return $this->_value;
    }

    /**
     * Issues an ending message or throws an exception according to SettingException setting
     * 
     * @param string $message
     * @throws \Iris\Exceptions\SettingException
     */
    public static function ErrorManagement($message) {
        if (is_null(self::$_CurrentGroup) or !\Iris\Errors\Settings::HasSettingException()) {
            \Iris\Engine\Debug::Kill("Error in settings: $message");
        }
        throw new \Iris\Exceptions\SettingException($message);
    }

}

