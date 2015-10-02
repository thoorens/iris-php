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
 * Can call a view helper
 */
trait tViewHelperCaller {

    /**
     * Permits to call a view helper (some of which are simple interfaces
     * to javascript or Ajax code)
     * 
     * @param string $helperName
     * @param mixed $args (optional and 
     */
    public function callViewHelper($helperName) {
        // gets optional parameters
        $args = func_get_args();
        array_shift($args); // helperName is the only mandatory argument
        $view = isset($this->_view) ? $this->_view : \NULL;
        return \Iris\views\helpers\_ViewHelper::HelperCall($helperName, $args, $view);
    }

}