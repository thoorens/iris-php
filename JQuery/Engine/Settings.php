<?php

namespace JQuery\Engine;

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

    
    
    protected static $_GroupName = 'jquery';


    /**
     * Version number correspond to the version used to write Iris-PHP.
     * This number is only used in remote URL. With local source, only
     * one version is supposed to installed in public/js folder.
     * 
     * @var string 
     */

    const VERSION = '1.9.0';

       

    
    protected function _init() {
        \Iris\SysConfig\StandardSetting::CreateSetting('version', self::VERSION);
    }

}
