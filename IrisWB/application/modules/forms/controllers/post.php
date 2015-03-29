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

    protected $_hasACL = \FALSE;
    
    public function formAction() {
        $post = \Iris\Engine\Superglobal::GetSession('oldPost',[]);
        $this->__post = $post;
        $this->__message = count($post) ? '' :'Press one of the 5 submit buttons to see the result';
        $this->_renderScript('post');
    }


}
