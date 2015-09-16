<?php

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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


require_once $irisEngineDir . 'coreFunctions.php';
include_once $irisLibrary . '/Iris/Design/iSingleton.php';
include_once $irisEngineDir . '/tSingleton.php';
include_once $irisEngineDir . '/Debug.php';
include_once $irisEngineDir . '/PathArray.php';
include_once $irisEngineDir . '/Mode.php';
include_once $irisEngineDir . '/_coreLoader.php';
// If you use your own copy of LoadLoader, this is the place to specify
// the path to your personal version of \Iris\Engine\Loader
$customDir = $irisEngineDir;
include_once $customDir . '/Loader.php';
