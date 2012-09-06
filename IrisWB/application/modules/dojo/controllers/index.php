<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class index extends _dojo {

    public function indexAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => '<h1>dojo - index - index</h1>' ,
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }

}
