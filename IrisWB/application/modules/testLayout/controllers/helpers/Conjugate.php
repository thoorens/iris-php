<?php

namespace Iris\controllers\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */


/**
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Conjugate extends _ControllerHelper{
    private $_accents = [
        'a' => 'á',
        'e' => 'é',
        'i' => 'í',
        'o' => 'ó',
        'u' => 'ú',
    ];
    
    /**
     * Conjugates a spanish verb using a method of the calling controller.
     * 
     * This example is only for DEMONSTRATION PURPOSE because it makes no senses to 
     * put the variables and the method in the controller. Il could be more interestin
     * to have various helpers accessing the same controller method or sharing some
     * controller variable.
     * 
     * @param string $verb
     * @param int $number
     * @return string
     */
    public function help($verb,$number){
        switch($number){
            case 1;
                $radix = $this->dropLast($verb,2);
                break;
            case 5;
                $radix = $this->dropLast($verb,2);
                $radix.= $this->_accents[$verb[strlen($verb)-2]];
                break;
            default:
                $radix = $this->dropLast($verb);
                break;
        }
        // the helper can modify a public variable in the calling controller
        $this->title = "Spanish verbs";
        // the helper can read a public variable in the calling controller
        return $radix.$this->endings[$number];
    }
}

