<?php

namespace modules\helpers\controllers;

/**
 * srv_IrisWB
 * Created for IRIS-PHP 0.9 - beta
 * Description of ilo
 * 
 * @author jacques
 * @license not defined
 */
class ilo extends _helpers {

   
    

    public function userAction() {
        // these parameters are only for demonstration purpose
        $this->__(NULL, array(
            'Title' => "'<h1>helpers - ilo - user</h1>'",
            'buttons' => 1+4,
            'logoName' => 'mainLogo'));
    }
}
