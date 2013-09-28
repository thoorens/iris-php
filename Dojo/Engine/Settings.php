<?php

namespace Dojo\Engine;

use Dojo\Manager;

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

class Settings extends \Iris\SysConfig\_Settings {

    
    
    protected static $_GroupName = 'dojo';


    /**
     * Version number correspond to the version used to write Iris-PHP.
     * This number is only used in remote URL. With local source, only
     * one version is supposed to installed in public/js folder.
     * 
     * @var string 
     */

    const VERSION = '1.9.0';


        

    /**
     * Defines some standard settings with a default value
     */
    protected function _init() {
        //Manager::SetActive();
        \Iris\SysConfig\StandardSetting::CreateSetting('version', self::VERSION);
        // Debug info is set according to site type (in construct)
        if (\Iris\Engine\Mode::IsDevelopment()) {
            \Iris\SysConfig\StandardSetting::CreateSetting('debug', 'true'); // true is js code
        }
        else {
            \Iris\SysConfig\StandardSetting::CreateSetting('debug', 'false'); // false is js code
        }
        // By default Dojo is parsed on page load
        \Iris\SysConfig\StandardSetting::CreateSetting('parseOnLoad', 'true'); // true is js code
        // The dojo style used in the program among the 4 standard styles: 
        // nihilo,soria, tundra, claro
        \Iris\SysConfig\StandardSetting::CreateSetting('theme', 'nihilo');
        // The default repository for Dojo libraries : GoogleApis
        \Iris\SysConfig\StandardSetting::CreateSetting('source', Manager::GOOGLE);
    }

}
