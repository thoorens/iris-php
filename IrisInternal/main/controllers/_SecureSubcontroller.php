<?php
namespace IrisInternal\main\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A possible ancestor for an internal Subcontroller. By default, they are
 * rerouted to privilege error. The security method is overridden to 
 * grant access in no production. Incidentaly, the exec time measure is
 * desactivated.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _SecureSubcontroller extends \Iris\MVC\_Subcontroller {
    
    public function security() {
        // This module must not be active in production mode
        if(\Iris\Engine\Mode::IsProduction()){
            $this->displayError(\Iris\Errors\Settings::TYPE_PRIVILEGE);
        }
    }

    
    
}


