<?php

namespace modules\manager\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of categories
 * 
 * @author jacques
 * @license not defined
 */
class categories extends _manager {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>manager - categories - index</h1> ',
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

}
