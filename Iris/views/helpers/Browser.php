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
 * @copyright 2012 Jacques THOORENS
 */

/**
 * This helper displays a set of icons for browsing an entity with classical
 * functions
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * @todo : verify the utility of this and suppress it in all layouts
 */
class Browser extends _ViewHelper {

    const UPDATE = 1;
    const DELETE = 2;
    const CREATE = 4;
    const FIND = 8;

    protected static $_Singleton = TRUE;


    /**
     * Displays first, previous, next, last buttons + one to 4 buttons for 
     * CRUD functions according to parameter $edit.
     * 
     * @param int $edit the requested edit functions 
     * @param boolean $active: if false, the browser is not active
     * @return string html code
     */
    public function help($edit=15, $active=TRUE) {
        $crudHelper = \Iris\Subhelpers\Crud::GetInstance(NULL);
        $html = '<span class="Iris_Browser">';
        $html .= $crudHelper->render('first', $active);
        $html .= $crudHelper->render('previous', $active);
        if ($edit & self::UPDATE)
            $html .= $crudHelper->render('update', $active);
        if ($edit & self::DELETE)
            $html .= $crudHelper->render('delete', $active);
        if($edit & self::FIND)
            $html .= $crudHelper->render('find', $active);
        $html .= $crudHelper->render('next', $active);
        $html .= $crudHelper->render('last', $active);
        if ($edit & self::CREATE)
            $html .= $crudHelper->render('create', $active);
        return "$html</span>\n";
    }

}

