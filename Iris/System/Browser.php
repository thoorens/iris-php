<?php

namespace Iris\System;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * Idenitifier for the browser
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Browser {

    const NO_VERSION = -1;
    const UNKOWN = 'unknown';
    const NONE = 'none';

    protected static $_EngineName = self::UNKOWN;
    protected static $_Version = self::NO_VERSION;

    public static function GetBrowser() {
        if (self::$_EngineName === self::UNKOWN) {
            $data = \Iris\Engine\Superglobal::GetServer('browser', self::NONE . '-' . self::NO_VERSION);
            list($browser, $version) = explode('-', $data);
            if ($browser == self::NONE) {
                self::Explore();
            }
        }
        //i_dnd(self::$_EngineName . " : ".self::$_Version);
        return [self::$_EngineName, self::$_Version];
    }

    public static function Explore($userAgent = \NULL) {
        if ($userAgent !== \NULL) {
            print("<em>$userAgent</em> <br>");
            $test = \TRUE;
        }
        else{
            $userAgent = \Iris\Engine\Superglobal::GetServer('HTTP_USER_AGENT');
            $test = \FALSE;
        }
        // TODO verify order
        $engines = [
            // 'Blink' in Chrome (no information)
            'Presto', // in old Opera
            'Edge',
            'WebKit',
            'Gecko',
            'Trident',
        ];
        self::$_EngineName = self::NONE;
        self::$_Version = self::NO_VERSION;
        foreach($engines as $engine){
            $pos = strpos($userAgent,$engine);
            if($pos !== \FALSE){
                $str = substr($userAgent, $pos + strlen($engine));
                self::$_EngineName = $engine;
                //i_dnd($str);
                $pos = preg_match('/[\.\d]+/', $str, $matches);
                if ($pos > 0) {
                    self::$_Version = $matches[0];
                }
                continue;
            }
        }
        //i_dnd($userAgent);
        if($test) return [self::$_EngineName, self::$_Version];
    }

}
