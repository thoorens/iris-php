<?php

namespace Iris\views\helpers;


/* This file is part of IRIS-PHP.
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
 * 
 * This helper permits to collect differents meta tags during
 * the page construction (by calling the helper with an array
 * parameter) and to display all of them (by calling the
 * helper without any parameter). The array parameter consists
 * in an associative array. The keys are used to evit doublons.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
final class HeadMeta extends _ViewHelper {

    protected static $_Singleton = TRUE;

    /**
     *
     * @var array : an array of key => meta_tag 
     */
    protected static $_Meta = array();

    /**
     *
     * @param array/NULL $param : NULL for display, array to add new meta tags
     * @return string zero to many lines of html meta tags 
     */
    public function help($param=NULL) {
        if (is_null($param)) {
            return $this->_display();
        }
        else {
            $this->_add($param);
        }
    }

    /**
     * add one or more meta tags to the collection
     * @param array $assocArray : an array of key => meta_tag
     */
    private function _add($assocArray) {
        foreach ($assocArray as $key => $value) {
            $this->_meta[$key] = $value;
        }
    }

    private function _display() {
        $metaLines = array();
        foreach (self::$_Meta as $meta) {
            $metaLines[] = "<meta $meta/>";
        }
        return implode("\n", $metaLines);
    }

}

?>
