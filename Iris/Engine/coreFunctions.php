<?php

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
 * This file contains 3 functions
 * 
 *      - __autoload
 *      - iris_assert
 *      - iris_debug
 * 
 */

/**
 * This magic function load a class file automatically 
 * 
 * @param string $className The class to load
 */
function __autoload($className) {
    \Iris\Engine\Loader::GetInstance()->loadClass($className);
}

/**
 * A required function for the assert mechanism
 * 
 * @param string $script
 * @param int $line
 * @param string $message
 */
function iris_assert($script, $line, $message) {
    $msg = "Error in <b>$script</b> at line <b>$line</b><br/>";
    list($expression, $comment) = explode('//', $message . '//');
    $msg .= "Tested expression: <b>$expression</b><br/>";
    $msg .= "Message : $comment";
    die($msg);
}

/**
 * 
 * @param mixed $var A printable message or variable
 * @param boolean $die If true, program dies
 * @param string $Message The message to display in die instruction
 */
function iris_debug($var, $die = TRUE, $Message = NULL) {
    if ($die) {
        \Iris\Engine\Debug::DumpAndDie($var, $Message, 1);
    }
    else {
        \Iris\Engine\Debug::Dump($var);
    }
}

assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_CALLBACK, 'iris_assert');

?>