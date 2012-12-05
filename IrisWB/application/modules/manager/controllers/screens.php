<?php

namespace modules\manager\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of screens
 * 
 * @author jacques
 * @license not defined
 */
class screens extends _manager {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>manager - screens - index</h1> ',
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

}
