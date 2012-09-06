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
 * Some functions are specifics or behave differently in Windows.
 * Futhermore the is no Windows but WindowsS
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * See commentary in namespace description about Windows as an extension of
 * Unix. The joke apart, it is a way to simplify.
 * 
 */
class Windows extends _OS{
    
    /**
     *
     * @param type $target
     * @param type $link 
     * @todo remove the exception and treat symlinks according to 
     * windows version
     */
    public function symlink($target, $link) {
        throw new \Iris\Exceptions\NotSupportedException('No linking possible in Windows');
    }

    /**
     *
     * @param type $from_path
     * @param type $to_path 
     * @todo remove the exception and treat links according to 
     * windows version
     */
    public function link($from_path, $to_path) {
        throw new \Iris\Exceptions\NotSupportedException('No linking possible in Windows');
    }

    /**
     * Get user home directory 
     * 
     * @return string
     */
    public function getUserHomeDirectory(){
        throw new \Iris\Exceptions\NotSupportedException('No linking possible in Windows');
        return \NULL;
    }

    public function fullPermission($fileName) {
        throw new \Iris\Exceptions\NotSupportedException('Permissions has to be implemented');
    }
}

?>
