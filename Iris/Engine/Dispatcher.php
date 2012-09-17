<?php

namespace Iris\Engine;



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
 */

/**
 * The dispatcher acts as a conductor<ul>
 * <li>creates and activate a router
 * <li>create a response and uses it to create a controller
 * <li>send the controller orders to realize the page
 * </ul>
 * 
 * @author Jacques THOORENS (jacques@thoorens.net)
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ * 
 */
class Dispatcher {

    /**
     * the router reference
     * 
     * @var \Iris\Engine\Router 
     */
    private $_router;

    /**
     *
     * @var \Iris\Engine\Response
     */
    private $_mainResponse;

    private $_responseCounter=0;
    
    /**
     * the required controller
     * @var \Iris\MVC\_Controller
     */
    private $_controller;

    /**
     * Analyses route and creates a router
     * 
     * @param type $forcedURL (NULL except in cas of error
     * @return Dispatcher  (for fluent interface)
     */
    public function analyseRoute($forcedURL) {
        \Iris\Log::Debug("Analyse", \Iris\Engine\Debug::ROUTE);
        $this->_router = $router = Router::GetInstance($forcedURL);
        return $this;
    }

    /**
     * 
     * @return Dispatcher (for fluent interface)
     */
    public function prepareResponse() {
        \Iris\Log::Debug("Response", \Iris\Engine\Debug::ROUTE);
        $responseNumber = ++$this->_responseCounter;
        $this->_mainResponse = $this->_router->makeResponse($responseNumber);
        $this->_controller = $this->_mainResponse->makeController();
        return $this;
    }

    /**
     *
     * @return Dispatcher (for fluent interface) 
     */
    public function preDispatch() {
        \Iris\Log::Debug("PreDispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->preDispatch();
        $this->_controller->security();
        return $this;
    }

    /**
     *
     * @return string the 
     */
    public function dispatch() {
        \Iris\Log::Debug("Dispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->excecuteAction();
        return $this->_controller->dispatch(\FALSE);
    }
    
    /**
     *
     * @return Dispatcher (for fluent interface) 
     */
    public function postDispatch() {
        \Iris\Log::Debug("PostDispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->postDispatch();
        return $this;
    }

    

}

?>
