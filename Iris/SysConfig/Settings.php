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
 * A config is a container for some properties. It can be looked into
 * through foreach. Each property has its own value or can be inherited
 * from an ancestor. The values can be managed in files or database through 
 * a _Parser.
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Settings implements \Iris\Design\iSingleton {

    use \Iris\Engine\tSingleton;

    /**
     * By default, the admin tool bar is displayed by an Ajax request
     */
    const DEF_ATB_AJAXMODE = \TRUE;
    /**
     * By default, the run time display is enabled (only effective in development)
     */
    const DEF_DISPLAY_RTD = \TRUE;
    
    /**
     *
     * @var array : associative array containing the values of the 
     * config (inherited values may be in it or not according to inheritance mode) 
     * Always use the iterator or magic methods to access the actual data
     */
    private $_data = array();

    /**
     * 
     * @return boolean
     */
    /**
     * If TRUE, the toolbar is only prepared at runtime and refreshed later
     * by an ajax routine. May be changed directly by this instruction in a config file:
     * \ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;
     * 
     * @var boolean 
     */
    public static function GetAdminTollbarAjaxMode() {
        $instance = self::GetInstance();
        return $instance->_getValue('adminToolbarAjaxMode', self::DEF_ATB_AJAXMODE);
    }

    public static function SetAdminToolbarAjaxMode($value = \TRUE){
        $instance = self::GetInstance();
        $instance->_data['adminToolbarAjaxMode'] = $value;
    } 
    
    public static function GetDisplayRuntimeDisplay(){
        $instance = self::GetInstance();
        return $instance->_getValue('displayRuntimeDuration', self::DEF_DISPLAY_RTD);
    }
    
    /**
     * Enable the final javascript exec time routine
     * (RunTime Duration)
     * No effect in production.
     * 
     */
    public static function EnableDisplayRuntimeDuration(){
        $instance = self::GetInstance();
        $instance->_data['displayRuntimeDuration'] = \TRUE;
    }
    /**
     * Disable the final javascript exec time routine
     * (Runtime Duration)
     * This is the default state
     * 
     */
    public static function DisableDisplayRuntimeDuration(){
        $instance = self::GetInstance();
        $instance->_data['displayRuntimeDuration'] = \FALSE;
    }
    
    /**
     * An MD5 signature may be calculated for a page and
     * placed in a field (used by WB)
     * By default, not used.
     */
    public static function EnableMD5Signature(){
       $instance = self::GetInstance();
       $instance->_data['md5'] = \TRUE;
    }
    
    /**
     * Disables the MD5 signature after it has been enabled
     */
    public static function DisableMD5Signature(){
       $instance = self::GetInstance();
       $instance->_data['md5'] = \FALSE;
    }
    
    /**
     * Returns true if MD5 signature is enabled
     * @return boolean
     */
    public static function HasMD5Signature(){
        $instance = self::GetInstance();
        return $instance->_getValue('md5', \FALSE);
    }
    
    private function _getValue($name,$default){
        if(!isset($this->_data[$name])){
            return $default;
        }
        return $this->_data[$name];
    }

    
}

