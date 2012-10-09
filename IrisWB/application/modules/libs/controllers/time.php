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

    public function timeAction($time = '14:56:27') {
        $time = new \Iris\Time\Time($time);
        $this->__time = $time;
    }

    public function dateAction($date = '2012-01-08') {
        $date = new \Iris\Time\Date($date);
        $this->__date = $date;
    }

    public function stopwatchAction() {
        // these parameters are only for demonstration purpose
    }

}
