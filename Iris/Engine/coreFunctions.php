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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * This file defines assert options and contains 6 functions 
 * <ul>
 *    <li> __autoload
 *    <li> iris_assert
 *    <li> iris_debug
 *    <li> iris_debug_member
 *    <li> iris_member
 *    <li> iris_print
 * </ul>
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
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
 * @param string $message The message to display in die instruction
 */
function iris_debug($var, $die = \TRUE, $message = \NULL) {
    if ($die) {
        \Iris\Engine\Debug::DumpAndDie($var, $message, 1);
    }
    else {
        \Iris\Engine\Debug::Dump($var);
    }
}
/**
 * Shows an object member value, even if it is protected or private
 * 
 * @param Object $object An object to inspect
 * @param string $memberName A member name (may be not public)
 * @param boolean $die If true, program dies
 * @param string $message The message to display in die instruction
 */
function iris_debug_member($object, $memberName, $die = \TRUE, $message = \NULL){
    $value = iris_member($object, $memberName);
    iris_debug($value, $die, $message);
}

/**
 * Returns the valeuyr of a member of an objet or class, 
 * even if it is protected or private. A debugging tool
 * 
 * @param Object $object An object to inspect
 * @param string $memberName A member name (may be not public)
 * @return mixed
 */
function iris_member($object, $memberName){
    $reflectionObject = new \ReflectionObject($object);
    $refProp = $reflectionObject->getProperty($memberName);
    $refProp->setAccessible(\TRUE);
    $value = $refProp->getValue($object);
    return $value;
}

/**
 * Echoes a line (with a final br tag)
 * @param string $message
 */
function iris_print($message){
    echo "$message<br/>";
}

/**
 * Prints a backtrace (all levels of call to subroutine) and stops
 * the program execution
 * 
 * @param boolean $die if not true, does not stop the program
 */
function iris_context($die = \TRUE){
    iris_debug(debug_backtrace(), $die);
}

/**
 * Prints a backtrace  item (one single level of call to subroutine) and stops
 * the program execution
 * 
 * @param boolean $die if not true, does not stop the program
 */
function iris_contextItem($level, $die = \TRUE){
    iris_debug(debug_backtrace()[$level], $die);
}

assert_options(ASSERT_BAIL, 1);
assert_options(ASSERT_CALLBACK, 'iris_assert');

