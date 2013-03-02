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
 * head part of each page
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class Head implements iRenderer {

    use \Iris\Engine\tSingleton;

    const LOADERMARK = "\t<!-- LOADERS -->\n";
    const AJAXMARK = "\t<!-- AJAX -->\n";
    const AJAXMODE = \TRUE;
    const HEADERMODE = \FALSE;

    private $_components = array();
    private $_html = array();
    private $_additionalHeadLoader = array();

    public function standard() {
        $this->_contentType();
        $this->_author();
        $this->_description();
        $this->_siteIcon();
        $this->_title();
        $this->_meta('description');
        $this->_meta('author');
        
        $this->_writeMark();
        $html = implode(CRLF . TAB2, $this->_html) . CRLF;
        $this->_html = array();
        return TAB2 . $html;
    }

    public function addLoader($className) {
        $this->_additionalHeadLoader[] = $className;
    }

    public function __call($name, $arguments) {
        if (strpos($name, 'set') === 0) {
            $this->_components[strtolower(substr($name, 3))] = $arguments[0];
        }
        return $this;
    }

    public function writeMark() {
        return self::LOADERMARK;
    }

    private function _contentType() {
        $charset = $this->_take('charset', 'UTF-8');
        $this->_html[] = sprintf('<meta http-equiv="Content-Type" content="text/html; charset=%s" />', $charset);
    }

    private function _take($param, $default) {
        if (!isset($this->_components[$param])) {
            $value = $default;
        }
        else {
            $value = $this->_components[$param];
            unset($this->_components[$param]);
        }
        return $value;
    }

    public function _meta($key) {
        $value = $this->_take($key, \NULL);
        if (!is_null($value)) {
            $this->_html[] = sprintf('<meta name="'.$key.'" content="%s" />', $value);
        }
    }
    
    

    private function _siteIcon() {
        $iconFile = $this->_take('iconfile', "/images/favicon.ico");
        if (!is_null($iconFile)) {
            $this->_html[] = sprintf('<link href="%s" rel="shortcut icon" />', $iconFile);
        }
    }

    public function render(array $arg1, $arg2) {
        
    }

    /** ---------------------------------------------------------------------------------------------
     *  STATIC FUNCTIONS
     *  --------------------------------------------------------------------------------------------- 
     */
    
    /**
     * 
     * @param type $text
     * @param type $runTimeDuration
     * @param type $componentId
     */
    public static function HeaderBodyTuning(&$text, $runTimeDuration = \NULL, $componentId = 'iris_RTD') {
        self::_MakeTuning(self::HEADERMODE, $text, $runTimeDuration, $componentId);
    }

    /**
     * 
     * @param type $text
     */
    public static function AjaxTuning(&$text) {
        self::_MakeTuning(self::AJAXMODE, $text);
    }

    /**
     * Replaces the html comment by scripts and styles
     * and add javascript code before &lt;/body>
     * 
     * @param string $text The page text before finalization 
     * @param \Iris\Time\RunTimeDuration $runTimeDuration
     */
    private static function _MakeTuning($ajaxMode, &$text, $runTimeDuration = \NULL, $componentId = 'iris_RTD') {
        $auto = self::GetInstance();
        $loaders = $auto->standard();
        foreach ($auto->_additionalHeadLoader as $loaderName) {
            $loader = $loaderName::getInstance();
            $loaders .= $loader->render($ajaxMode);
        }
        $starter = \Iris\views\helpers\JavascriptStarter::GetInstance()->render();
        if ($ajaxMode) {
            $text = str_replace(self::AJAXMARK, $loaders, $text);
        }
        else {
            $text = str_replace(self::LOADERMARK, $loaders, $text);
            $starter .= \Iris\views\helpers\Signature::computeMD5($text);
            \iris\views\helpers\Signature::computeMD5($loaders);
            if (!is_null($runTimeDuration)) {
                $starter .= $runTimeDuration->jsDisplay($componentId);
            }
        }
        $text = str_replace('</body>', $starter . "\n</body>", $text);
    }

    

}

