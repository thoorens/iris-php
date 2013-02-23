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
 * NudeGirl helper has two purposes:<ul>
 * <li>try to raise popularity of Iris-PHP in search engines
 * <li>give some advices to the developper after to much code writing
 * </ul>
 */
class NudeGirl extends _ViewHelper {


    public function help() {
        throw new \Iris\Exceptions\NotSupportedException('Stop coding. There is no nude girl support in Iris-PHP. 
We suggest you to call your wife (husband) and offer her/him a small party. If necessary, call the girl/boy who is in love with you
but whose existence you don\'t even notice.');
    }

}

