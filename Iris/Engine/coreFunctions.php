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
 *    <li> iris_assert_callback
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
function iris_assert_callback($script, $line, $code, $message) {
    $msg = "Error in <b>$script</b> at line <b>$line</b><br/>";
    //list($expression, $comment) = explode('//', $message . '//');
    $msg .= "Tested expression: <b>$code</b><br/>";
    $msg .= "Message : $message";
    \Iris\Engine\Debug::Abort($msg);
}

/**
 * 
 * @param mixed $var A printable message or variable
 * @param boolean $die If true, program dies
 * @param string $message The message to display in die instruction
 * @param int $traceLevel The trace level for DumpAndDie call will be 2 from i_d() and 3 from i_n()
 */
function iris_debug($var, $die = \TRUE, $message = \NULL, $traceLevel = 1) {
    if ($die) {
        \Iris\Engine\Debug::DumpAndDie($var, $message, $traceLevel);
    }
    else {
        \Iris\Engine\Debug::Dump($var);
    }
}
/**
 * An alias for iris_debug
 * 
 * @param mixed $var A printable message or variable
 * @param boolean $die If true, program dies
 * @param string $message The message to display in die instruction
 */
function i_d($var, $die = \TRUE, $message = \NULL){
    iris_debug($var, $die, $message, 2);
}

/**
 * Another alias for iris_debug with no death value
 * 
 * @param mixed $var A printable message or variable
 * @param string $message The message to display in die instruction
 */
function i_dnd($var, $message = \NULL){
    iris_debug($var, \FALSE, $message, 2);
}

function i_n($number, $var){
    iris_nDebug($number, $var, \FALSE);
}

function i_nnd($number, $var){
    iris_nDebug($number, $var, \TRUE);
}

/**
 * A alias of iris_nDebug used to init its static var
 * 
 * @param int $number the number stopping the process
 * @param boolean $display order to display the preceding call
 */
function i_ninit($number, $display = \FALSE){
    iris_nDebug(-$number,'',$display);
}

/**
 * A progressive 
 * @staticvar int $stop will contains the stop value
 * @staticvar boolean $display if \TRUE each step will display information
 * @param int $number the step number
 * @param mixed $var the variable to display
 * @param mixed $message
 */
function iris_nDebug($number, $var, $message = \NULL){
    static $stop = 0;
    static $display = \FALSE;
    // when awaited value is passed as a parameter : displays the due var, the message, the file and the line number containing the call 
    if($number === $stop){
        \Iris\Engine\Debug::Dump($var,"STEP $number : " );
        $trace = debug_backtrace();
        \Iris\Engine\Debug::Abort(sprintf('Debugging interrupt in file <b> %s </b> line %s', $trace[1]['file'], $trace[1]['line']));
    }
    // if necessary, the preceding tests are also displayed
    else if($number < $stop and $display){
        \Iris\Engine\Debug::Dump($var,"STEP $number : " );
    }
    // if number is negative, used as  
    elseif($number<0){
        $start =abs($number); 
        $stop = $start;
        if (is_bool($message)) {
            $display = $message;
        }
    }
}

/**
 * Print a variable value with an optional message, if the message begins
 * with '!' it is printed before otherwise it is print after
 * 
 * @param mixed $var A printable message or variable
 * @param string $message The optional message to display
 */
function iris_dump($var, $message = \NULL){
    if(!is_null($message) and $message[0]=='!'){
        echo substr($message, 1);
        $message = \NULL;
    }
    \Iris\Engine\Debug::Dump($var);
    if(!is_null($message)){
        echo $message;
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
assert_options(ASSERT_CALLBACK, 'iris_assert_callback');

