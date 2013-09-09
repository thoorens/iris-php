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

    use \Iris\System\tDoubleRepository;

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
    private $_defined = \FALSE;

    

    public static function CreateSetting($fullName, $initialValue, $groupName = \NULL) {
        $object = self::_GetSetting($fullName, $groupName);
        \Iris\Log::Debug("Initing setting $fullName with $initialValue", \Iris\Engine\Debug::SETTINGS);
        $object->_value = $initialValue;
        $object->_fullName = $fullName;
        $object->_defined = \TRUE;
    }

    public static function GetSetting($settingName, $groupName = \NULL, $exception = \FALSE) {
        $object = self::_GetSetting($settingName, $groupName);
        if ($object->_defined and $exception) {
            self::SettingError("The setting $settingName does not seem to exist in $groupName");
        }
        $object->_value = \NULL;
        return $object;
    }

    /**
     * 
     * @param type $fullSettingName
     * @param type $groupName
     * @return _Setting
     */
    private static function _GetSetting($fullSettingName, $groupName) {
        $settingName = strtolower($fullSettingName);
        if (is_null($groupName)) {
            if (is_null(self::$_CurrentGroup)) {
                iris_debug(debug_backtrace());
                self::SettingError("'SetCurrentRepository' must be used before creating new settings.");
            }
            $groupName = self::$_CurrentGroup;
        }

        /* @var $object _Setting */
        return self::_GetObject($groupName, $settingName);
    }

    /**
     * Sets the current group name
     * 
     * @param string $groupName 
     */
    public static function SetCurrentGroup($groupName) {
        self::$_CurrentGroup = $groupName;
    }

    /**
     * No more CurrentRepositpry
     */
    public static function ResetGroup() {
        self::$_CurrentGroup = \NULL;
    }

    // Boolean accessors

    /**
     * Will return TRUE if the setting has been enabled
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function has() {
        self::SettingError("The requested setting is not boolean");
    }

    /**
     * Enables a boolean setting  if it is not locked
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function enable() {
        self::SettingError("The requested setting is not boolean");
    }

    /**
     * Disables a boolean setting if it is not locked
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function disable() {
        self::SettingError("The requested setting is not boolean");
    }

    /**
     * Returns the value of the setting
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function get() {
        self::SettingError("The requested setting is boolean");
    }

    /**
     * Sets the new value of a setting
     * 
     * @param mixed $value
     * @throws \Iris\Exceptions\InternalException
     */
    public function set($value) {
        self::SettingError("The requested setting is boolean");
    }

    /**
     * The object necessarily exists
     * 
     * @return boolean
     */
    public function exists() {
        return $this->_defined;
    }

    /**
     * Returns the name + type + value of a setting (for debugging purpose)
     * 
     * @param string $name
     * @return string
     */
    public function debug() {
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

    public static function SettingError($message) {
        die($message);
        if (is_null(self::$_CurrentGroup) or !\Iris\Errors\Settings::HasSettingException()) {
            die("Error in settings: $message");
        }
        throw new \Iris\Exceptions\SettingException($message);
    }

}

