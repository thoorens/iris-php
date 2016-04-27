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
 * A possible ancestor for an internal controller. By default, they are
 * rerouted to privilege error. The security method is overridden to 
 * grant access in no production. Incidentaly, the exec time measure is
 * desactivated.
 * @todo is it a good idea to desactivate time measure
 * 
 */
abstract class _SecureInternal extends \Iris\MVC\_Controller {
    
    public function __construct(\Iris\Engine\Response $response, $actionName) {
        parent::__construct($response, $actionName);
    }

    /**
     * More permisive version : reroute only in production mode
     */
    public function security() {
        // This module must not be active in production mode
        if(\Iris\Engine\Mode::IsProduction()){
            $this->displayError(\Iris\Errors\Settings::TYPE_PRIVILEGE);
        }
    }

    
}


