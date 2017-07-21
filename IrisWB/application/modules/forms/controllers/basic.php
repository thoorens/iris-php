<?php

namespace modules\forms\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * This is part of the WorkBench fragment
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Basic tests of forms (both HTML and Dojo style)
 */
class basic extends _forms {

//    private $_SFF;
//    private $_DFF;

    /**
     * All actions display with the same view script
     */
    protected function _init() {
        $this->setViewScriptName('common/all');
        
    }

    /**
     * A simple form with def layout
     */
    public function htmlDefLoAction() {
        $this->__form = $this->SimpleForm([])->render();
    }

    /**
     * A simple form with def layout in Dojo mode
     */
    public function dojodDefLoAction() {
        $this->__form = $this->SimpleForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO])->render();
    }

    /**
     * A simple form with no layout
     */
    public function htmlNoLoAction() {
        $this->__form = $this->SimpleForm(['layout' => 'no'])->render();
    }

    /**
     * A simple form with no layout in Dojo mode
     */
    public function dojoNoLoAction() {
        $this->__form = $this->SimpleForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO, 'layout' => 'no'])->render();
    }

    /**
     * A simple form with tab layout
     */
    public function htmlTabLoAction() {
        $this->__form = $this->SimpleForm(['layout' => 'tab'])->render();
    }

    /**
     * A simple form with tab layout in Dojo Mode
     */
    public function dojocssloAction() {
        $this->__form = $this->SimpleForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO,'layout' => 'tab'])->render();
    }

    /**
     * A simple form with tab layout
     */
    public function htmlcssloAction() {
        $this->__form = $this->SimpleForm(['layout' => 'tab'])->render();
    }

    /**
     * A simple form with tab layout in Dojo Mode
     */
    public function dojotabloAction() {
        $this->__form = $this->SimpleForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO,'layout' => 'tab'])->render();
    }

    /**
     * Test of multi elements with only two elements per line
     */
    public function perline2Action() {
        //$elements = FormConst::
        $this->__form = $this->MultiPerlineForm(['perline' => 2])->render();
    }

    
    /**
     * Test of multi elements with only two elements per line in Dojo Mode
     */
    public function perline2DojoAction() {
        $this->__form = $this->MultiPerlineForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO,'perline' => 2])->render();
    }

    public function requiredAction(){
        $this->__form = $this->RequiredForm()->render();
    }
    
    public function requiredDojoAction(){
        $this->__form = $this->RequiredForm(['factoryType' => \Iris\Forms\_FormFactory::DOJO])->render();
    }
    

    public function groupsAction() {
        $form = $this->makeGroupForm(new \Iris\Forms\StandardFormFactory());
        $this->__form = $form->render();
        $this->__other = ['Dojo form', '/forms/dojo/groups', 'Goto Dojo form'];
    }

}
