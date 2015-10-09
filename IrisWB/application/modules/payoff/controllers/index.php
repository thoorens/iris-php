<?php
namespace modules\payoff\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A futur tester for the Payoff library
 * 
 * @author 
 * @license 
 */
class index extends _payoff {

    public function indexAction() {
        // this Title var is required by the default layout defined in _payoff
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    public function paypalAction() {
        // demo 
        //$this->__object1 ="XSTHWA5RYXRBU"; 
        // voiture jaune
        $this->__object1 = "SGZJCG8U3KKB8";
    }

}
