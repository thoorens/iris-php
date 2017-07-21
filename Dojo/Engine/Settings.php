<?php

namespace Dojo\Engine;

use Dojo\Manager;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This class contains all the Dojo settings
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 */
class Settings {

    protected static $_GroupName = 'dojo';

    /**
     * Version number correspond to the version used to write Iris-PHP.
     * This number is only used in remote URL. With local source, only
     * one version is supposed to installed in public/js folder.
     * 
     * @var string 
     */
    const VERSION = '1.12.2'; //Last version on Google (2017/05/04)
    const URL_DOJO_GOOGLE = "https://ajax.googleapis.com/ajax/libs/dojo/";

    /**
     * A Dojo theme : claro
     */
    const CLARO = 'claro';

    /**
     * A Dojo theme : nihilo
     */
    const NIHILO = 'nihilo';

    /**
     * A Dojo theme : tundra
     */
    const TUNDRA = 'tundra';

    /**
     * A Dojo theme : soria
     */
    const SORIA = 'soria';

    /**
     * A value for a form factory
     */
    const DOJO = 6;

    /**
     * The version of Dojo is defined here
     * @var type 
     */
    public static $Version = self::VERSION;

    /**
     * The debug mode of dojo
     * To be defined by __ClassInit
     * @var string 
     */
    public static $Debug;

    /**
     * The dojo style used in the program among the 4 standard styles: 
     * nihilo,soria, tundra, claro
     * @var string 
     */
    public static $Theme = self::NIHILO;

    /**
     * The default repository for Dojo libraries : GoogleApis
     * @var int 
     */
    public static $Source = Manager::GOOGLE;

    /**
     * By default Dojo is parsed on page load (the value is JS syntax)
     * 
     * @var string 
     */
    public static $ParseOnLoad = 'true';

    /**
     * Indicates that portions of javascript will bear a signature
     * 
     * @var boolean
     */
    public static $JSDebug = \TRUE;

    /**
     * Defines some standard settings with a default value
     */
    public static function __ClassInit() {
        // Debug info is set according to site type 
        if (\Iris\Engine\Mode::IsDevelopment()) {
            self::$Debug = 'true'; // true is js code
        }
        else {
            self::$Debug = 'false'; // false is js code
        }
    }

}
