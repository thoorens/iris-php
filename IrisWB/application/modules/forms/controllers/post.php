<?php

namespace modules\forms\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of index
 * 
 * @author jacques
 * @license not defined
 */
class post extends \Iris\MVC\_AjaxController {

    
    
    public function formAction() {
        $this->__post = \Iris\Engine\Superglobal::GetSession('oldPost', \NULL);
        $this->_renderScript('post');
    }
}
