<?php
// !! no Iris prefix (as in application)
namespace modules\main\controllers;

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
 * @version $Id: $ */

/**
 * The default error controller concrete class defines the default error
 * management for standard and privilege errors, both in production and development
 * mode. 
 */
class Error extends \Iris\modules\main\controllers\_Error {


    /**
     * By default, the standard error mechanism for production uses
     * a predefined script. It is possible to write another script
     * with the same path and name in the main part of the application
     * folder, to display another text. 
     */
    protected function _standardProduction() {
        $this->setViewScriptName('defaultErrors/production/standard');
    }

    /**
     * In development, the predefined standard error script can be replaced by
     * another script with the same path and name in the main part of the application
     * folder. But it is advisable to keep it as it is.
     */
    protected function _standardDevelopment() {
        $this->setViewScriptName('defaultErrors/development/standard');
        $this->_exceptionDescription();
        $this->_displayStackLevel();

        
    }

    /**
     * By default, the same script is used to display an error message
     * relative to authorisation problem in both production and development
     * mode.
     */
    protected function _privilegeProduction() {
        $this->setViewScriptName('defaultErrors/common/privilege');
    }

    /**
     * By default, the same script is used to display an error message
     * relative to authorisation problem in both production and development
     * mode
     */
    protected function _privilegeDevelopment() {
        $this->setViewScriptName('defaultErrors/common/privilege');
    }


}