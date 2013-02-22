<?php
namespace ILO\views\helpers;
use \Iris\views\helpers\_ViewHelper;

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
 * @copyright 2011-2013 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */

/**
 * This helper displays an IRIS logo.
 *
 */
class Logo extends _ViewHelper {

    /**
     * This helper is a singleton
     * @var boolean
     */
    protected static $_Singleton = TRUE;
    
    
    /**
     * Displays a logo situated in ILO logos folder (by default Title80)
     * 
     * @param string $logo
     * @return string The HTML string for the logo
     */
    public function help($logo='#default#') {
        if ($logo == '#default#') {
           $logo='Title80';
        }
        return $this->getView()->image("$logo.jpg", 'Logo Iris', NULL, '/!documents/file/resource/logos', 'logoIris');
    }

}

?>
