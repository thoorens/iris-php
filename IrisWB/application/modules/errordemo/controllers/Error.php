<?php

namespace modules\errordemo\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of Error
 * 
 * @author jacques
 * @license not defined
 */
class Error extends \IrisInternal\main\controllers\Error {

    public function preDispatch() {
        $this->_setLayout('wberror');
        $this->__bodyColor = 'ORANGE2';
        //$url = $this->_prepareExceptionDisplay(3);die($url);
        $this->_sequence = \Iris\Structure\_Sequence::GetInstance();
        $this->_sequence->setCurrent("/");
        $this->__wbTitle = "caProut";        
    }


    

}
