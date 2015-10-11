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
 * A helper for image display
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Image extends _ViewHelper {

    protected $_folder = BLANKSTRING;
    protected $_defaultClass = '';
    


    /**
     * Creates an image tag with alt and title
     * 
     * @param string $source Image file name
     * @param string $alt Alt attribute (to display if image is missing)
     * @param string $tooltip Title attribute (tooltip)
     * @param string $folder image directory (by default images)
     * @param string $class class name for CSS
     * @param string $id optional id for the object
     * @return string 
     */
    public function help($source = BLANKSTRING, $alt = 'Image', $tooltip = BLANKSTRING, $folder = BLANKSTRING, $class = '', $id = BLANKSTRING) {
        /* @var $image \Iris\Subhelpers\ImageLink */
        $args = func_get_args();
        return new \Iris\Subhelpers\Image($args);
    }

    
}

