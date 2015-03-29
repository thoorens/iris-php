<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * 
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A helper for Ajax functions
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Ajax extends _ViewHelper implements \Iris\Subhelpers\iRenderer {
use \Iris\Subhelpers\tSubhelperLink;

    protected function _init() {
        $this->_subhelperName = \Iris\Ajax\_AjaxProvider::GetDefaultAjaxLibrary().'Provider';
    }

    public function render(array $arg1, $arg2) {
        return $arg1[0];
    }

}

