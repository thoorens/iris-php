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
class basic extends _forms {

    
    protected function _init(){
        $this->setViewScriptName('common/all');
        
    }
    
    /**
     * A simple form with def layout
     */
    public function htmlAction(){
        $this->_form(new \Iris\Forms\StandardFormFactory());
    }
    
    /**
     * A simple form with def layout in Dojo mode
     */
    public function dojoAction(){
        $this->_form(new \Dojo\Forms\FormFactory());
        
    }
    
    /**
     * A simple form with no layout
     */
    public function htmlnoloAction(){
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'Simple', ['layout' => 'no']);
    }
    
    
    /**
     * A simple form with no layout in Dojo mode
     */
    public function dojonoloAction(){
        $this->_form(new \Dojo\Forms\FormFactory(), 'Simple', ['layout' =>'no']);
        
    }
    
    /**
     * A simple form with tab layout
     */
    public function htmltabloAction(){
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'Simple', ['layout' =>'tab']);
    }
    
    /**
     * A simple form with tab layout in Dojo Mode
     */
    public function dojotabloAction(){
        $this->_form(new \Dojo\Forms\FormFactory(), 'Simple', ['layout' =>'tab']);
        
    }
    
    public function perline2Action(){
        //$elements = FormConst::
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'MultiPerline', ['perline' => 2]);
    }
    
    public function perline2DojoAction(){
        $this->_form(new \Dojo\Forms\FormFactory(), 'MultiPerline', ['perline' => 2]);
    }
    
    private function _form($formFactory, $formClass, $specials) {
        $formClass .= 'Form';
        /* @var $form \Iris\Forms\_Form */
        $form = $this->$formClass($formFactory, $specials);
        $this->__form = $form->render();
        \Iris\Users\Session::GetInstance()->oldPost = $_POST;
        //iris_debug($this->_view->form);
    }

    public function groupsAction() {
        $form = $this->makeGroupForm(new \Iris\Forms\StandardFormFactory());
        $this->__form = $form->render();
        $this->__other = ['Dojo form','/forms/dojo/groups','Goto Dojo form'];
    }
}
