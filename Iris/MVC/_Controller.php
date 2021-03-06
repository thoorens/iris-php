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
     * @var _Subcontroller[]
     */
    private $_wrappers = array();

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
     * Returns the current script name for the layout
     * @return string
     */
    protected function _getLayout(){
        return \Iris\MVC\Layout::GetInstance()->getViewScriptName();
    }
    /**
     * The view is converted to string (and echoed if requested). In this
     * class, if a layout has been defined, it is used otherwise, the parent
     * method is called.
     * 
     * @param boolean $echoing
     * @return string 
     */
    public function dispatch($echoing = TRUE) {
        $this->_view->setResponse($this->_response);
        $layout = \Iris\MVC\Layout::GetInstance();
        $layoutName = $layout->getViewScriptName();
// Complex treatment with layout and possibly subviews
        if (!empty($layoutName)) {
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
                        $l_ex->getMessage(), 0, $l_ex);
            }
            catch (Exception $ex) {
                throw $ex;
            }
        }
        else {
            return parent::dispatch($echoing);
        }
    }

    /**
     * Each planned subcontroller is instanciated and passed through its 
     * normal route : predispatch, executeAction, dispatch and postdispatch.
     * The rendering is stored in the subview area of the layout
     *  
     * @param type $layout
     */
    private function _renderSubviews($layout) {
        /* @var $wrapper SubcontrollerWrapper */
        foreach ($this->_wrappers as $num => $wrapper) {
            $subcontroller = $wrapper->makeSubcontroller();
            $subcontroller->predispatch();
            $subcontroller->excecuteAction();
            $rendering = $subcontroller->dispatch(\FALSE);
            $subcontroller->postdispatch();
            $layout->addSubView($num, $rendering);
        }
    }

    

    /**
     * 
     * @param int $number
     * @param string $controllerName 
     * @param string $actionName
     * @param string $module
     * @return _SubController
     */
    public function registerSubcontroller($number, $controllerName, $actionName = 'index', $moduleName = '') {
        if (!isset($this->_wrappers[$number])) {
            $subcontroller = new SubcontrollerWrapper($this, $number);
            $this->_wrappers[$number] = $subcontroller;
        }
        else {
            $subcontroller = $this->_wrappers[$number];
        }
        $subcontroller->setModContAct("$moduleName/$controllerName/$actionName");
        return $subcontroller;
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
            $this->getSubcontroller($number)->_toView1($name, $values);
        }
    }

    public function setActiveMenu($uri, $menuName = '#def#') {
        $menus = \Iris\Structure\MenuCollection::GetInstance();
        $menus->getMenu($menuName)->setDefaultItem($uri);
    }

    /**
     * 
     * @param int $number
     * @return _Subcontroller
     * @throws \Iris\Exceptions\ControllerException
     */
    public function getSubcontroller($number) {
        if (!isset($this->_wrappers[$number])) {
            throw new \Iris\Exceptions\ControllerException("Subcontroller $number is not defined");
        }
        return $this->_wrappers[$number];
    }

    
}

