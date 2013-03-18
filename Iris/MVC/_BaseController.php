<?php

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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

namespace Iris\MVC;

/**
 * _BaseController:
 * an abstract class ancestor of any controller of the application.
 * <br/>
 * It provides mechanisms for: <ul>
 * <li> init : permits to simulate a standard creator </li>
 * <li> predispatch : any treatement to be done before the action</li>
 * <li> action : calls the action asked in URL</li>
 * <li> postdispatch : any treatment to be done after the action</li> 
 * </ul>
 * 
 */
class _BaseController {
use \Iris\views\helpers\tViewHelperCaller;

    /**
     * Each controller has a type to be defined in subclasses
     * (used in error/debugging messages)
     * 
     * @var string
     */
    protected static $_Type = '';

    /**
     * The view linked to the controller (not to confound with the script
     * through which it displays itself)
     * 
     * @var \Iris\MVC\View
     */
    protected $_view;

    /**
     * The response which is driving the controller
     * 
     * @var \Iris\Engine\Response
     */
    protected $_response;

    /**
     * The active router when the controller was created
     *  
     * @var \Iris\Engine\Router
     */
    private $_router;

    /**
     * The default URL to go in case of privilege error
     * 
     * @var string
     */
    protected $_noAclRoute = "/ERROR/privilege";

    /**
     * If the controller is not the main controller, a reference to 
     * this controller
     * 
     * @var _Controller
     */
    protected static $_MainController = NULL;
    
    /**
     * 
     * @param \Iris\Engine\Response $response 
     * PHP 5.4 ready
     */
    public function __construct(\Iris\Engine\Response $response, $actionName = 'index') {
        $this->_response = $response;
        $this->_view = new View();
        $this->_view->setViewScriptName($actionName);
        \Iris\Exceptions\ErrorHandler::SystemTrace(static::$_Type, $response);
    }

    /**
     * Callback for customize application level contructor
     */
    protected function _applicationInit() {
        
    }

    /**
     * Callback for customize module level constructor
     */
    protected function _moduleInit() {
        
    }

    /**
     * Callback for customize constructor
     */
    protected function _init() {
        
    }

    /**
     * Prohibits any internal controller by default
     * To permits access it must have an overridden version of security
     * Public access is required for the dispatcher to access it
     */
    public function security() {
        if ($this->_response->isInternal()) {
            $this->reroute($this->_noAclRoute, TRUE);
        }
    }

    /**
     * Callback to be called after initer and before action and dispatch
     */
    public function preDispatch() {
        
    }

    /**
     * Callback to be called after action and dispatch
     */
    public function postDispatch() {
        
    }

    /**
     * Call to the action method specified in the URL. Some treatment is
     * involved:<ul>
     * <li> verify permission (or reroute)
     * <li> verify method existence (invent it if necessary through _callAction)
     * </ul>
     */
    public function excecuteAction() {
        // if problem no return
        //$this->_verifyAcl();
        $action = $this->_response->getActionName();
        $this->_view->setResponse($this->_response);
        $actionName = $action . 'Action';
        $methodes = get_class_methods(get_class($this));
        // caution : in subcontrollers, the parameters are provided by the
        // main controller 
        $parameters = $this->_getParameters();
        if (array_search($actionName, $methodes) === FALSE) {
            $this->__callAction($actionName, $parameters);
        }
        else {
            if (count($parameters)) {
                call_user_func_array(array($this, $actionName), $parameters);
            }
            else {
                $this->$actionName();
            }
        }
    }

    /**
     * Do the verification of ACL.
     */
    protected function _verifyAcl() {
        // always happy
        $acl = \Iris\Users\Acl::GetInstance();
        $resource = '/' . $this->getModuleName() . '/' . $this->getControllerName();
        if (!$acl->hasPrivilege($resource, $this->getActionName())) {
            $this->reroute($this->_noAclRoute, TRUE);
            // no return
        }
    }

    /**
     * Some controllers may have "action" methods called "magically" through this
     * method (overridden)
     * 
     * @param string $actionName the name of the action (e.g. "createAction" )
     * @param array parameters the parameters from URL
     */
    public function __callAction($actionName, $parameters) {
        \Iris\Engine\Memory::SystemTrace();
        throw new \Iris\Exceptions\ControllerException("Unknown action: $actionName");
    }

    /**
     * The view is converted to string (and echoed if requested). This method
     * is used only in case of no layout or by islets and subcontrollers
     * 
     * @param boolean $echoing if false, produce a string and return it
     * @return string (in case of not echoing) 
     */
    public function dispatch($echoing = \TRUE) {
        //$this->_view->setResponse($this->_response);
        if ($echoing) {
            echo $this->_view->render();
        }
        else {
            // in case of islet
            return $this->_view->render();
        }
    }

    public function quote($text, $data=\NULL){
        if(is_null($data)){
            $data = $this->_view;
        }
        $quoteView = new \Iris\MVC\Quote($text, $data);
        $render = $quoteView->render();
        $this->_view->addPrerending($render);
        $this->setViewScriptName('__QUOTE__');
    }
    
    /**
     * Offers a way to render a view manually by giving its name. 
     * By default, it is echoed directly.
     * 
     * @param string $scriptName
     * @param boolean $echoing
     * @param boolean $absolute If true, the name if an absolute one (relative to project root)
     * @return mixed 
     */
    public function renderNow($scriptName, $echoing = TRUE, $absolute = \FALSE) {
        $rendering = $this->_view->render($scriptName, $absolute);
        if ($echoing) {
            echo $rendering;
        }
        else {
            return $rendering;
        }
    }

    /**
     * Offers a way to render a view manually by giving its name. 
     * By default, it is echoed directly. Here the file name is absolute (starting from project root)
     * 
     * @param string $scriptName
     * @param boolean $echoing
     * @return mixed 
     */
    public function renderFile($scriptName, $echoing = TRUE) {
        $this->renderNow($scriptName, $echoing, Template::ABSOLUTE);
    }
    
    public function preRender($scriptName) {
        //$this->_prerendering .= $this->renderNow($scriptName, \FALSE);
        $this->_view->addPrerending($this->renderNow($scriptName, \FALSE));
    }

    /**
     * Returns the subtype of the controller, essentially for
     * debugging purpose
     * 
     * @return string
     */
    public function getType() {
        return static::$_Type;
    }

    /**
     * Returns the module in which the controler has been found
     * (not in the URI if its a default one)
     * 
     * @return string 
     */
    public function getModuleName() {
        return $this->_response->getModuleName();
    }

    /**
     * Returns the name of the controller
     * 
     * @return string 
     */
    public function getControllerName() {
        return $this->_response->getControllerName();
    }

    /**
     * Returns the name of the expected action to be taken
     * @return string 
     */
    public function getActionName() {
        return $this->_response->getActionName();
    }

    /**
     * In standard controllers, the parameters are
     * in the URI (and then in response)
     * 
     * @return array
     */
    protected function _getParameters() {
        return $this->_response->getParameters();
    }

    /**
     *
     * @return type 
     */
    protected function _getLayout() {
        return NULL;
    }

    /**
     *
     * @return \Iris\Engine\Router 
     */
    public function getRouter() {
        if (is_null($this->_router)) {
            $this->_router = \Iris\Engine\Router::GetInstance();
        }
        return $this->_router;
    }

    /**
     * Explicitely change the script to be rendered
     * 
     * @param type $scriptName 
     */
    public function setViewScriptName($scriptName) {
        $this->_view->setViewScriptName($scriptName);
    }

    /**
     *
     * @return Iris\Engine\Reponse 
     */
    public function getResponse() {
        return $this->_response;
    }

    public function redirect($action) {
        $actionPara = explode('/', $action);
        $actionName = array_shift($actionPara);
        $this->_response->setAction($actionName);
        $this->setViewScriptName($actionName);
        $this->_response->setParameters($actionPara);
        $this->excecuteAction();
        $this->dispatch();
        $this->postDispatch();
        throw new \Iris\Exceptions\RedirectException('First');
    }

    public function reroute($URI, $sameServer = TRUE) {
        // if parameters have been put in array
        if (is_array($URI)) {
            list($URI, $sameServer) = $URI;
        }
        if ($sameServer) {
            $URI = \Iris\Engine\Superglobal::GetServer('HTTP_HOST') . $URI;
        }
        $URI = "http://$URI";
        header("location:$URI");
    }

    /**
     * If an non existent method is called from one of the controller method
     * the program tries to use a controller helper
     * 
     * @param string $functionName the non existent method
     * @param array $arguments the optional arguments as an array
     * @return mixed the value returned by the helper
     */
    public function __call($functionName, $arguments) {
        try {
            $actionResult = \Iris\controllers\helpers\_ControllerHelper::HelperCall($functionName, $arguments, $this);
            return $actionResult;
        }
        catch (_Exception $exc) {
            throw new \Iris\Exceptions\ResponseException("Action or action helper '$functionName' not found");
        }
    }

    /**
     * The better way to transmit a value to a view. By default to the main view,
     * if the third parameter is used to a subcontroller view.
     * 
     * @param string $name the variable name
     * @param mixed $value the variable value
     * @param int $number the view number (0 is mainview, other refers to a subcontroller view) 
     * @return mixed (for fluent interface)
     */
    public function toView($name, $values, $number = 0) {
        if (is_null($name)) {
            foreach ($values as $key => $value) {
                $this->toView($key, $value, $number);
            }
        }
        else {
            $this->_toView1($name, $values, $number);
        }
        return $values;
    }

    /**
     *
     * @param string $name the variable name
     * @param mixed $value the variable value
     * @param int $number the view number (ONLY TAKEN INTO ACCOUNT IN TRUE _CONTROLLER) 
     */
    protected function _toView1($name, $value, $number = 0) {
        $this->_view->$name = $value;
    }

    /**
     * An alias for toView
     * 
     * @param string $name the variable name
     * @param mixed $value the variable value
     * @param int $number the view number (0 is mainview, other refers to a subcontroller view) 
     * @return mixed (for fluent interface)
     */
    public function __($name, $value, $number = 0) {
        return $this->toView($name, $value, $number);
    }

    /**
     * A simpler way to transmit values toView, the variable name is prepended by __
     * (this method is reserved for main view)
     * 
     * @param string $__name the variable name
     * @param mixed $value the variable value
     */
    public function __set($__name, $value) {
        if (strpos($__name, '__') !== 0) {
            throw new \Iris\Exceptions\ControllerException(('Illegal attribute or bad view variable'));
        }
        else {
            $name = substr($__name, 2);
            $this->_view->$name = $value;
        }
    }

    /**
     *
     * @param string $name
     * @param mixed $value 
     */
    protected function _toMemory($name, $value) {
        \Iris\Engine\Memory::Set($name, $value);
    }

    /**
     * Islets often needs to get value from memory. An easier access...
     * 
     * @param string $name
     * @param mixed $default 
     */
    protected function _fromMemory($name, $default = NULL) {
        return \Iris\Engine\Memory::Get($name, $default);
    }

    /**
     *
     * @return _Controller
     */
    public static function GetMainController() {
        return self::$_MainController;
    }

}
