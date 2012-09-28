<?php

namespace modules\libs\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of times
 * 
 * @author jacques
 * @license not defined
 */
class time extends _libs {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>libs - times - date</h1> ',
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

    public function dateAction() {
        $date = new \Iris\Time\Date('2012-04-08');
        $this->__date = $date;
    }
    public function stopwatchAction() {
        // these parameters are only for demonstration purpose
        
    }
}
