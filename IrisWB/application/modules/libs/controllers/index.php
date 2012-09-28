<?php

namespace modules\libs\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _libs {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>libs - index - index</h1> ',
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

}
