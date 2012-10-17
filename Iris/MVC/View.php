<?php

namespace Iris\MVC;

use Iris\Engine as ie,
    Iris\Exceptions as ix;

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
class View {

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
     *
     * @var Iris\Engine\Response
     */
    protected $_response;
//    public function getActionView() {
//        return $this->_viewScriptName;
//    }

    protected $_prerending = '';

    public function setViewScriptName($name) {
        $this->_viewScriptName = $name;
    }

    /**
     *
     * @param type $name
     * @param type $value 
     * 
     * @todo interdire accès aux variables non publiques
     */
    public function __set($name, $value) {
        $this->_properties[$name] = $value;
    }

    /**
     * Instance variable are maintained in an array
     * 
     * @param type $name
     * @return type 
     */
    public function __get($name) {
        return $this->_properties[$name];
    }

    public function addPrerending($text) {
        $this->_prerending .= $text;
    }

    /**
     * Evaluates the (implicit or explicit) view script
     * and renders it as html string
     *  
     * @param string $forcedScriptName (usually NULL)
     * @return string 
     */
    public function render($forcedScriptName = NULL) {
        if (strpos($this->_viewScriptName, '/')) {
            $forcedScriptName = $this->_viewScriptName;
        }
        // File generators and loaders have no view part, stop them and no time measurement.
        if ($forcedScriptName == '__NO_RENDER__' or $this->_viewScriptName == '__NO_RENDER__') {
            \Iris\Time\StopWatch::DisableRTDDisplay();
            die(''); // stop process - file is complete
        }
        // In case of simple quoting, there is no template file to treat
        elseif (($forcedScriptName == '__QUOTE__' or $this->_viewScriptName == '__QUOTE__')) {
            echo $this->_prerending; // the "quotation" has been already prerended
            return;
        }
        // In normal cases, there is a template file to render
        else {
            
            $template = $this->_getTemplate($forcedScriptName);
            $iviewFile = $this->_renderTemplate($template);
            ob_start();
            echo $this->_prerending;
            $this->_eval($iviewFile);
            $page = ob_get_clean();
            return $page;
        }
    }

    /**
     * Explores the array template and replace all template tags 
     * by php short echo tags. Returns a string.
     *  
     * @param array $template
     * @return string 
     */
    private function _renderTemplate($template) {
        $inStyle = $inScript = FALSE;
        foreach ($template as &$line) {
            if (strpos($line, '<style') !== FALSE) {
                $inStyle = TRUE;
            }if (strpos($line, '<script') !== FALSE) {
                $inScript = TRUE;
            }
            if (!$inStyle and !$inScript) {
                $line = preg_replace("/({)(.*?)(})/i", '<?= $this->$2?>', $line);
            }
            if (strpos($line, '</style>') !== FALSE) {
                $inStyle = FALSE;
            }
            if (strpos($line, '</script>') !== FALSE) {
                $inScript = FALSE;
            }
        }
        return implode("", $template);
    }

    /**
     * Evals the text passed in argument (with a lot of error trapping)
     * 
     * @param type $phtml
     * @param type $scriptFileName The name of the script (for 
     */
    private function _eval($phtml) {
        foreach ($this->_properties as $name => $value) {
            ${$name} = $value;
        }
        if (\Iris\Engine\Mode::IsDevelopment() and !is_null(\Iris\Engine\Superglobal::GetGet('EvalError'))) {
            eval("?>" . $phtml);
        }
        else {
            try {
                eval("?>" . $phtml);
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
                $file = self::$_LastUsedScript;
                $trace = $exception->getTrace();
                // take the line number from the trace containing eval()'d error
                foreach ($trace as $traceItem) {
                    if (isset($traceItem['file']) and strpos($traceItem['file'], "eval()'d code") !== FALSE) {
                        $line = $traceItem['line'];
                        break;
                    }
                }
                $viewException = new \Iris\Exceptions\ViewException('Error in a view evaluation', NULL, $exception);
                $viewException->setFileName($file);
                $viewException->setLineNumber($line);
                $viewException->setViewType($viewType);
                $viewException->setErrorMessage($errMessage);
                throw $viewException;
            }
        }
    }

    /**
     * Get a template content. In view, by reading a file found by the loader
     * 
     * @param type $forcedScriptName
     * @return string 
     */
    protected function _getTemplate($forcedScriptName) {
        $loader = ie\Loader::GetInstance();
        $viewType = static::$_ViewType;
        try {
            // the case where a scriptName has been explicitely required (renderNow)
            if (!is_null($forcedScriptName)) {
                $viewFile = $loader->loadView($forcedScriptName, "scripts", $this->_response);
            }
            else {
                $viewFile = $loader->loadView($this->_viewScriptName, $this->_viewDirectory(), $this->_response);
            }
        }
        catch (\Iris\Exceptions\LoaderException $l_ex) {
            throw new \Iris\Exceptions\LoaderException("Problem with $viewType " .
                    $l_ex->getMessage(), NULL, $l_ex);
        }
        static::$_LastUsedScript = $viewFile; // for debugging purpose
        $scriptFileName = IRIS_ROOT_PATH . '/' . $viewFile;
        $file = file($scriptFileName);
        return $file;
    }

    /**
     * Returns the name of the directory where to find the script
     * corresponding to the view (or the layout or the partial in subclasses)
     * 
     * @return String 
     */
    protected function _viewDirectory() {
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

    public function __toString() {
        return print_r($this, TRUE);
    }

    /**
     * Gets the last used script name (for debugging or demo purpose)
     * @return string
     */
    public static function GetLastScriptName() {
        return static::$_LastUsedScript;
    }

}

