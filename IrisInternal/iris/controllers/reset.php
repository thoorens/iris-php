<?php

namespace IrisInternal\iris\controllers;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * This class provides a mechanism to reset the session
 * and a way to set a session parameter when no javascript is detected
 * on the client.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class reset extends \IrisInternal\main\controllers\_SecureInternal {

    /**
     * no security required
     */
    public function security() {
        
    }

    /**
     * Destroy the session and start a new one by calling the site root
     */
    public function indexAction() {
        if (\Iris\Users\Session::IsSessionActive()) {
            session_destroy();
        }
        header('location:/');
    }

    /**
     * An action to set a session parameter if javascript is not enabled
     * CAUTION: DON'T PUT &ltnoscript> DIRECTLY RECURSION GUARANTED!!
     * 
     * @see view helper JavascriptDetector
     */
    public function jsTestAction() {
        if (!\Iris\Users\Session::IsSessionActive()) {
            session_start();
        }
        $_SESSION['iris_nojavascript'] = TRUE;
        header('location:/');
    }

    

}
