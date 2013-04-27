<?php

namespace modules\helpers\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of controllers
 * 
 * @author jacques
 * @license not defined
 */
class controllers extends _helpers {

    public function indexAction() {
        $this->__computeVar = $this->compute(7,'+');
        $this->__localComputeVar = $this->localCompute(8, '*');
        $this->__localCompute2Var = $this->compute2(9,'-');
        $this->__systemComputeVar = $this->testCompute(10,'/');
        $this->__libComputeVar = $this->dojo_compute(11,'-');
    }
    
    

}
