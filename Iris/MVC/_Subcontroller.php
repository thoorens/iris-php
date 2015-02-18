<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Subcontrollers are managed by a main controller which sends them
 * variable values for their view, orders them to render their
 * view whose content will be displayed in mainview or the layout
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Subcontroller extends _Islet {

    /**
     * A signature for system trace
     * 
     * @var string
     */
    protected static $_Type = 'SUBCONT';

    /**
     * The subcontroller registers itself in the main controller, with a unique number. 
     * This number will serve as a second parameter for the toView method in order to 
     * send variables to the view linked to the subcontroller.
     * Il will also serve as a parameter to the SubView helper in the layout.
     * 
     * @param int $num 
     * @return self (fluent interface)
     */
//    public function register($num) {
//        self::$_MainController->subcontrollers[$num] = $this;
//        return $this;
//    }
   


}

