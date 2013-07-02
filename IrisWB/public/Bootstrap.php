<?php


namespace Iris\Engine;
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
define('IRIS_LIBRARY','library');
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
 * @version $Id: $ */

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

}

?>
