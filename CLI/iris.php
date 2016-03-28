#! /usr/bin/env php
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
 * This is the main entry for the command line interpreter (CLI)
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $
 */

define('IRIS_LIBRARY_DIR', dirname(dirname(__FILE__)));

//require_once IRIS_LIBRARY_DIR.'/CLI/FrontEnd.php';
//CLI\FrontEnd::GetInstance()->testArgs();


require_once IRIS_LIBRARY_DIR.'/CLI/FrontEnd.php';
CLI\FrontEnd::GetInstance()->run();
