<?php
// !! no Iris prefix (as in application)
namespace modules\errordemo\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * 
/**
 * The default error controller concrete class defines the default error
 * management for standard and privilege errors, both in production and development
 * mode. 

 *  * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
class Error extends \Iris\modules\main\controllers\_Error {

    protected function _init() {
        $this->_setLayout('wberror');
    }

    
    
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