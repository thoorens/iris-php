<?php

namespace modules\classes\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
    Various tests on character representation
 *  */
class multibytes extends _classes {

    public function asciiAction(){
        $this->__text = $text ="J'ai passé l'été à Paris";
        $this->__length= $length = strlen($text);
        $this->__size = strlen($text);
        $this->__upper = strtoupper($text);
        $this->__mbupper = mb_convert_case($text,MB_CASE_UPPER);
        $this->__low= $low = "J'ai passe l'ete a Paris";
        $this->__size2 = strlen($low);
        $this->__scale = '----.----!----.----!----.----!';
    }
    
    public function utf8Action(){
        
    }

}
