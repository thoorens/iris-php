<?php



namespace workbench;

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
 * Context analysis for info display in Workbench
 * 
 */
class Context implements \Iris\Structure\iExplanationProvider {

    

    /**
     *
     * @param \Iris\MVC\View $view
     * @return array 
     */
    public function getActiveController($view, $first = TRUE, $compact = FALSE) {
        if ($first) {
            $router = \Iris\Engine\Router::GetInstance();
            if ($router->getPrevious() != NULL) {
                $router = $router->getPrevious();
            }
            return $router->getAnalyzedURI();
        }
        else {
            $response = $view->getResponse();
            $controllerName[] = $response->getModuleName();
            $controllerName[] = $response->getControllerName();
            $controllerName[] = $response->getActionName();
            if ($compact) {
                return implode('/', $controllerName);
            }
            else {
                return $controllerName;
            }
        }
    }

    public function getMessage($view) {
        $activecontroller = $this->getActiveController($view, TRUE);
        $methodName = str_replace('/', '_', $activecontroller);
        $objectName = \Iris\Translation\_Messages::GetSender('wb');
        $object = new $objectName();
        call_user_func_array(array($object, $methodName), array());
    }

}

?>
