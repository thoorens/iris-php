<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Debug offers a way to dump variable during development
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $     * 
 * 
 */
abstract class Debug {

    const NONE = 0;
    const ALL = 65535; // Warning: -1 is identified as 127
    const LOADER = 1;
    const ROUTE = 2;
    const DB = 4;
    const VIEW = 8;
    const HELPER = 16;
    const ACL = 32;
    const FILE = 64;
    const SETTINGS = 128;
    const HTML5 = 256;

    /**
     * Display a var_dump between &lt;pre> tags
     * @param mixed $var : a var or text to dump
     */
    public static function Dump($var, $message = '') {
        echo "<pre>\n";
        echo $message;
        \var_dump($var);
        echo "</pre>\n";
    }

    /**
     * Display a var_dump between &lt;pre> tags and die
     *
     * @param mixed $var A printable message or variable
     * @param string $dieMessage
     * @param int $traceLevel permits to find the file and line containing the call
     * if called from iris_debug, trace level is 1 instead of 0
     * if called from i_d() or its alias, trace level is 2 from i_d()
     */
    public static function DumpAndDie($var, $dieMessage = NULL, $traceLevel = 0) {
        if (is_null($dieMessage)) {
            $trace = debug_backtrace();
            $dieMessage = sprintf('Debugging interrupt in file <b> %s </b> line %s', $trace[$traceLevel]['file'], $trace[$traceLevel]['line']);
        }
        self::Dump($var);
        self::Abort($dieMessage);
    }

    /**
     * A substitute for die.
     * 
     * @param string $message
     */
    public static function Abort($message = NULL) {
        die($message); // die is translated to Kill
    }

    
    /**
     * Error box with title and message, for error debugging purpose
     * Should not be used in a production environment. This box is used
     * when an error occurs in error processing for an cry of despair.
     * 
     * @param string $message : error description
     * @param string $title : box title
     * @return string 
     */
    public static function ErrorBox($message, $title = "Unkown class") {
        $text = '<div style="background-color:#EF5B4F; color:#FFFFFF; margin:10px; padding:5px\">';
        $text .= "&nbsp;<strong>IRIS-PHP SYSTEM ERROR : $title</strong><hr>";
        $text .= '<pre style="background-color:#DDD;color:#800;margin:10px;font-size:0.8em;">';
        $text .= $message . '</pre><p style="margin-top:-15px">&nbsp;</p></div>';
        return $text;
    }
    
    /**
     * Same as ErrorBox, except that it does not return  the box text but displays it
     * at once and kill the program
     * 
     * @param string $message : error description
     * @param string $title : box title
     */
    public static function ErrorBoxDie($message, $title = "Unkown class"){
        $text = self::ErrorBox($message, $title);
        self::Abort($text);
    }
}

