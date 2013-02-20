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
    
    const JSON = 'text/JSON';
    const HTML = 'text/html';
    
    protected $_text = \Iris\views\helpers\AutoResource::AJAXMARK;
    
    protected $_type = self::HTML;

    protected final function _moduleInit() {
        parent::_setLayout(NULL);
        $this->_view->setViewScriptName('__AJAX__');
    }

    public final function setViewScriptName($scriptName) {
        throw new \Iris\Exceptions\ControllerException('Ajax controllers can\'t have standard view scripts');
    } 
    
    protected final function _setLayout($layoutName) {
        throw new \Iris\Exceptions\ControllerException('Ajax controllers can\'t have a layout.');;
    }

    
    public function renderScript($scriptName){
        $this->_text = $this->callViewHelper('partial',$scriptName, $this->_view);
    }
    
    protected function _echo($text){
        $this->_text .= $text;
    }
    
    public function postDispatch() {
        header("content-type:$this->_type");
        \Iris\views\helpers\AutoResource::AjaxTuning($this->_text, \NULL);
        echo $this->_text;
    }

    public function setAjaxType($type){
        $this->_type = $type;
    }
    
}