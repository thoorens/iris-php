<?php
namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
        \Iris\Engine\Log::Debug("Analyse", \Iris\Engine\Debug::ROUTE);
        $this->_router = $router = Router::GetInstance($forcedURL);
        return $this;
    }

    /**
     * 
     * @return Dispatcher (for fluent interface)
     */
    public function prepareResponse() {
        \Iris\Engine\Log::Debug("Response", \Iris\Engine\Debug::ROUTE);
        $response = $this->_router->makeResponse();
        $this->_controller = $response->makeController();
        return $this;
    }

    /**
     *
     * @return Dispatcher (for fluent interface) 
     */
    public function preDispatch() {
        \Iris\Engine\Log::Debug("PreDispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->security();
        $this->_controller->preDispatch();
        return $this;
    }

    /**
     *
     * @return string the 
     */
    public function dispatch() {
        \Iris\Engine\Log::Debug("Dispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->excecuteAction();
        return $this->_controller->dispatch(\FALSE);
    }
    
    /**
     *
     * @return Dispatcher (for fluent interface) 
     */
    public function postDispatch() {
        \Iris\Engine\Log::Debug("PostDispatch", \Iris\Engine\Debug::ROUTE);
        $this->_controller->postDispatch();
        return $this;
    }

    

}


