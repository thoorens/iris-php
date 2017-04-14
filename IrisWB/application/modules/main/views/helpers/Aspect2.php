<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * An example of view helper (for Iris WB). 
 * see an example in IRISWB/helpers/various/views script  
 * 
 */
class Aspect2 extends \Iris\views\helpers\_ViewHelper {

    static $_singleton = FALSE;

    public function help($text) {
        throw new \Iris\Exceptions\HelperException('This helper is not intended to be fired. A module helper should be found first.');
    }

}

