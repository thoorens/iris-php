<?php
namespace Iris\SysConfig;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A new constant for password hash 
 */
define('PASSWORD_IRIS', 'IRIS');

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
class Settings {

    protected static $_GroupName = 'main';

    // The PHP subversion is the same as in User\Password but to avoid cross reference
    // it is safer to repeat the values

    const MODE_PHP54 = 4;
    const MODE_PHP55 = 5;

    /**
     * Management of special values
     */
    public static function __ClassInit() {
        // The default folder for document is relative to parameters in project creation
        self::$DataFolder = IRIS_ROOT_PATH . '/data';
        // By default use the PHP5.5 mode
        self::$DefaultHashType = self::MODE_PHP55;
        // By default the development RunTimeDuration is display by internal code, not Ajax
        self::$RuntimeDisplayMode = \Iris\Time\RuntimeDuration::INNERCODE;
        // The default date format is JAPAN (e.g. 2015-12-31
        self::$DateMode = \Iris\Time\TimeDate::JAPAN;
        // By default the view script templates are not cached
        self::$CacheTemplate = \Iris\MVC\Template::CACHE_NEVER;
        // By default the debug message will not be emited
        self::$DebugMode = \Iris\Engine\Log::POS_NONE;
    }

    /**
     * Imports settings from a Config or array of configs
     * 
     * @param Config $params
     * @throws \Iris\Exceptions\NotSupportedException
     */
    public static function FromConfigs($params) {
        throw new \Iris\Exceptions\NotSupportedException('The ini configuration of Settings is still not implemented');
    }

    /* =========================================================================
     * L I S T   O F    P A R A M E T E R S 
     * ======================================================================== */

    /* -------------------------------------------------------------------------
     * Parameters related to menus 
     * ------------------------------------------------------------------------- */

    /**
     * Default class used by menu
     * @var string
     */
    public static $MenuActiveClass = 'active';

    /**
     * Default main tag for the menu
     * @var string
     */
    public static $MenuMainTag = 'ul';

    /**
     * Default button tag for the menu
     * @var string
     */
    public static $ButtonMenuMainTag = 'div';


    /* -------------------------------------------------------------------------
     * Parameters related to users
     * ------------------------------------------------------------------------- */

    /**
     * If ACL are used, the default user is named 'somebody'
     * @var string
     */
    public static $DefaultUserName = 'somebody';

    /**
     * If ACL are used, the default user group is named 'browse'
     * @var string
     */
    public static $DefaultRoleName = 'browse';

    /**
     * If ACL are used, the default user email address is 'info@irisphp.org'
     * @var string
     */
    public static $DefaultUserMail = 'info@irisphp.org';

    /**
     * If ACL are used, no entity is defined in Settings (DIY)
     * @var string
     */
    public static $SystemUserEntity = '';

    /**
     * Type of Password conversion (PHP 5.5 internal conversion)
     * @var int
     */
    public static $DefaultHashType; //= self::MODE_PHP55;
    
    /* -------------------------------------------------------------------------
     * Parameters related to development
     * ------------------------------------------------------------------------- */

    /**
     * Admin toolbar may be Ajax or simple javascript
     * @var boolean 
     */
    public static $AdminToolbarAjaxMode = \TRUE;

    /**
     * Pages usually don't have a MD5 signature (usefull in debugging or caching)
     * @var boolean
     */
    public static $MD5Signature = \FALSE;

    /**
     * In development, it may be usefull to compute Program Time Excecution,
     * by default managed by Javascript (not Ajax)
     * @var boolean
     */
    public static $DisplayRuntimeDuration = \TRUE;

    /**
     * @var int
     */
    public static $RuntimeDisplayMode; //\Iris\Time\RuntimeDuration::INNERCODE;

    /**
     * Debugging level
     * @var int
     */
    public static $ErrorDebuggingLevel = 1;
    
    /**
     *
     * @var int
     */
    public static $InternalDatabaseNumber = 99;
    
    /**
     *
     * @var int
     */
    public static $AdDatabaseNumber = 98;

    /**
     *Debug position : will be inited to POS_NONE
     * @var int
     */
    public static $DebugMode = \NULL;
    

    /* -------------------------------------------------------------------------
     * Parameters related to databases
     * ------------------------------------------------------------------------- */
    
    
    public static $DefaultEntityMananagerClass = '\\Iris\\DB\\Dialects\\Em_PDOmySQL';
    
    public static $SqliteCreateMissingFile = \TRUE;
    
    /* -------------------------------------------------------------------------
     * Parameters related to forms
     * ------------------------------------------------------------------------- */
    
    /**
     * Default settings for forms
     * @var string 
     */
    public static $DefaultFormClass = '\\Iris\\Forms\\StandardFormFactory';

    
    /* -------------------------------------------------------------------------
     * Parameters related to date and time
     * ------------------------------------------------------------------------- */

    /**
     * Unformated dates use japanese format as in 2012-12-31
     * @var iint 
     */
    public static $DateMode; // \Iris\Time\TimeDate::JAPAN;

    /**
     * The default time zone is Brussels
     * @var string
     */
    public static $DefaultTimezone = 'Europe/Brussels';

    /* -------------------------------------------------------------------------
     * Parameters related to Ajax
     * ------------------------------------------------------------------------- */

    /**
     * All Ajax functions need a library to manage them, by default it is Dojo
     * @var string
     */
    public static $DefaultAjaxLibrary = '\\Dojo\\Ajax\\';

    /**
     * The slideshow is javascript based (by default trough Dojo)
     * @var string
     */
    public static $SlideShowManagerLibrary = '\\Dojo\\';

    /* -------------------------------------------------------------------------
     * Parameters related to languages and translation
     * ------------------------------------------------------------------------- */

    /**
     * By default all texts are in US english
     * @var string
     */
    public static $DefaultLanguage = 'en';

    /**
     * By default only english is available (otherwise, use a string like 'fr-en-es')
     * @var string
     */
    public static $AvailableLanguages = 'en';

    /**
     * The default translator is a mere reproducer
     * @var string
     */
    public static $DefaultTranslator = '\\Iris\\Translation\\NullTranslator';

    /* -------------------------------------------------------------------------
     * Parameters related to timeout
     * ------------------------------------------------------------------------- */

    /**
     * The default time out for development (4 hours)
     */
    public static $DefaultTimeout = 14400;

    /**
     * The default time out for production (10 minutes)
     */
    public static $ProductionTimeout = 600;
    
    /* -------------------------------------------------------------------------
     * Paraùeters relative to links, buttons, images and icons
     * ------------------------------------------------------------------------- */
    
    /**
     * The default folder for images files (relative to PUBLIC)
     * @var string
     */
    public static $ImageFolder = '/images';
    
    /**
     * Application icons are placed in an /images/icons folder
     * @var string 
     */
    public static $IconDir = '/icons';
    
    /**
     * The content of the label of a link, button, image or icon which cannot be
     * visible
     * 
     * @var type 
     */
    public static $NoLinkLabel = '!!!!NONE!!!!';
    
    /* -------------------------------------------------------------------------
     * Various parameters 
     * ------------------------------------------------------------------------- */

    /**
     * Module, controler and action names may contain '-' (by default not)
     * @var boolean
     */
    public static $AdmitDash = \FALSE;

    /**
     * To minimize execution templates can be cached (not by default)
     * @var int 
     */
    public static $CacheTemplate; // \Iris\MVC\Template::CACHE_NEVER;

    /**
     * The default folder for document files managed through the program (defined by _ClassInit)
     * @var string
     */
    public static $DataFolder;

    
}
