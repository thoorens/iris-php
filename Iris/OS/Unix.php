<?php

namespace Iris\OS;

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

?>
