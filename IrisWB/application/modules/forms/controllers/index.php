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
class index extends _forms {

    protected function _init(){
        $this->setViewScriptName('all');
        
    }
    
    public function indexAction() {
        // these parameters are only for demonstration purpose
        /* @var $form \Iris\Forms\_Form */
        $form = $this->makeForm(array());
        //$form->getComponent($name)
        $this->__form = $form->render();
    }

    public function groupsAction() {
        // these parameters are only for demonstration purpose
        $form = $this->makeForm(array('groups'));
        $this->__title = 'Test element groups';
        $this->__form = $form->render();
    }
}
