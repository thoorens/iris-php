<?php

namespace Dojo\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * A helper making some come calculation to fill part of the page. It is intended
 * to be found through a library prefix dojo_
 * 
 * See an example in IrisWB/application/modules/various/controllers
 */
class Compute extends \Iris\controllers\helpers\_ControllerHelper {

    public function help($number, $operator) {
        $result = $number;
        switch ($operator) {
            case '+':
                $result += $number;
                break;
            case '*':
                $result *= $number;
                break;
            case '-':
                $result -= $number;
                break;
            case '/':
            case ':':
                $result = $number / $number;
                break;
            default:
                $result = $number * $number;
                break;
        }
        return sprintf('%d %s %d = %d', $number, $operator, $number, $result);
    }

}


