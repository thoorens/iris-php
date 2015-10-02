<?php
namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * NudeGirl helper has two purposes:<ul>
 * <li>try to raise popularity of Iris-PHP in search engines
 * <li>give some advices to the developper after too much code writing
 * </ul>
 */
class NudeGirl extends _ViewHelper {


    public function help() {
        throw new \Iris\Exceptions\NotSupportedException('Stop coding. There is no nude girl support in Iris-PHP. 
We suggest you to call your wife (husband) and offer her/him a small party. If necessary, call the girl/boy who is in love with you
but whose existence you don\'t even notice.');
    }

}

