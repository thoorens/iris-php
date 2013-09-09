<?php

namespace modules\system\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of engine
 * 
 * @author jacques
 * @license not defined
 */
class engine extends _system {

    public function indexAction() {
        // this Title var is required by the default layout defined in _system
        $this->__Title = $this->callViewHelper('welcome',1);
    }
    
    

    public function singletonAction() {
        $singleton11 = \modules\system\classes\Daughter1Of1::GetInstance();
        $singleton12 = \modules\system\classes\Daughter2Of1::GetInstance();
        $singleton12->setName('Petronille');
        echo $singleton11->getName().'<br>';
        echo $singleton12->getName().'<br>';
        die('Stop');
        $singleton21 = \modules\system\classes\Daughter1Of2::GetInstance();
        $singleton22 = \modules\system\classes\Daughter2Of2::GetInstsance();
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>system - engine - singleton</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
