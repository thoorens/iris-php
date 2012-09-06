<?php

namespace modules\main\controllers;

use Iris\Engine\Program as _PROGRAM_;
use Iris\Users\Identity as _IDENTITY_;

\Iris\Engine\Bootstrap::$AlternativeClasses['Iris\\main\\controllers\\ERROR'] = TRUE;
include 'library/Iris/modules/main/controllers/ERROR.php';

class ERROR extends core_ERROR {

    protected $_sequence;

    public function preDispatch() {

        $this->_setLayout('error');
        //$url = $this->_prepareExceptionDisplay(3);die($url);
        $this->_sequence = \workbench\TextSequence::GetInstance();
        $this->_sequence->setCurrent("/$this->_url");
        $this->__wbTitle = $this->_sequence->getCurrentDesc();
    }

    public function privilegeAction() {
        $this->__title = $this->_("Forbidden page");
    }

    public function errorAction() {
        $this->setViewScriptName('privilege');
    }

    public function indexAction() {
        $this->__wbTitle = $this->_sequence->getCurrentDesc();
        if (\Iris\Engine\Mode::IsDevelopment()) {
            $this->__title = $this->_('Error');
//            if (isset($memory->Exception)) {
//                die('111');
//                $exception = $memory->Exception;
//                $this->__errorMessage = 'Prouta';//$exception->__toString();
//                if ($exception instanceof \Iris\Exceptions\_Exception) {
//                    $this->__title = $exception->getExceptionName();
//                }
//                else {
//                    $this->__title = get_class($exception);
//                }
//                $this->__trace = "Trace:\n" . $exception->getTraceAsString();
//                $this->__system_trace = \Iris\Exceptions\ErrorHandler::$_Trace;
//            }
            // Recuperate Log if possible
            \Iris\Log::Recuperate();
            $this->setViewScriptName('details');
            $this->__tooltip = $this->_view->dojo_toolTip();
        }
        // Production
        else {
            $this->_setLayout('errorprod');
            $this->setViewScriptName('prod');
            $this->__page = \Iris\Exceptions\ErrorHandler::$_Trace[0];
            $this->__title = $this->_('Error');
            $this->__wbTitle = $this->_('Error');
            error_log('Problème', 1, 'jacques@thoorens.net');
        }
    }

    public function documentAction($errNumber) {
        $this->__('err', $errNumber);
        $this->__('dev', _PROGRAM_::IsDevelopment());
    }

}

?>
