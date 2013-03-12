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

    const DEF_ATB_AJAXMODE = \TRUE;
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
        $instance = self::Instance();
        return $instance->_getValue('adminToolbarAjaxMode', self::DEF_ATB_AJAXMODE);
    }

    public static function SetAdminToolbarAjaxMode($value = \TRUE){
        $instance = self::Instance();
        $instance->_data['adminToolbarAjaxMode'] = $value;
    } 
    
    public static function GetDisplayRuntimeDisplay(){
        $instance = self::Instance();
        return $instance->_getValue('displayRuntimeDuration', self::DEF_DISPLAY_RTD);
    }
    
    /**
     * Enable the final javascript exec time routine
     * (RunTime Duration)
     * No effect in production.
     * 
     */
    public static function EnableDisplayRuntimeDuration(){
        $instance = self::Instance();
        $instance->_data['displayRuntimeDuration'] = \TRUE;
    }
    /**
     * Disable the final javascript exec time routine
     * (Runtime Duration)
     * This is the default state
     * 
     */
    public static function DisableDisplayRuntimeDuration(){
        $instance = self::Instance();
        $instance->_data['displayRuntimeDuration'] = \FALSE;
    }
    
    
    private function _getValue($name,$default){
        if(!isset($this->_data[$name])){
            return $default;
        }
        return $this->_data[$name];
    }

    /**
     * 
     * @return self
     */
    private static function Instance(){
        return self::GetInstance();
    }
}

