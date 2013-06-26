<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of links
 * 
 * @author jacques
 * @license not defined
 */
class forms extends _dojo {

    /**
     *
     * @var \Dojo\Forms\Elements\Editor;
     */
    private $_form;

    protected function _init() {
        $this->_setLayout('application');
        $this->_form = \Dojo\Forms\FormFactory::GetDefaultFormFactory()->createForm('test');
    }


    public function editor1Action(){
        $this->setViewScriptName('editorAll');
        $editor = $this->_form->createEditor('editor');
        $editor->addTo($this->_form)->setValue('Test');
        $this->__form = $this->_form->render();
    }
    
    
}
