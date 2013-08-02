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
 * The default error controller concrete class
 * 
 */
class Error extends \Iris\modules\main\controllers\_Error {


    
    protected function _standardProduction() {
        $this->setViewScriptName('Errors/production');
        $this->__ErrorType = "Standard";
    }

    protected function _standardDevelopment() {
        $this->setViewScriptName('Errors/development');
        $this->__ErrorType = "Developement";
        $this->_exceptionDescription();
        $this->_displayStackLevel();

        
    }

    protected function _privilegeProduction() {
        $this->setViewScriptName('Errors/privilege');
        $this->__ErrorType = "Privilege";
    }

    protected function _privilegeDevelopment() {
        $this->setViewScriptName('Errors/privilege');
        $this->__ErrorType = "PrivDev";
    }


}