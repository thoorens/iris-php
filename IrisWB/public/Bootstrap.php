<?php

namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/*
 * The server document roots consists of 3 links to <ul>
 * <li>library/IrisWB/application
 * <li>library/IrisWB/public
 * <li>library
 * </ul>
 * Because putting a link to library in library/IrisWB creates a recursive
 * directory (my IDE does not like that)
 * So for the library to be found, we need an absolute definition of IRIS_ROOT_PATH.
 */
// Define IRIS_STARTTIME if not done in index.php
defined('IRIS_STARTTIME') or define('IRIS_STARTTIME', microtime());

define('IRIS_ROOT_PATH', dirname($_SERVER["DOCUMENT_ROOT"]));
define('IRIS_PUBLIC_PATH', __DIR__);
define('IRIS_LIBRARY', 'library');
set_include_path(IRIS_ROOT_PATH . PATH_SEPARATOR . get_include_path());
define('IRIS_WB', IRIS_ROOT_PATH . '/library/IrisWB/application/config/base/config.sqlite');
require_once IRIS_LIBRARY . '/Iris/Engine/Bootstrap.php';

/**
 * An important file, required for starting the application
 * and make some customisation. Left unmodified from project creation
 * (except copyright notice)
 * 
 */
class Bootstrap extends core_Bootstrap {
    /* specify here (in good order) the files in config
      to be read, otherwise all files will be read */

    protected $_configToRead = array(
    );

    // You can modify here the class Loader path
    //protected $_standardLoaderPath = 'library/Iris/Engine/Loader.php';
    // You add here any call to  initialising routines
    public function init() {
        // for instance add a library
        //\Iris\Engine\Loader::GetInstance()->insertAltPath('MyIris');
        //\Iris\Engine\Loader::GetInstance()->setTransapplicationName('Trans');
    }

    // You can add here some debbuging tools and parameters
    public function debug() {
        
    }

    /**
     * Adds entity manager settings corresponding to numbers 97 or go to standard InternaleDB
     * 
     * @param int $entityNumber
     * @return string[] an array containing 5 parameters for the creation of the entity manager
     * @throws \Iris\Exceptions\DBException
     */
    public static function InternalDB($entityNumber) {
        if ($entityNumber > 97) {
            $params = \Iris\SysConfig\Settings::InternalDB($entityNumber);
        }
        else {
            switch ($entityNumber) {
                case 97:
                    $params = [
                        'type' => \Iris\DB\_EntityManager::SQLITE,
                        'id' => $entityNumber,
                        'username' => \NULL,
                        'passwd' => \NULL,
                        'dsn' => 'sqlite:' . IRIS_ROOT_PATH . "/application/config/base/config.sqlite"
                    ];
                    break;
                default:
                    throw new \Iris\Exceptions\DBException('EM number still not developed');
            }
        }
        return $params;
    }

}
