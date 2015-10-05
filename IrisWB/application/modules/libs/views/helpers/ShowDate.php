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
 * @todo Write the description  of the class
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class ShowDate extends \Iris\views\helpers\_ViewHelper {

    private static $_Date;

    
    
    protected function _init() {
        $this->_date = new \Iris\Time\Date();
    }

    /**
     * 
     * @param type $format
     * @param \Iris\Time\Date $date
     * @return \iris\views\helpers\ShowDate (or string)
     */
    public function help($format, $date = \NULL) {
        if ($format===\NULL) {
            return $this;
        }
        if(is_null($date)){
            $date = self::$_Date;
        }
        if($format=="#DEF#"){
            $formatedDate = $date->__toString();
            $format = '';
        }
        else{
            $formatedDate = $date->toString($format);
        }
        return <<<FIN
    <th>
        $format
    </th>
    <td>
        $formatedDate
    </td>
FIN;
    }

    public function setDate($date) {
        self::$_Date = $date;
    }

}


