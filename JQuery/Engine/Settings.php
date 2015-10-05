<?php

namespace JQuery\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class is intended to control the settings of the JQuery library.
 * Presently, it only contains static public variables.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
class Settings {

    protected static $_GroupName = 'jquery';

    /**
     * Version number correspond to the version used to write Iris-PHP.
     * This number is only used in remote URL. With local source, only
     * one version is supposed to be installed in public/js folder.
     * 
     * @var string 
     */
    public static $Version = '1.9.0';

}
