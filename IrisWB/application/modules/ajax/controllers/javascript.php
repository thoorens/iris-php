<?php

namespace modules\ajax\controllers;
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
 * Illustration of javascript : <ul>
 * <li> a simple style applied to a div
 * <li> a synchro sending message to a receiver
 * <li> a synchro controlled by buttons 
 * @author jacques
 * @license not defined
 */
class javascript extends _ajax {

    public function indexAction() {
        $this->__functionCode = <<<JS
   domClass.add('zone2','yellowcolor');
JS
        ;
    }

    public function synchroAction() {
        // create a syncho sending MESS message during 10 seconds
        //$this->callViewHelper('synchro')->send('MESS', 10000);
    }

    /**
     * This action starts a synchro with 1/10 sec granularity
     * and accepts control from buttons sending CMESS signals
     */
    public function contSynchroAction() {
        $this->callViewHelper('synchro')
                // in display (divide timer)
                ->setRefreshingInterval(100)
                // in processing (sleeping duration)
                ->setGranularity(100)
                // MESS is the client signal
                // CMESS is the controller signal
                ->send('MESS', 10000, 'CMESS');
    }

    public function testAjaxAction($screen=1){
       $this->callViewHelper('synchro')
                // in display (divide timer)
                ->setRefreshingInterval(100)
                // in processing (sleeping duration)
                ->setGranularity(100)
                // MESS is the client signal
                // CMESS is the controller signal
                ->send('MESS', 10000, 'CMESS');
    }
    public function controlPanelAction(){
        
    }

    public function getTabAction(){
        
    }
    
    public function putPinkAction(){
        $this->_putColor(1);
        $this->__label = 'Paint pink';
    }
    
    public function putYellowAction(){
        $this->_putColor(0);
        $this->__label = 'Paint yellow';
    }
    
    private function _putColor($color){
        // if AdminToolBar is in Ajax mode, screen will be unreadable
        // in debug mode
        \ILO\views\helpers\AdminToolBar::$AjaxMode = \FALSE;
        $this->__type = \Iris\Ajax\_AjaxProvider::JS;
        $this->__ajaxFile = "/ajax/get/color/mytext/$color";
        $this->setViewScriptName('putColor');
    }
    
    
}
