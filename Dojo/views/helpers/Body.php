<?php

namespace Dojo\views\helpers;

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
 *
 */

/**
 * This helper adds a class to <body> so that Dojo could
 * use the good CSS. 
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Body extends \Iris\views\helpers\_ViewHelper {

    
    /**
     * The style of dojo widget may be set in body tag
     * No effect if no Dojo tool is used in the page.
     * 
     * @return string 
     */
    public function help() {
        $manager = \Dojo\Manager::GetInstance();
        if (!$manager->isActive())
            return '';
        $style = $manager->getStyle();
        return " class=\"$style\"";
    }

}

?>
