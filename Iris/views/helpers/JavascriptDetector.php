<?php

namespace Iris\views\helpers;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2012 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */

/**
 * This helper is part of the javascript detection mechanism of IRIS-PHP.
 * It adds a noscript part in the head part of the view/layout file
 * while no 'iris_nojavascript' variable exist in $_SESSION.
 * If the client has no javascript, a refresh is done to jsTest.php
 * which will create the variable before returning to the site root.
 * 
 */
class JavascriptDetector extends _ViewHelper {

    /**
     * Indicates that this helper can't be used in islet nor partial
     * 
     * @var boolean
     */
    protected static $_NotAfterHead = \TRUE;
    protected static $_Singleton = TRUE;

    public function help($command='/!iris/reset/jsTest') {
        if (\Iris\Users\Session::JavascriptEnabled()) {
            echo <<<END
<noscript>
            <meta http-equiv="refresh" content="1; URL=$command"/>
        </noscript> 

END;
        }
    }
    

}

