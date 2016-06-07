<?php

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS 
 */

/** 
 * This files is used to load important files and classes 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */
$irisLibrary = IRIS_ROOT_PATH . '/' . IRIS_LIBRARY;
$irisEngineDir = "$irisLibrary/Iris/Engine/";



// If you use your own copy of LoadLoader, this is the place to specify
// the path to your personal version of \Iris\Engine\Loader
$customDir = $irisEngineDir;
$coreClasses = [
    'coreFunctions.php',
    'Debug.php',
    '/Iris/Design/iSingleton.php',
    'tSingleton.php',
    'Log.php',
    'LogItem.php',
    '/Iris/SysConfig/Settings.php',
    'PathArray.php',
    'Mode.php',
    '_coreLoader.php',
    '!Loader.php'
];
foreach($coreClasses as $class){
    switch($class[0]){
        case '/':
            $path = $irisLibrary . $class;
            break;
        case '!':
            $path =  $customDir . substr($class, 1);
            break;
        default:
            $path =  $irisEngineDir . $class;
    }
    if(defined('IRIS_CORE')) echo "Loading $path<br/>";
    include_once $path;    
}
Iris\SysConfig\Settings::__ClassInit();
if(defined('IRIS_CORE'))die('OK');
