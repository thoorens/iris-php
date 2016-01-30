<?php
namespace Iris\modules\main\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/*
 * An abstract controller used by any error management controler. It provides
 * tools and an API. It has 3 predefined types of error and 2 modes:
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

abstract class _Error extends \Iris\MVC\_Controller {

    protected $_isProduction;

    /**
     *
     * @var \Iris\Exceptions\_Exception
     */
    protected $_exception;

    /**
     * User designed error controller inherits from this class, so they
     * cannot be derived from a standard module abstract controller. The
     * _moduleInit() method is recycled to set some parameters for the standard
     * error display mechanism. These can be overidden in _init() or predispatch().
     */
    protected final function _moduleInit() {
        $this->_isProduction = \Iris\Errors\Handler::IsProduction();
        $this->_setLayout('error');
        $this->__title = \Iris\Errors\Settings::$Title;
        $this->_exception = \Iris\Engine\Memory::Get('untreatedException', new \Iris\Exceptions\InternalException('Unkown exception'));
        \Iris\SysConfig\Settings::$DisplayRuntimeDuration = \FALSE;
        \Iris\SysConfig\Settings::$MD5Signature = \FALSE;
    }

    /**
     * Error screens must be visible by all. Security does nothing. Daughter
     * classes cannot change this behavior.
     */
    public final function security() {
        
    }

    protected final function _verifyAcl() {
        
    }

    /**
     * The default treatment for general error 
     */
    public final function standardAction() {
        if ($this->_isProduction) {
            if (\Iris\Errors\Settings::$ForceDevelopment) {
                $this->_standardDevelopment();
            }
            else {
                $this->_standardProduction();
            }
        }
        else {
            $this->_standardDevelopment();
        }
    }

    /**
     * What happens in standard production context :
     * to be defined in subclasses
     */
    protected abstract function _standardProduction();

    /**
     * What happens in standard developement context :
     * to be defined in subclasses
     */
    protected abstract function _standardDevelopment();

    /**
     * A default treatment for privilege error: it uses two methods
     * to be defined in a subclass
     */
    public final function privilegeAction() {
        if ($this->_isProduction or \Iris\Errors\Settings::$ForceDevelopment) {
            $this->_privilegeProduction();
        }
        else {
            $this->_privilegeDevelopment();
        }
    }

    /**
     * The privilege error in production mode has to be defined in a subclass
     */
    protected abstract function _privilegeProduction();

    /**
     * The privilege error in development mode has to be defined in a subclass
     */
    protected abstract function _privilegeDevelopment();

    /**
     * A treatment for a fatal error (usually an error in error display).
     * Not used in development
     */
    public final function fatalAction() {
        $this->setViewScriptName('defaultErrors/common/fatal');
    }

    /**
     * This action permits to test an error privilege even if there
     * is no acl policy on the site (only for test)
     */
    public final function impossibleAction() {
        $this->displayError(\Iris\Errors\Settings::TYPE_PRIVILEGE);
    }

    public final function documentAction($param) {
        
            switch ($param) {
                case 'notfound':
                    die('Document not found');
                    break;
            }
        
    }

    /**
     * A standard way to display error information:<ul>
     * <li> file
     * <li> line
     * <li> type
     * <li> message
     * </ul>
     */
    protected function _exceptionDescription() {
        $this->__file = $this->_exception->getFile();
        $this->__line = $this->_exception->getLine();
        if (is_a($this->_exception, '\Iris\Exceptions\_Exception')) {
            $this->__type = $this->_exception->getExceptionName();
        }
        else {
            $this->__type = get_class($this->_exception);
        }
        $this->__message = $this->_exception->getMessage();
    }

    /**
     * Will display or not the stack execution level required or all of them
     */
    protected final function _displayStackLevel() {
        $stackLevel = \Iris\Errors\Settings::$StackLevel;
// no stack (default state)
        if (is_null($stackLevel)) {
            $this->_noStack();
        }
// all the levels (?ERRORSTACK=-1 in URL)
        elseif ($stackLevel == -1) {
            $this->_stackInTabs();
        }
// the required level (?ERRORSTACK= number in URL)
        else {
            $this->_stackDetails($stackLevel);
        }
    }

    /**
     * No stack display
     */
    protected function _noStack() {
        $this->__secondPart = 'errorsimple';
    }

    /**
     * Display a precise stack level
     * 
     * @param int $stackLevel
     */
    protected function _stackDetails($stackLevel) {
        $this->__secondPart = 'errordetails';
        $this->__stack = $stackLevel;
        $this->__details = $this->callViewHelper('error')->details[$stackLevel];
//$this->__stack = $this->_exception->getTrace()[$stackLevel];
    }

    /**
     * Display all stack levels
     */
    protected function _stackInTabs() {
        $this->__secondPart = 'errorfull';
        $tabs = $this->callViewHelper('dojo_tabContainer', 'tabs');
        $tabs->setDefault('desc')
                ->setItems(array('desc' => 'Description', 'stack' => 'Error stack', 'det' => 'Details'));
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//$errorInformation = \Iris\Errors\ErrorInformation::GetInstance();
        $subHelper = \Iris\Subhelpers\ErrorDisplay::GetInstance();
        if (!\Iris\MVC\View::GetEvalStack()->isEmpty()) {
            $evaledFile = \Iris\MVC\View::GetEvalStack()->pop()->getReadScriptFileName();
            $subHelper->addMessage("<br/>See file $evaledFile");
        }
        $this->__tooltip = $this->callViewHelper('dojo_toolTip');
// ---------------------------------------------
        $lines = count($subHelper->details);
        for ($n = 0; $n < $lines - 1; $n++) {
            $tabs->addItem("det_$n", "Level $n");
        }
        $this->__ignore = $lines - 1;
    }

    protected function _createStack($subHelper) {
        
    }

}

