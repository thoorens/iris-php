<?php

namespace Iris\MVC;

// See real class at bottom of file

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
 * A view is a class charged to maintain its values and components
 * before displaying them with the help of a script
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class View implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tTranslatable;

    /**
     * Type of view
     * 
     * @var string
     */
    protected static $_ViewType = 'view';

    /**
     * The only way to detect the script file name in eval is 
     * to store it during file reading.
     * 
     * @var string
     */
    protected static $_LastUsedScript;

    /**
     * the array containing all view variables
     * @var array(mixed) 
     */
    protected $_properties = array();

    /**
     * The script name
     * 
     * @var string 
     */
    protected $_viewScriptName = '';

    /**
     * The response which has selected the view
     * 
     * @var Iris\Engine\Response
     */
    protected $_response;

    /**
     * Some text to be preprend to the final rendering
     * @var string 
     */
    protected $_prerendering = '';

    /**
     *
     * @var \Iris\System\Stack
     */
    private static $EvalStack = \NULL;

    public static function Initializer() {
        if (is_null(self::$EvalStack)) {
            self::$EvalStack = new \Iris\System\Stack('Error in View script stack');
        }
    }

    /**
     * Sets an explicit view script file name
     * 
     * @param string $name
     */
    public function setViewScriptName($name) {
        $this->_viewScriptName = $name;
    }

    /**
     * The magic set method to manage view internal variables
     * 
     * @param string $name
     * @param mixed $value 
     */
    public function __set($name, $value) {
        $this->_properties[$name] = $value;
    }

    /**
     * The magic get method to manage view internal variables
     * Instance variables are maintained in an array
     * 
     * @param type $name
     * @return type 
     */
    public function __get($name) {
        return $this->_properties[$name];
    }

    /**
     * A call back to copy properties from main view if necessary 
     *   
     */
    protected function _copyMainViewProperties() {
        
    }

    /**
     * Adds a text to be prepend to the final rendering
     * 
     * @param string $text
     */
    public function addPrerending($text) {
        $this->_prerendering .= $text;
    }

    /**
     * Evaluates the (implicit or explicit) view script
     * and renders it as an html string
     *  
     * @param string $forcedScriptName (usually NULL)
     * @return string 
     */
    public function render($forcedScriptName = NULL, $absolute = \FALSE) {
        if (strpos($this->_viewScriptName, '/') !== FALSE and !$absolute) {
            $forcedScriptName = $this->_viewScriptName;
        }
        if ($forcedScriptName == '__AJAX__' or $this->_viewScriptName == '__AJAX__') {
            \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
            return;
        }
        // File generators and loaders have no view part, stop them and no time measurement.
        if ($forcedScriptName == '__NO_RENDER__' or $this->_viewScriptName == '__NO_RENDER__') {
            \Iris\SysConfig\Settings::DisableDisplayRuntimeDuration();
            die(''); // stop process - file is complete
        }
        // In case of simple quoting, there is no template file to treat
        elseif (($forcedScriptName == '__QUOTE__' or $this->_viewScriptName == '__QUOTE__')) {
            echo $this->_prerendering; // the "quotation" has been already prerended
            return;
        }
        // In normal cases, there is a template file to render
        else {
            if ($absolute) {
                $template = new Template($forcedScriptName, $this, Template::ABSOLUTE);
            }
            else {
                $template = $this->_getTemplate($forcedScriptName);
            }
            ob_start();
            echo $this->_prerendering;
            self::$EvalStack->push($template);
            $this->_eval($template);
            self::$EvalStack->pop();
            $page = ob_get_clean();
            return $page;
        }
    }

    /**
     * In most case, a script name has been stored
     * @return string
     */
    public function getViewScriptName() {
        return $this->_viewScriptName;
    }

    /**
     * Returns the type of view (e.g. layout)
     * 
     * @return string
     */
    public static function getViewType() {
        return static::$_ViewType;
    }

    /**
     * Evals the text passed in argument (with a lot of error trapping)
     * 
     * @param Template $template
     */
    private function _eval($template) {
        $phtml = $template->renderTemplate();
        // add external properties if necessary (e.g. from mainView to layout)
        $this->_copyMainViewProperties();
        foreach ($this->_properties as $name => $_) {
            ${$name} = &$this->_properties[$name];
        }

        try {
            self::$EvalStack->push($template);
            eval("?>" . $phtml);
            self::$EvalStack->pop();
        }
        catch (\Iris\Exceptions\LoaderException $l_ex) {
            throw $l_ex;
        }
        catch (\Iris\Exceptions\HelperException $h_ex) {
            throw $h_ex;
        }
        // The layout may receive view exceptions from inside views
        catch (\Iris\Exceptions\ViewException $ex) {
            throw $ex;
        }
        catch (\Iris\Exceptions\ErrorException $exception) {
            $errMessage = $exception->getMessage();
            $viewType = static::$_ViewType;
            $fileName = $template->getReadScriptFileName();
            $trace = $exception->getTrace();
            // take the line number from the trace containing eval()'d error
            foreach ($trace as $traceItem) {
                if (isset($traceItem['file']) and strpos($traceItem['file'], "eval()'d code") !== FALSE) {
                    $line = $traceItem['line'];
                    break;
                }
            }
            $viewException = new \Iris\Exceptions\ViewException('Error in a view evaluation', NULL, $exception);
            $viewException->setFileName($fileName);
            $viewException->setLineNumber($line);
            $viewException->setViewType($viewType);
            $viewException->setErrorMessage($errMessage);
            throw $viewException;
        }
    }

    /**
     * Get a template content. In view, by reading a file found by the loader
     * 
     * @param string $forcedScriptName
     * @return string 
     */
    protected function _getTemplate($forcedScriptName) {
        return new Template($forcedScriptName, $this);
    }

    /**
     * Returns the name of the directory where to find the script
     * corresponding to the view (or the layout or the partial in subclasses)
     * 
     * @return String 
     */
    public function viewDirectory() {
        $controllerName = $this->_response->getControllerName();
        $controller = str_replace("\\", "/", $controllerName);
        return "scripts/$controller";
    }

    /**
     * A view helper is called in case an unknow method is called 
     * 
     * @param string $functionName
     * @param array $arguments
     * @return mixed
     */
    public function __call($functionName, $arguments) {
        return \Iris\views\helpers\_ViewHelper::HelperCall($functionName, $arguments, $this);
    }

    /**
     * Accessor set for the current response
     * 
     * @param \Iris\Engine\Response $response 
     */
    public function setResponse($response) {
        $this->_response = $response;
    }

    /**
     * Acessor get for the response which called the view
     * 
     * @return \Iris\Engine\Response
     */
    public function getResponse() {
        return $this->_response;
    }

    /**
     * Gets the last used script name (for debugging or demo purpose)
     * @return string
     */
    public static function GetLastScriptName() {
        return static::$_LastUsedScript;
    }

    /**
     * Sets the last used script name (for debugging or demo purpose)
     */
    public static function SetLastScriptName($name) {
        static::$_LastUsedScript = $name;
    }

    /**
     * 
     * @return \Iris\System\Stack;
     */
    public static function GetEvalStack() {
        return self::$EvalStack;
    }

}

View::Initializer();