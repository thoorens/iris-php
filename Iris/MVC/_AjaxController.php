<?php

namespace Iris\MVC;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
abstract class _AjaxController extends \Iris\MVC\_Controller {
    
    protected $_text = \Iris\views\helpers\AutoResource::AJAXMARK;

    protected function _echo($text){
        $this->_text .= $text;
    }
    
    public function postDispatch() {
        \Iris\views\helpers\AutoResource::AjaxTuning($this->_text, \NULL);
        echo $this->_text;
    }

}