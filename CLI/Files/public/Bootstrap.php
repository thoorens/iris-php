<?php
namespace Iris\Engine;

define('IRIS_ROOT_PATH', dirname(__DIR__));
define('IRIS_PUBLIC_PATH', __DIR__);
define('IRIS_LIBRARY','{LIBRARY}');
set_include_path(IRIS_ROOT_PATH . PATH_SEPARATOR . get_include_path());


require_once IRIS_LIBRARY.'/Iris/Engine/Bootstrap.php';

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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $/**
 * This class is just a subclass of core_Bootstrap
 * for customisation purpose.
 * 
 */
class Bootstrap extends core_Bootstrap {
    
    /* specify here (in good order) the files in config
       to be read, otherwise all files will be read */

    protected $_configToRead = array(
    );

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


