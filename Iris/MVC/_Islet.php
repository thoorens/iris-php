<?php

namespace Iris\MVC;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An Islet is a special controller executed by a view/layout with its local
 * parameters. It acts as a small application, with no interaction with
 * the rest of the page (except parameters for action and use of Memory).
 * The usual way to fire one is by a view helper:
 * 
 * {islet(controllerName,parameters,actionName,moduleName)}
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 */
abstract class _Islet extends _BaseController {

    /**
     * A signature for system trace
     * 
     * @var string
     */
    protected static $_Type = 'ISLET';

    /**
     * what to do in case of direct call 
     * @var mixed[] : parameter for redirect 
     */
    protected static $_ErrorRedirection = array('/', TRUE);

    /**
     * The parameters to send to the called method
     * 
     * @var string[]
     */
    protected $_parameters = array();

    /**
     *
     * @param \Iris\Engine\Response $response 
     */
    public function __construct($response, $actionName='index') {
        parent::__construct($response, $actionName);
        $this->_testDirectCall();
        // additional setup at module and controller level
        $this->_moduleInit();
        $this->_init();
    }

    /**
     * Subcontroller are not meant to be called in the URL. In such a case,
     * the program is rerouted to / by default (can be overridden)
     */
    final private function _testDirectCall() {
        $presentMCA = sprintf('%s/%s/%s', $this->getModuleName(), $this->getControllerName(), $this->getActionName());
        $router = \Iris\Engine\Router::GetInstance();
        $mainUri = $router->getAnalyzedURI();
        // error is detected by comparing the present mod/cont/act with that from router
        if ($presentMCA == $mainUri) {
            $this->reroute(self::$_ErrorRedirection);
        }
    }

    /**
     * In islets parameters are passed by the helper which creates it
     * In subcontrollers parameters have been set by the main controller
     * 
     * @return array
     */
    public function getParameters() {
        return $this->_parameters;
    }

    /**
     * Permits to set the parameters of the method which is going 
     * to be executed in the subcontroller.
     * 
     * @param mixed[] $parameters 
     * @return static (fluent interface)
     */
    public function setParameters($parameters) {
        $this->_parameters = $parameters;
        return $this;
    }
    
    /**
     * Adds a parameters to themethod which is going 
     * to be executed in the subcontroller.
     * 
     * @param mixed $parameter
     * @return static (fluent interface)
     */
    public function addParameter($parameter) {
        $this->_parameters[] = $parameter;
        return $this;
    }
    

}