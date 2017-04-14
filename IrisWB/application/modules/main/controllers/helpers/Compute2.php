<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A helper making some come calculation to fill part of the page. It will
 * be invisible in a module, if the module has a helper having the same name.
 * 
 * See an example in IrisWB/helpers/various/controllers
 */
class Compute2 extends _ControllerHelper{
    
    public function help($number,$operator){
        throw new \Iris\Exceptions\HelperException('This helper is not intended to be fired. A module helper should be found first.');
    }
}


