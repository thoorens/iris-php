<?php

namespace Iris\modules\main\controllers;

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
 * An error controller
 * 
 */
abstract class _Error extends \Iris\MVC\_Controller {

    /**
     *
     * @var \Iris\Errors\Settings
     */
    protected $_errorSettings;
    protected $_isDevelopment;

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
        $this->_errorSettings = \Iris\Errors\Settings::GetInstance();
        $this->_isDevelopment = \Iris\Engine\Mode::IsDevelopment();
        $this->_setLayout('error');
        $this->__title = $this->_errorSettings->getTitle();
        $this->_exception = \Iris\Engine\Memory::Get('untreatedException', new \Iris\Exceptions\InternalException('Unkown exception'));
    }

    /**
     * Error screens must be visible by all. Security does nothing. Daughnter
     * class cannot change this behavior.
     */
    public final function security() {
        
    }

    /**
     * The default treatment for general error 
     */
    public final function standardAction() {
        if ($this->_isDevelopment) {
            $this->_standardDevelopment();
        }
        else {
            $this->_standardProduction();
        }
    }

    protected abstract function _standardProduction();

    protected abstract function _standardDevelopment();

    /**
     * A default treatment for privilege error
     */
    public final function privilegeAction() {
        if ($this->_isDevelopment) {
            $this->_privilegeDevelopment();
        }
        else {
            $this->_privilegeProduction();
        }
    }

    protected abstract function _privilegeProduction();

    protected abstract function _privilegeDevelopment();

    /**
     * A treatment for a fatal error (usually an error in error display)
     * Not used in development
     */
    public final function fatalAction() {
        $this->setViewScriptName('Errors/fatal');
    }

//    public function indexDevAction() {
//        $this->setViewScriptName('indexDev');
//        /* @var $exception \Exception */
//        $exception = \Iris\Engine\Memory::Get('untreatedException', \NULL);
//        if (is_null($exception)) {
//            $this->reroute('/');
//        }
//        $this->__message = $exception->getMessage();
//        $this->__file = $exception->getFile();
//        $this->__line = $exception->getLine();
//        if ($exception instanceof \Iris\Exceptions\_Exception) {
//            $this->__type = $exception->getExceptionName();
//        }
//        else {
//            $this->__type = "PHP";
//        }
//        //iris_debug($exception->getTrace());
//    }

    /**
     * A default treatment for privilege error in development
     * Same as in production (but may be overriden)
     */
    public function privilegeDevAction() {
        $this->setViewScriptName('privilege');
    }

    protected function _exceptionDescription() {
        $this->__file = $this->_exception->getFile();
        $this->__line = $this->_exception->getLine();
        $this->__type = $this->_exception->getExceptionName();
        $this->__message = $this->_exception->getMessage();
    }

    /**
     * 
     * @param \Iris\Exceptions\_Exception $exception
     */
    protected final function _displayStackLevel() {
        $stackLevel = $this->_errorSettings->getErrorStackLevel();
        if (is_null($stackLevel)) {
            $this->_noStack();
        }
        elseif ($stackLevel == -1) {
            $this->_stackInTabs();
        }
        else {
            $this->_stackDetails($stackLevel);
        }
    }

    /**
     * No stack display
     */
    protected function _noStack() {
        $this->__partialName = 'errorsimple';
    }

    /**
     * Display a precise stack level
     * 
     * @param int $stackLevel
     */
    protected function _stackDetails($stackLevel) {
        $this->__partialName = 'errordetails';
        $this->__stack = "Level $stackLevel";
        //$this->__stack = $this->_exception->getTrace()[$stackLevel];
    }

    /**
     * Display all stack levels
     */
    protected function _stackInTabs() {
        $this->__partialName = 'errorfull';
        $tabs = $this->callViewHelper('dojo_tabContainer', 'tabs');
        $tabs->setDefault('desc')
                ->setItems(array('desc' => 'Description', 'stack' => 'Error stack', 'det' => 'Details'));
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        $errorInformation = \Iris\Errors\ErrorInformation::GetInstance();
        $subHelper = \Iris\Subhelpers\ErrorDisplay::GetInstance();
        if (!$errorInformation->errorDisplayReady($url)) {
            $url = $subHelper->prepareExceptionDisplay($errorInformation->getException());
            // in case of error in eval
            if (!\Iris\MVC\View::GetEvalStack()->isEmpty()) {
                $evaledFile = \Iris\MVC\View::GetEvalStack()->pop()->getReadScriptFileName();
                $subHelper->addMessage("<br/>See file $evaledFile");
            }
        }
        $this->__tooltip = $this->callViewHelper('dojo_toolTip');
        // ---------------------------------------------
        $lines = count($subHelper->details);
        for ($n = 0; $n < $lines-1; $n++) {
            $tabs->addItem("det_$n", "Level $n");
        }
        $this->__ignore = $lines-1;
    }

}

