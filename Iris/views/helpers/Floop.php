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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */

/**
 * FLoop offers a way to display the items of an array by means of a
 * function.
 * 
 */
class Floop extends _ViewHelper {

    private $_functions = [];
    protected static $_Singleton = \TRUE;

    public function help($viewName = \NULL, $dataSet = \NULL) {
        if (is_null($viewName)) {
            return $this;
        }
        else {
            if (isset($this->_functions[$viewName])) {
                $function = $this->_functions[$viewName];
            }
            else {
                $function = function($item, $key = \NULL) {
                    $context = " 
                    <div class=\"oldscreen old1\">
                        $key - $item
                </div>";
                    return $context;
                };
            };
        }
        $floop = new \Iris\MVC\FunctionLoop($function, $dataSet);
        return $floop->render();
    }

    /**
     * 
     * @param string $index
     * @param \Iris\views\helpers\callable $function 
     * @return string
     */
    /**
     * 
     * @param string $index
     * @param \callable $function
     * @return string
     */
    public function setFunction($index, $function) {
        $this->_functions[$index] = $function;
        return '';
    }

    

}
