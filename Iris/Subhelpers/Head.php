<?php

namespace Iris\Subhelpers;

defined('TAB2') or define('TAB2', "\t\t");

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
 * This class is a subhelper for the helper Head family. It prepares the
 * head part of each page. It does not use any of the usal features of 
 * a subhelper, except that it is called by helper.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Head implements \Iris\Design\iSingleton {

    use \Iris\Engine\tSingleton;



    /**
     * A place holder for all the loader
     */

    const LOADERMARK = "\t<!-- LOADERS -->\n";
    /**
     * A place holder for Javascript in Ajax mode
     */
    const AJAXMARK = "\t<!-- AJAX -->\n";

    /**
     * A convenient way to say Ajaxmode is true
     */
    const AJAXMODE = \TRUE;
    /**
     * The contrary of Ajax mode is header mode
     */
    const HEADERMODE = \FALSE;

    /**
     * The components of the &lt;head> part of the file
     * @var array(string)
     */
    private $_components = array();

    /**
     *
     * @var array 
     */
    private $_html = array();

    /**
     * The list of associated loader
     * @var array 
     */
    private $_additionalHeadLoader = array();

    /**
     * Realizes the final rendering of the head part of the page
     * @return string
     * @todo add the other meta
     */
    private function _render() {
        $this->_contentType();
        $this->_author();
        $this->_description();
        $this->_siteIcon();
        $this->_title();
        $this->_meta('description');
        $this->_meta('author');

        $this->_writeMark();
        $html = implode(CRLF . TAB2, $this->_html) . CRLF;
        $this->_html = array(); // all treated lines are erased
        return TAB2 . $html;
    }

    /**
     * Registers a new loader in the head process
     * 
     * @param type $className
     */
    public function registerLoader($className) {
        $this->_additionalHeadLoader[] = $className;
    }

    /**
     * Magic method to not declared methods: presently only setParameter is
     * supported
     * 
     * @param type $name
     * @param type $arguments
     * @return \Iris\Subhelpers\Head
     */
    public function __call($name, $arguments) {
        if (strpos($name, 'set') === 0) {
            $this->_components[strtolower(substr($name, 3))] = $arguments[0];
        }
        return $this;
    }

    /**
     * Returns a place holder string which will be replaced by the 
     * actual header
     * 
     * @return string
     */
    public function writeMark() {
        return self::LOADERMARK;
    }

    /**
     * Creates the "content type" meta tag
     */
    private function _contentType() {
        $charset = $this->_takeOnce('charset', 'UTF-8');
        $this->_html[] = sprintf('<meta http-equiv="Content-Type" content="text/html; charset=%s" />', $charset);
    }

    /**
     * Creates a meta tag giving its name.
     * 
     * @param type $metaName
     */
    public function _meta($metaName) {
        $value = $this->_takeOnce($metaName, \NULL);
        if (!is_null($value)) {
            $this->_html[] = sprintf('<meta name="' . $metaName . '" content="%s" />', $value);
        }
    }

    /**
     * Creates a shortcut icon
     */
    private function _siteIcon() {
        $iconFile = $this->_takeOnce('iconfile', "/images/favicon.ico");
        if (!is_null($iconFile)) {
            $this->_html[] = sprintf('<link href="%s" rel="shortcut icon" />', $iconFile);
        }
    }

    /**
     * Retrieves a value by its name among the components, if it exists. 
     * Returns a default value otherwise. The components is erased.
     * 
     * @param string $paramName
     * @param mixed $defaultValue
     * @return mixed
     */
    private function _takeOnce($paramName, $defaultValue) {
        if (!isset($this->_components[$paramName])) {
            $value = $defaultValue;
        }
        else {
            $value = $this->_components[$paramName];
            unset($this->_components[$paramName]);
        }
        return $value;
    }

    /** ---------------------------------------------------------------------------------------------
     *  STATIC FUNCTIONS
     *  --------------------------------------------------------------------------------------------- 
     */

    /**
     * Finalizes the hmtl text:<ul>
     * <li> by replacing the place holder marker in the header (or beginning)
     * <li> optionally adding javascript code just before &lt;/body> (not in Ajaxmode)
     * </ul>
     * @param string $text The prefinal html text for the page (to be modified)
     * @param \Iris\Time\RuntimeDuration $runtimeDuration The time mesurement since the index.php start
     * @param string $componentId The id of the component to write into 
     */
    public static function HeaderBodyTuning(&$text, $runtimeDuration = \NULL, $componentId = 'iris_RTD') {
        self::_MakeTuning(self::HEADERMODE, $text, $runtimeDuration, $componentId);
    }

    /**
     * In Ajax mode, only the place holder marker is replaced.
     * @param string $text The prefinal html text for the page (to be modified)
     */
    public static function AjaxTuning(&$text) {
        self::_MakeTuning(self::AJAXMODE, $text, \NULL, \NULL);
    }

    /**
     * Replaces the html comment by scripts and styles
     * and add javascript code before &lt;/body>
     * 
     * @param boolen $ajaxMode
     * @param string $text The prefinal html text for the page (to be modified)
     * @param \Iris\Time\RuntimeDuration $runtimeDuration The time mesurement since the index.php start
     * @param string $componentId The id of the component to write into 
     */
    private static function _MakeTuning($ajaxMode, &$text, $runtimeDuration, $componentId) {
        try {
            $auto = self::GetInstance();
            $loaders = $auto->_render();
            foreach ($auto->_additionalHeadLoader as $loaderName) {
                $loader = $loaderName::getInstance();
                $loaders .= $loader->render($ajaxMode);
            }
            $starter = \Iris\views\helpers\JavascriptStarter::GetInstance()->render();
            if ($ajaxMode) {
                $text = str_replace(self::AJAXMARK, $loaders, $text);
            }
            else {
                $text = \str_replace(self::LOADERMARK, $loaders, $text);
                if (\Iris\SysConfig\Settings::HasMD5Signature()) {
                    $starter .= \Iris\views\helpers\Signature::computeMD5($text);
                    \Iris\views\helpers\Signature::computeMD5($loaders);
                }
                if (\Iris\SysConfig\Settings::GetDisplayRuntimeDisplay() and !is_null($runtimeDuration)) {
                    $starter .= $runtimeDuration->jsDisplay($componentId);
                }
                $text = \str_replace('</body>', $starter . "\n</body>", $text);
            }
        }
        catch (\Exception $exception) {
            \Iris\Engine\Debug::Kill(\Iris\Engine\Program::ErrorBox($exception->__toString(), 'Fatal error during final tuning'));
        }
    }

}

