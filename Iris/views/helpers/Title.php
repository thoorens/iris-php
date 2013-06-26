<?php

namespace Iris\views\helpers;

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
 * @version $Id: $ * 
 */

/**
 * Gives facilities to manage the &gt;title> of a page.
 * Subtitle can be managed more easily by subtitle() helper.
 *
 */
final class Title extends _ViewHelper {

    protected static $_Singleton = TRUE;

    /**
     * Permits to set the main title and the subtitle of the page
     * To set the subtitle only use the subtitle() helper instead.
     * 
     * @param string $mainTitle
     * @param string $subtitle
     */
    public function help($mainTitle = \NULL, $subtitle = \NULL) {
        $head = \Iris\Subhelpers\Head::GetInstance();
        if (!is_null($mainTitle)) {
            $head->setTitle($mainTitle);
        }
        if (!is_null($subtitle)) {
            $head->setSubtitle($subtitle);
        }
    }

}

?>
