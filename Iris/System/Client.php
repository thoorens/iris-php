<?php



namespace Iris\System;

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
 */

/**
 * Inspect browser for knowing its prefered language, type and version ...
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Client {
    // browsers as number
    const UNKNOWN = 0;
    const FIREFOX = 1;
    const SAFARI = 2;
    const IE = 3;
    const KONQUEROR = 4;
    const REKONQ = 5;
    const OPERA = 6;
    const CHROME = 7;
    const EPIPHANY = 8;
    const LYNX = 9;

    const MAJOR = 1; // the major version number
    const MINOR = 2; // the minor version number
    const FULL = 3;  // major and minor separated by period
    const ARRAY_ = 4; // major and minor in an array

    /**
     * Returns the client browser version number (full 11.0, major 11, 
     * minor 0 or as an array of two numbers).
     * 
     * @param int $mode The version number presentation 
     * @return mixed The version number
     */
    public function getVersion($mode=self::FULL) {
        // reg exp for identify version number
        switch ($mode) {
            case self::FULL:
                $number = "(\d+\.\d+)"; //e.g. 11.0
                break;
            case self::MAJOR:
                $number = "(\d+)\.\d+"; // e.g. 11 
                break;
            case self::MINOR:
                $number = "\d+\.(\d+)"; // e.g 0
                break;
            case self::ARRAY_:
                $number = "(\d+)\.(\d+)"; // e.g. array('11','0')
                break;
        }
        $browser = $this->getClient(TRUE);
        switch ($this->getClient()) {
            case self::IE:
                // MSIE x.y;
                $pattern = "/MSIE\ $number/";
                break;
            case self::REKONQ:
            case self::EPIPHANY:
            case self::CHROME:
            case self::SAFARI:
                // AppleWebKit/x.y
                $pattern = "/AppleWebKit\/$number/";
                break;
            case self::FIREFOX:
            case self::KONQUEROR:
            case self::LYNX:
                // BrowerName/x.y
                $pattern = "/$browser\/$number/";
                break;
            case self::OPERA:
                // Version/x.y
                $pattern = "/Version\/$number/";
                break;
            default :
                return $this->_extendedVersion($mode);
        }
        $userAgent = \Iris\Engine\Superglobal::GetServer('HTTP_USER_AGENT');
        $array = array();
        preg_match($pattern, $userAgent, $array);
        // in this mode, returns both major and minor in an array
        if ($mode == self::ARRAY_) {
            array_shift($array); // [0] is full string
            return $array;
        }
        // return the matched number as requested in $number
        return $array[1];
    }

    /**
     * Returns the first language used by the browser as a two uppercase
     * character string
     * @return string 
     */
    public function getLanguage($full=\FALSE) {
        /* Chrome : fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4  */
        /* Firefox : fr-fr,fr;q=0.8,en-us;q=0.5,en;q=0.3 | Firefox/11.0fr,fr-fr;q=0.8,en-us;q=0.5,en;q=0.3  */
        /* Safari : fr-FR */
        /* Konqueror : fr,en-US;q=0.9,en;q=0.8 */
        /* Rekonq : fr, en-US; q=0.8, en; q=0.6  */
        /* Opera : fr-FR,fr;q=0.9,en;q=0.8  */
        /* IE 8 : fr-BE  */
        /* IE5.0 :  fr  */
        /* IE6 :  fr  */
        $language = \Iris\Engine\Superglobal::GetServer('HTTP_ACCEPT_LANGUAGE', NULL);
        if (is_null($language)) {
            return "??";
        }
        $lang2 = strtoupper(substr($language, 0, 2));
        if (!$full) {
            return $lang2;
        }
        else {
            switch ($lang2) {
                case 'FR':
                    return 'French';
                case 'EN':
                    return 'English';
                case 'SP':
                    return 'Spanish';
                default:
                    return 'English';
            }
        }
    }

    /**
     * Returns the client browser as a number or a readable string
     * 
     * @param boolean $text
     * @return miexed then identitied browser
     */
    public function getClient($text=FALSE) {
        $client = $this->_getClient();
        if (!$text) {
            return $client;
        }
        else {
            switch ($client) {
                case self::OPERA:
                    return "Opera";
                case self::CHROME:
                    return "Chrome";
                case self::FIREFOX:
                    return "Firefox";
                case self::REKONQ:
                    return "Rekonq";
                case self::KONQUEROR:
                    return "Konqueror";
                case self::SAFARI:
                    return "Safari";
                case self::IE:
                    return "Internet Explorer";
                case self::EPIPHANY:
                    return "Epiphany";
                case self::LYNX:
                    return "Lynx";
                default:
                    return $this->_getExendedClientName($client);
            }
        }
    }

    /**
     * Returns a code for each type of browser
     * @return int
     */
    protected function _getClient() {
        $userAgent = \Iris\Engine\Superglobal::GetServer('HTTP_USER_AGENT');

        $extended = $this->_getExtendedClient();
        if ($extended != self::UNKNOWN) {
            return $extended;
        }

        /* Lynx
         * Lynx/2.8.8dev.9 
         * libwww-FM/2.14 SSL-MM/1.4.1 
         * GNUTLS/2.10.5 
         */
        if (strpos($userAgent, 'Lynx') !== FALSE) {
            return self::LYNX;
        }
        /* Epiphany
         * Mozilla/5.0 
         * (X11; Linux i686) 
         * AppleWebKit/534.26+ 
         * (KHTML, like Gecko) 
         * Version/5.0 
         * Safari/534.26+ 
         * Ubuntu/11.04 
         * (3.0.4-1ubuntu1) 
         * Epiphany/3.0.4
         */
        if (strpos($userAgent, 'Epiphany') !== FALSE) {
            return self::EPIPHANY;
        }
        /* Chromium 
         * Mozilla/5.0 
         * (X11; Linux i686) 
         * AppleWebKit/535.19 
         * (KHTML, like Gecko) 
         * Ubuntu/11.10 
         * Chromium/18.0.1025.151 
         * Chrome/18.0.1025.151 
         * Safari/535.19
         */
        /* Chrome
         * Mozilla/5.0 
         * (X11; Linux i686) 
         * AppleWebKit/535.19 
         * (KHTML, like Gecko) 
         * Chrome/18.0.1025.162 
         * Safari/535.19
         */
        if (strpos($userAgent, 'Chrome') !== FALSE) {
            return self::CHROME;
        }

        /* Rekonq
         * Mozilla/5.0 
         * (X11; Linux i686) 
         * AppleWebKit/534.34 
         * (KHTML, like Gecko) 
         * rekonq 
         * Safari/534.34
         */
        if (strpos($userAgent, 'rekonq') !== FALSE) {
            return self::REKONQ;
        }

        /* Safari
         * Mozilla/5.0 (Windows NT 6.1) 
         * AppleWebKit/534.55.3 
         * (KHTML, like Gecko) 
         * Version/5.1.5 
         * Safari/534.55.3
         */
        if (strpos($userAgent, 'AppleWebKit') !== FALSE) {
            return self::SAFARI;
        }

        /* Firefox 11.0
         * Mozilla/5.0 
         * (X11; Linux i686; rv:11.0) | (Windows NT 6.1; rv:11.0)
         * Gecko/20100101 
         * Firefox/11.0
         */
        if (strpos($userAgent, 'Firefox') !== FALSE) {
            return self::FIREFOX;
        }



        /* Konqueror
         * Mozilla/5.0 
         * (X11; Linux i686) 
         * KHTML/4.7.4 (like Gecko) 
         * Konqueror/4.7
         */
        if (strpos($userAgent, 'Konqueror') !== FALSE) {
            return self::KONQUEROR;
        }




        /* Opera
         * Opera/9.80 
         * (X11; Linux i686; U; fr) 
         * Presto/2.10.229 
         * Version/11.62
         */
        if (strpos($userAgent, 'Opera') !== FALSE) {
            return self::OPERA;
        }


        /* IE 8
         * Mozilla/4.0 
         * (compatible; 
         * MSIE 8.0; 
         * Windows NT 6.1; 
         * Trident/4.0; SLCC2; 
         * .NET CLR 2.0.50727; 
         * .NET CLR 3.5.30729; 
         * .NET CLR 3.0.30729; 
         * Media Center PC 6.0; 
         * .NET4.0C)
         * 
         * IE5.0
         * Mozilla/4.0 (compatible; MSIE 5.01; Windows 98)
         *
         * IE5.5
         * Mozilla/4.0 (compatible; MSIE 5.5; Windows 98) 
         * 
         * IE 6
         * Mozilla/4.0 (compatible; MSIE 6.0; Windows 98) 
         */
        if (strpos($userAgent, 'MSIE') !== FALSE) {
            return self::IE;
        }
        return self::UNKNOWN;
    }

    /**
     * Override this method to add other browsers
     * 
     * @return int 
     */
    protected function _getExtendedClient() {
        return self::UNKNOWN;
    }

    /**
     * Override this method to add other browsers
     * 
     * @return string 
     */
    protected function _getExendedClientName($browser) {
        switch ($browser) {
            default:
                return "Unkown";
        }
    }

    /**
     * Override this method to add other browsers
     * 
     * @param int $mode 
     */
    public function _extendedVersion($mode) {
        if ($mode == self::ARRAY_) {
            return array('?', '?');
        }
        elseif ($mode == self::FULL) {
            return "?.?";
        }
        else {
            return "?";
        }
    }

}

?>
