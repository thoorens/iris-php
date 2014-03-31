<?php

namespace modules\forms\controllers;

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
 * @copyright 2011-2013 Jacques THOORENS
 */


/**
 * Basic tests of forms (both HTML and Dojo style
 */
class basic extends _forms {

    protected function _init() {
        $this->setViewScriptName('common/all');
    }

    /**
     * A simple form with def layout
     */
    public function htmlAction() {
        $this->_form(new \Iris\Forms\StandardFormFactory(new \Iris\Forms\StandardFormFactory()), 'Simple');
    }

    /**
     * A simple form with def layout in Dojo mode
     */
    public function dojoAction() {
        $this->_form(new \Dojo\Forms\FormFactory(new \Dojo\Forms\FormFactory()), 'Simple');
    }

    /**
     * A simple form with no layout
     */
    public function htmlnoloAction() {
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'Simple', ['layout' => 'no']);
    }

    /**
     * A simple form with no layout in Dojo mode
     */
    public function dojonoloAction() {
        $this->_form(new \Dojo\Forms\FormFactory(), 'Simple', ['layout' => 'no']);
    }

    /**
     * A simple form with tab layout
     */
    public function htmltabloAction() {
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'Simple', ['layout' => 'tab']);
    }

    /**
     * A simple form with tab layout in Dojo Mode
     */
    public function dojotabloAction() {
        $this->_form(new \Dojo\Forms\FormFactory(), 'Simple', ['layout' => 'tab']);
    }

    /**
     * Test of multi elements with only two elements per line
     */
    public function perline2Action() {
        //$elements = FormConst::
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'MultiPerline', ['perline' => 2]);
    }

    
    /**
     * Test of multi elements with only two elements per line in Dojo Mode
     */
    public function perline2DojoAction() {
        $this->_form(new \Dojo\Forms\FormFactory(), 'MultiPerline', ['perline' => 2]);
    }

    public function requiredAction(){
        $this->_form(new \Iris\Forms\StandardFormFactory(), 'Required');
    }
    
    public function requiredDojoAction(){
        $this->_form(new \Dojo\Forms\FormFactory(), 'Required');
    }
    
    
    
    private function _form($formFactory, $formClass, $specials = []) {
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
        $this->__other = ['Dojo form', '/forms/dojo/groups', 'Goto Dojo form'];
    }

}
