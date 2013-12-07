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
class Settings extends _Settings {

    protected static $_GroupName = 'main';
    protected static $_Instance = \NULL;

    protected function _init() {
        // Admin toolbar may be Ajax or simple javascript 
        BooleanSetting::CreateSetting('adminToolbarAjaxMode', \TRUE);
        // Pages usually don't have a MD5 signature (usefull in debugging or caching)
        BooleanSetting::CreateSetting('MD5Signature', \FALSE);
        // In development, it may be usefull to compute Program Time Excecution, 
        // by default managed by Javascript (not Ajax)
        BooleanSetting::CreateSetting('displayRuntimeDuration', \TRUE);
        StandardSetting::CreateSetting('runtimeDisplayMode', \Iris\Time\RuntimeDuration::INNERCODE);
        // Unformated dates use japanese format as in 2012-12-31
        StandardSetting::CreateSetting('dateMode', \Iris\Time\TimeDate::JAPAN); // could be 'japan'
        // The default time zone is Brussels
        StandardSetting::CreateSetting('defaultTimezone', 'Europe/Brussels');
        // All Ajax functions need a library to manage them, by default it is Dojo
        StandardSetting::CreateSetting('defaultAjaxLibrary', '\\Dojo\\Ajax\\');
        // The slideshow is javascript based (by default trough Dojo)
        StandardSetting::CreateSetting('slideShowManagerLibrary', '\\Dojo\\');
        // If ACL are used, the default user is named 'somebody' in 'browse' group
        StandardSetting::CreateSetting('defaultUserName', 'somebody');
        StandardSetting::CreateSetting('defaultRoleName', 'browse');
        StandardSetting::CreateSetting('defaultUserEmail', 'info@irisphp.org');
        // 
        StandardSetting::CreateSetting('errorDebuggingLevel', 1);
        // To minimize execution templates can be cached (not by default)
        StandardSetting::CreateSetting('cacheTemplate', \Iris\MVC\Template::CACHE_NEVER);
        // Some icons are used internally (e.g. in CRUD), a default directory is specified
        StandardSetting::CreateSetting('iconSystemDir', '/!documents/file/images/icons');
        // By default all messages are in US english
        StandardSetting::CreateSetting('defaultLanguage', 'en-us');
        // Defaults settings for menu
        StandardSetting::CreateSetting('menuActiveClass', 'active');
        StandardSetting::CreateSetting('menuMainTag', 'ul');
        StandardSetting::CreateSetting('buttonMenuMainTag', 'div');
        // Default settings for database
        // Sqlite : create the file if not existing
        BooleanSetting::CreateSetting('sqliteCreateMissingFile', \FALSE);
        // Default settings for forms
        StandardSetting::CreateSetting('defaultFormClass', '\\Iris\\Forms\\StandardFormFactory');
        // Module, controler and action names may contain '-'
        BooleanSetting::CreateSetting('admitDash', \FALSE);
        // The default folder for document files managed through the program
        StandardSetting::CreateSetting('dataFolder', IRIS_ROOT_PATH.'/data');
    }

}

// Auto init
//Settings::__ClassInit();