<?php
namespace Iris\Engine;

define('IRIS_ROOT_PATH', dirname(__DIR__));
define('IRIS_PUBLIC_PATH', __DIR__);
define('IRIS_LIBRARY','{LIBRARY}');
set_include_path(IRIS_ROOT_PATH . PATH_SEPARATOR . get_include_path());


require_once IRIS_LIBRARY.'/Iris/Engine/Bootstrap.php';

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/* 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 * This class is just a subclass of core_Bootstrap
 * for customisation purpose.
 * 
 */
class Bootstrap extends core_Bootstrap {
    
    /* specify here (in good order) the files in config
       to be read, otherwise all files will be read */

    protected $_configToRead = [
    ];

    // You can modify here the class Loader path starting from IRIS_LIBRARY
    //protected $_standardLoaderPath = 'Iris/Engine/Loader.php';
    
    
    // You add here any call to  initialising routines
    public function init() {
        // for instance add a extension library
        //\Iris\Engine\Loader::GetInstance()->insertAltPath('MyIris');
        // or add a transapplication library
        //\Iris\Engine\Loader::GetInstance()->setTransapplicationName('Trans');
    }

    // You can add her some debbuging tools and parameters
    // This can also be done in a config file
    public function debug() {
    }

}


