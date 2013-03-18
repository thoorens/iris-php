<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tutorial\controllers\helpers;

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
 * Description of FrInstall
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
abstract class _Content extends \Iris\controllers\helpers\_ControllerHelper {

    const VIEW = 1;
    const TEXT = 2;
    const TEXTVIEW = 3;
    const IMAGE = 4;

    /**
     * 
     * @param type $num
     * @param int $max
     * @return \Tutorial\Content\Item
     */
    public function help($num, $ajax = \FALSE) {
        return $this->getItem($num);
        
        
        
        
        
        return $item;
    }

    /**
     * @return \Tutorial\Content\Item
     */
    protected abstract function getItem($num);
}

