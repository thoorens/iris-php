<?php

namespace Iris\OS;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * No function are specific, but some people may be sad
 * to view Windows as a specific extension of Unix. 
 * So _OS is Unix in reality
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * See commentary in namespace description about Windows as an extension of
 * Unix. The joke apart, it is a way to simplify.
 */
class Unix extends _OS {

    

    /**
     * Get user home directory 
     * 
     * @return string
     */
    public function getUserHomeDirectory() {
        return getenv('HOME');
    }

}


