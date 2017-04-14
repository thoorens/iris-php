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
 * Get a CSS file for workbench 
 * 
 */
class WbCSS extends \Iris\views\helpers\_ViewHelper {

    static $_singleton = FALSE;

    public function help($type = \NULL) {
        $css = is_numeric($type) ? "wb$type.css" : "wb.css";
        return $this->callViewHelper('styleLoader', "/!documents/file/css/$css");
    }

}
