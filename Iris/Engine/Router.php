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
 * The router is an essential part of the process. Its mission is to analyze
 * the URL line and prepare the loader to find the good classes.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ *  
 */
class Router {

    /**
     * If true, indicates the internal status of the module. False by default
     * 
     * @var boolean
     */
    private $_internal = FALSE;

    /**
     * The current module after resolution
     * 
     * @var string
     */
    private $_moduleName;

    /**
     * The current controller name
     * @var string
     */
    private $_controllerName;

    /**
     * The current action name
     * @var string
     */
    private $_actionName;

    /**
     * Any parameters remaining after mod/cont/act analysis
     * 
     * @var array
     */
    private $_parameters;

    /**
     * A previous router after creating a new one in error situation
     * 
     * @var Router
     */
    private $_previous = NULL;

    /**
     *
     * @var Router
     */
    private static $_Myself = NULL;

    /**
     * Get the unique instance of Router, which can be <ul>
     * <li> a fresh new one (after a fatal error in previous execution)
     * <li> a first one if none exists (with analysis of the request uri)
     * <li> the current one
     * </ul>
     * @param type $forcedURI
     * @return \Iris\Engine\Router  
     */
    public static function GetInstance($forcedURI = NULL) {
        // if force a new router, do it
        if (!is_null($forcedURI)) {
            self::$_Myself = new Router($forcedURI);
        }
        // if non existent create one 
        if (is_null(self::$_Myself)) {
            self::$_Myself = new Router();
        }
        // in anycase returns the good one
        return self::$_Myself;
    }

    /**
     * A new router is made containing all stuffs to manage the page.
     *
     * @param string $error an optional url in case of error 
     */
    private function __construct($error = NULL) {
        $this->_previous = self::$_Myself;
        //// set program name
        $slices0 = $this->_getURLSlices($error);
        list($slices, $modules) = $this->_seekModules($slices0);
        $this->seekOtherComponents($slices);
        \Iris\Engine\Loader::GetInstance()->pathToHelpers($modules);
    }

    /**
     * A given URL or the Request_URI are made an array
     * 
     * @param type $url
     * @return type 
     */
    private function _getURLSlices($url) {
        // By default analyse server URI
        if (is_null($url)) {
            // we must eliminate GET parameters if any
            $parts = explode('?', \Iris\Engine\Superglobal::GetServer('REQUEST_URI'));
            $url = $parts[0];
        }
        $URLslices = explode('/', $url);
        array_shift($URLslices); // drop first empty chunk made by initial /
        return $URLslices;
    }

    /**
     * Examines URL slices to find a possible module (internal or not)
     * 
     * @param type $slices
     * @return array 
     */
    private function _seekModules($slices) {
        $rootdir = IRIS_PROGRAM_PATH;
        // test for internal module 
        if (strlen($slices[0]) and $slices[0][0] == '!') {
            $this->_moduleName = substr(array_shift($slices), 1);
            $this->_internal = TRUE;
            $modulePath = 'library/IrisInternal/' . $this->_moduleName;
            $mainPath = 'library/IrisInternal/main';
        }
        else {
            if ($slices[0] == '') {
                $this->_moduleName = 'main';
            }
            else {
                // first slice may be a possible module name
                $candidateModule = "$rootdir/modules/$slices[0]";
                \Iris\Log::Debug('Candidat module :' . $candidateModule . '<br/>', \Iris\Engine\Debug::LOADER);
                if (file_exists($candidateModule)) {
                    $this->_moduleName = array_shift($slices);
                }
                else {
                    $this->_moduleName = 'main';
                }
            }
            $modulePath = '/modules/' . $this->_moduleName;
            $mainPath = '/modules/main';
        }
        if ($modulePath == $mainPath) {
            $modules = array($mainPath);
        }
        else {
            $modules = array($modulePath, $mainPath);
        }

        return array($slices, $modules);
    }

    /**
     * Add the path to the modules in the loader
     * 
     * @param string
     * @param array $modules 
     * @todo clean the loader when it executes a second time
     */
    public function prepareLoader($error, $modules) {
        $loader = \Iris\Engine\Loader::GetInstance($error);
        // in modules, search helpers in main if not found
        // unnecessary to search main twice
        $i = 0;
        if (count($modules) == 2) {
            $loader->pathToHelpers($modules[0]);
            $i++;
        }
        $loader->pathToHelpers($modules[$i]);
    }

    public function seekOtherComponents($URIchunks) {
        $this->_controllerName = $this->_forceIndex(array_shift($URIchunks));
        $this->_actionName = $this->_forceIndex(array_shift($URIchunks));
        $this->_parameters = $URIchunks;
    }

    /**
     * For debugging purpose (or error handling in development)
     * @return String 
     */
    public function __toString() {
        return "$this->_moduleName - $this->_controllerName - $this->_actionName";
    }

    private function _forceIndex($name) {
        return $name == '' ? 'index' : $name;
    }

    /**
     * An accessor get for the program name
     * @return string
     */
    public function getProgramName() {
        return $this->_programName;
    }

    /**
     * A critical method to create the response after URL analysis
     * 
     * @return Response 
     */
    public function makeResponse() {
        $response = new Response($this->_controllerName, $this->_actionName, $this->_moduleName, $this->_parameters, $this->_internal);
        return $response;
    }

    /**
     * A '/' separated concatenated string of module, controller and action 
     * 
     * @return string 
     */
    public function getAnalyzedURI($withParameters = \FALSE) {
        if ($withParameters) {
            $parameters = implode('/', $this->_parameters);
            $actionPara = $this->_actionName . "/$parameters";
        }
        else {
            $actionPara = $this->_actionName;
        }
        return sprintf('%s/%s/%s', $this->_moduleName, $this->_controllerName, $actionPara);
    }

    /**
     * An accessor to the previous router (useful in error treatment)
     * @return Router
     */
    public function getPrevious() {
        return $this->_previous;
    }

}


