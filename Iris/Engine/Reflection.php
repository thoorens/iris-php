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
 * Mainly used in test management. 
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

?>
