<?php



namespace IrisInternal\main\controllers;

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

?>
