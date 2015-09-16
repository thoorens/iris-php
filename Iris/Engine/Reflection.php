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
 * A class mainly used in test management. 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Reflection {
    
    /**
     *
     * @var Response
     */
    private $_response;
    
    /**
     *
     * @var Router
     */
    private $_router;
    
    public function __construct($object){
        $this->_router = Router::GetInstance();
        if($object instanceof \Iris\MVC\View){
            $this->_response = $object->getResponse();
        }else{
            \Iris\Engine\Debug::DumpAndDie($object);
        }
    }
    
    public function getModuleName($main=\TRUE){
        if($main){
            $uri = explode('/',$this->_router->getAnalyzedURI());
            return $uri[0];
        }
        else{
            return $this->_response->getModuleName();
        }
    }

    public function getControllerName(){
        return $this->_response->getControllerName();
    }
    
    public function getAction(){
        return $this->_response->getActionName();
    }
    
    public function getControllerType(){
        $controller = $this->_response->makedController;
        return $controller->getType();
    }
    
    public function isInternal(){
        return $this->_response->isInternal();
    }

}


