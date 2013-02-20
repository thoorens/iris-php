<?php

namespace modules\ajax\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of javascript
 * 
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
}
