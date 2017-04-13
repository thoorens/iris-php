<?php

namespace Vendors\W3CSS;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 * This class 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
class Settings {

    const PUBLICDIR = 3;
    const LOCAL = 2;
    const DISTANT = 1;
    
    public static $DownloadMode = self::DISTANT;
    
    public static $PublicURL = "css/w3.css";
    public static $LocalURL = "!documents/file/internal/1/w3.css"; 
    public static $W3CURL = "/www.w3schools.com/lib/w3.css";

}
