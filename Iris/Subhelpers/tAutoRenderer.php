<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Iris\Subhelpers;

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
 * This trait provides an autorender mechanism for sub helper:
 * the first element in the args array is the name of a method to be called
 * in the subhelper as a final render.
 *
 * Project IRIS-PHP
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
trait tAutoRenderer {

    protected $_type = \NULL;

    /**
     * Entry point to the subhelper auto rendering
     *
     * @return string
     */
    public final function autoRender() {
        $args = func_get_args();
        $function = '_' . array_shift($args);
        $this->_type = $function;
        $nArgs = $this->_normalize($args);
        if ($nArgs[0] == \NULL) {
            return $this;
        }
        if ($this->_dontRender($nArgs,0)) {
            return '';
        }
        return $this->$function($nArgs);
    }

    /**
     * This subroutine may be overwritten to display nothing if necessary
     *
     * @param string[] $args
     * @param int positions
     * @return boolean
     */
    protected function _dontRender($args, $position = 0) {
        return \FALSE;
    }

    /**
     * Modifies the array so that <ul>
     * <li> it has 5 elements (filling with NULLs)
     * <li> if the first is an array, ignores the rest
     * </ul>
     *
     * @param array $args
     * @return type
     */
    protected function _normalize($args) {
        if (is_array($args[0])) {
            $first = array_shift($args);
            $data = array_merge($first, $args);
        }
        else {
            $data = $args;
        }
        while (count($data) < 5) {
            $data[] = \NULL;
        }
        return $data;
    }

}

