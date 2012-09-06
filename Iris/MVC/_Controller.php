<?php

namespace Iris\MVC;

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
 * An abstract class for the concrete controllers who play an important 
 * role in the site management. Each controller belongs to a module and 
 * has one or more action to be done according to the url given. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class _Controller extends _BaseController {

    protected static $_Type = 'CONTR';

    /**
     * The list of subcontroller 
     * @var array(_Subcontroller) 
     */
    public $subcontrollers = array();

    public function __construct(\Iris\Engine\Response $response, $actionName = 'index') {
        parent::__construct($response, $actionName);
// default menu setting
        $uri = '/' . $response->getModuleName()
                . '/' . $response->getControllerName()
                . '/' . $response->getActionName();
        $this->setActiveMenu($uri);
        self::$_MainController = $this;
// additional setup at application, module and controller level
        $this->_applicationInit();
        $this->_moduleInit();
        $this->_init();
        
// if problem no return
        $this->_verifyAcl();
    }

    /**
     * Records the layout (if any)
     * 
     * @param String $layoutName 
     */
    protected function _setLayout($layoutName) {
        \Iris\MVC\Layout::GetInstance()->setViewScriptName($layoutName);
    }

    /**
     * This class has is 
     * @param boolean $echoing
     * @return string 
     */
    public function dispatch($echoing = TRUE) {
        $this->_view->setResponse($this->_response);
        $layout = \Iris\MVC\Layout::GetInstance();
        $layoutName = $layout->getViewScriptName();
// Complex treatment with layout and possibly subviews
        if (!is_null($layoutName)) {
            try {
                $layout->setResponse($this->_response);
                $layout->setMainView($this->_view);
                $this->_renderSubviews($layout);
                $text = $layout->render();
                if ($echoing) {
                    echo $text;
                }
                else {
                    return $text;
                }
            }
            catch (\Iris\Exceptions\LoaderException $l_ex) {
                throw new \Iris\Exceptions\LoaderException('Problem with layout with message ' .
                        $l_ex->getMessage(), $l_ex);
            }
            catch (Exception $ex) {
                throw $ex;
            }
        }
        else {
            return parent::dispatch($echoing);
        }
    }

    private function _renderSubviews($layout) {
        foreach ($this->subcontrollers as $num => $subcontroller) {
            $subcontroller->predispatch();
            $subcontroller->excecuteAction();
            $rendering = $subcontroller->dispatch(\FALSE);
            $subcontroller->postdispatch();
            $layout->addSubView($num, $rendering);
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
     * Instanciates a subcontroller knowing its name (action and module names are
     * 'index' and 'main' by default if not otherwise defined)
     *   
     * @param string $controllerName 
     * @param type $actionName
     * @param type $module
     * @return _SubController
     */
    public function getSubcontroller($controllerName, $actionName='index', $moduleName=NULL) {
        if (strpos($controllerName, '/') !== FALSE) {
            list($moduleName, $controllerName) = explode('/', $controllerName);
        }
        $response = \Iris\Engine\Response::GetOtherInstance($controllerName, $actionName, $moduleName);
        return $response->makeController();
    }

    /**
     * A version of the overridden method 
     * which considere the view number for true controllers 
     * 
     * @param string $name the variable name
     * @param mixed $value the variable value
     * @param int $number the view number (0 is mainview, other refers to a subcontroller view) 
     */
    protected function _toView1($name, $values, $number = 0) {
        if ($number == 0) {
            $this->_view->__set($name, $values);
        }
        else {
            $this->subcontrollers[$number]->_toView1($name, $values);
        }
    }

    public function setActiveMenu($uri, $menuName='#def#') {
        $menus = \Iris\Structure\MenuCollection::GetInstance();
        $menus->getMenu($menuName)->setDefaultItem($uri);
    }

}

