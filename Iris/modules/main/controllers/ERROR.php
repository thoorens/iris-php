<?php

namespace modules\main\controllers;

require_once 'library/Iris/Exceptions/PHPException.php';

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
 * This class provides methods for error management in IRIS-PHP.
 * Each of them can be overwritten as explained in ERROR class.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class core_ERROR extends \Iris\MVC\_Controller implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;

    /**
     *
     * @var \Iris\Subhelpers\ErrorDisplay
     */
    protected $_subHelper;
    protected $_url;

    /**
     * _init() is final here to force the definition of
     * layout. It is still possible to use predispatch
     * to define another layout.
     */
    final protected function _init() {
        $errorInformation = \Iris\Exceptions\ErrorInformation::GetInstance();
        $this->_subHelper = \Iris\Subhelpers\ErrorDisplay::GetInstance();
        if (!$errorInformation->errorDisplayReady($this->_url)) {
            $this->_url = $this->_subHelper->prepareExceptionDisplay();
            // in case of error in eval
            if (!\Iris\MVC\View::GetEvalStack()->isEmpty()) {
                $evaledFile = \Iris\MVC\View::GetEvalStack()->pop()->getReadScriptFileName();
                $this->_subHelper->addMessage("<br/>See file $evaledFile");
            }
        }
        $this->_setLayout('error');
        $this->__tooltip = $this->callViewHelper('dojo_toolTip');
    }

    public function errorAction() {
        $this->__Title = "OK";
    }

    public function privilegeAction() {
        $this->__title = $this->_("Impossible access");
    }

    public function indexAction() {
        // Recuperate Log if possible
        \Iris\Log::Recuperate();
        if (\Iris\Engine\Mode::IsProduction()) {
            $this->setViewScriptName('prod');
            $this->_setLayout('errorprod');
        }
    }

//        $errorMessage = 'No message';
//        $exception = \Iris\Exceptions\_Exception::GetLastException();
//        if (!is_null($exception)) {
//            if ($exception instanceof \Iris\Exceptions\_Exception) {
//                list($errorMessage, $trace, $title) = $exception->prepareDisplay();
//            }
//            else {
//                $errorMessage = $exception->getMessage();
//                $title = 'PHP system exception';
//                $trace = $exception->getTrace();
//            }
//            $errorMessage = $exception->getMessage();
//            if ($exception instanceof \Iris\Exceptions\ViewException) {
//                $file = $exception->getFileName();
//                $line = $exception->getLineNumber();
//                $errorMessage = "Error in file $file line $line <br>";
//                if (!is_null($previous = $exception->getPrevious())) {
//                    $errorMessage .= $previous->getMessage();
//                }
//                $this->__title = $exception->getMessage();
//            }
//            elseif ($exception instanceof \Iris\Exceptions\_Exception) {
//                //die($exception->getExceptionName());
//                $this->__title = $exception->getExceptionName();
////                if ($exception instanceof \Iris\Exceptions\ViewException) {
////                    $errorMessage .= '<br/><br/>It may help to retest this page with <b>?EvalError=1</b> in URL 
////                        to obtain more information on error.<br/>';
////                }
//            }
//            else {
//                $this->__title = get_class($exception);
//            }
//            // trace can contain html fragments which could interfere with page layout. '<' is replaced
//            $this->__trace = "Trace:\n" . str_replace('<', '&lt;', $exception->getTraceAsString());
//            $trace = $this->__systemTrace = \Iris\Exceptions\ErrorHandler::$_Trace;
//        }
//        else {
//            $title = "Unkwown fatal error";
//            $trace = array();
//        }
//        $this->__errorMessage = $errorMessage;
//        $this->__title = $title;
//        $this->__trace = $trace;
}

// Permits overwriting of ERROR
if (!isset(\Iris\Engine\Bootstrap::$AlternativeClasses['Iris\\main\\controllers\\ERROR'])) {

    class ERROR extends core_ERROR {
        
    }

}
?>
