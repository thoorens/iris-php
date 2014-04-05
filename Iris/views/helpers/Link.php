<?php

namespace Iris\views\helpers;
use Iris\Subhelpers\Link as SubLink;

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
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *
 */

/**
 *
 *
 * Creates a button which links to a page or site
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Link extends \Iris\views\helpers\_ViewHelper {

    /**
     * @param string $label link label
     * @param string $url target URL
     * @param string $tooltip tooltip when mousevoer
     * @param string $class class of the link
     * @param string $id id of the linklabel
     * @return string
     */
    public function help($label = \NULL, $url = \NULL, $tooltip = \NULL, $class = \NULL, $id = \NULL) {
        $subhelper = SubLink::GetInstance();
        $args = func_get_args();
        return $subhelper->autoRender($args);
    }

}

