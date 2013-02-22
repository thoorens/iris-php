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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * An Ajax controler is a special controller <ul>
 * <li> which has a type
 * <li> which sends a header according to its type
 * <li> which does not have and cannot have neither layout nor view script file
 * (nor the methods for setting them)
 * <li> which does not use RTD measurement
 * <li> which have a special mechanism of script loading
 * <li> which have special methods to produce its output
 * <li> has special methods _moduleInit and postDispatch
 * </ul>
 * It can be used tp get Ajax or any file needing dynamic generation
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _AjaxController extends \Iris\MVC\_Controller {

    const JSON = 'text/JSON';
    const HTML = 'text/html';
    const CSS = 'text/css';

    protected $_text = ''; 
    protected $_type = self::HTML;

    /**
     * Application init cannot be rewritten
     */
    protected final function _applicationInit() {
        
    }

    /**
     * The module init is reused to set no layout et a pseudo view script name
     */
    protected final function _moduleInit() {
        parent::_setLayout(NULL);
        $this->_view->setViewScriptName('__AJAX__');
    }

    /**
     * The setViewScriptName method cannot be used (no view script)
     * 
     * @param string $scriptName
     * @throws \Iris\Exceptions\ControllerException
     * @see renderScript method
     */
    public final function setViewScriptName($scriptName) {
        throw new \Iris\Exceptions\ControllerException('Ajax controllers can\'t have standard view scripts');
    }

    /**
     * The _setLayout cannot be used (no layout)
     * @param string $layoutName
     * @throws \Iris\Exceptions\ControllerException
     */
    protected final function _setLayout($layoutName) {
        throw new \Iris\Exceptions\ControllerException('Ajax controllers can\'t have a layout.');
        ;
    }

    /**
     * The Ajax controller can produce an output from a view script (which is a partial and follows
     * the partial naming convention).
     * 
     * @param type $scriptName
     */
    protected function _renderScript($scriptName) {
        $this->_text .= $this->callViewHelper('partial', $scriptName, $this->_view);
    }

    /**
     * A way to send text as a litteral
     * 
     * @param string $text
     */
    protected function _renderLiteral($text) {
        $this->_text .= $text;
    }

    /**
     * The postDispatch method will echo the header and the produced text, after optionally
     * replace the AJAX markers
     */
    public final function postDispatch() {
        header("content-type:$this->_type");
        $text = '';
        $HTMLMode = ($this->_type == self::HTML);
        $HTMLMode and $text .= \Iris\views\helpers\AutoResource::AJAXMARK;
        $text .= $this->_text;
        $HTMLMode and \Iris\views\helpers\AutoResource::AjaxTuning($text, \NULL);
        echo $text;
    }

    /**
     * The setter for the type 
     * 
     * @param string $type
     */
    public function setAjaxType($type) {
        $this->_type = $type;
    }

}