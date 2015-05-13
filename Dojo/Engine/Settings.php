<?php

namespace Dojo\Engine;

use Dojo\Manager;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This class 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
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
    const VERSION = '1.10.4';
    //const VERSION = '1.9.0';

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
     * By default Dojo is parsed on page load
     * @var type 
     */
    public static $ParseOnLoad = 'true';
    
    
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
