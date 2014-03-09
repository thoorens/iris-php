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
 * @copyright 2011-2014 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

namespace Iris\Engine;

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

    /**
     * Display a var_dump between &lt;pre> tags
     * @param mixed $var : a var or text to dump
     */
    public static function Dump($var) {
        echo "<pre>\n";
        var_dump($var);
        echo "</pre>\n";
    }

    /**
     * Display a var_dump between &lt;pre> tags and die
     *
     * @param mixed $var A printable message or variable
     * @param string $dieMessage
     * @param int $traceLevel If called from iris_debug, trace level is 1 instead of 0
     */
    public static function DumpAndDie($var, $dieMessage = NULL, $traceLevel = 0) {
        if (is_null($dieMessage)) {
            $trace = debug_backtrace();
            $dieMessage = sprintf('Debugging interrupt in file <b> %s </b> line %s', $trace[$traceLevel]['file'], $trace[$traceLevel]['line']);
        }
        self::Dump($var);
        self::Kill($dieMessage);
    }

    /**
     * A substitute for die.
     * 
     * @param string $message
     */
    public static function Kill($message = NULL) {
        die($message); // the only authorized die in programm
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
        self::Kill($text);
    }
}

