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

    /**
     * The text produced by the action 
     * @var string
     */
    protected $_text = '';

    /**
     * The MIME type of the produced ouput
     * 
     * @var string
     */
    protected $_type = self::HTML;

    /**
     * By default Ajax controller has ACL, this behaviour is controlled
     * by this var
     * 
     * @var boolean
     */
    protected $_hasACL = \TRUE;

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
    }

    /**
     * The Ajax controller can produce an output from a view script (which is a partial and follows
     * the partial naming convention).
     * 
     * @param type $scriptName The name of the script (begins by _ or has a complex path
     */
    protected function _renderScript($scriptName) {
        $this->_text .= $this->callViewHelper('partial', $scriptName, $this->_view);
    }

    /**
     * The Ajax controller can produce an output from a simple text
     * 
     * @param string $text The text to send by the ajax controller
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
        $HTMLMode and $text .= \Iris\Subhelpers\Head::AJAXMARK;
        $text .= $this->_text;
        $HTMLMode and \Iris\Subhelpers\Head::AjaxTuning($text, \NULL);
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

    
    /**
     * Controls the verifcation of ACL according to the setting of _hasACL var.
     */
    protected function _verifyAcl() {
        if ($this->_hasACL) {
            parent::_verifyAcl();
        }
    }

}