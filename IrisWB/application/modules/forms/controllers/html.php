<?php

namespace modules\forms\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class html extends _forms {

    protected function _init(){
        $this->setViewScriptName('common/all');
        
    }
    
    public function indexAction() {
        // these parameters are only for demonstration purpose
        /* @var $form \Iris\Forms\_Form */
        $form = $this->makeForm(new \Iris\Forms\StandardFormFactory());
        //$form->getComponent($name)
        $this->__form = $form->render();
    }

    public function groupsAction() {
        $form = $this->makeGroupForm(new \Iris\Forms\StandardFormFactory());
        $this->__form = $form->render();
        $this->__other = ['Dojo form','/forms/dojo/groups','Goto Dojo form'];
    }
}
