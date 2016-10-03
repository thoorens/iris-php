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
    
    public function formAction($submit = 1) {
        $post = \Iris\Engine\Superglobal::GetSession('oldPost',[]);
        $this->__post = $post;
        if($submit>1){
            $message = "Press one of the $submit submit buttons to see the result";
        }
        else{
             $message = "Press the submit button to see the result";
        }
        $this->__message = count($post) ? '' :$message;
        $this->_renderScript('post');
    }


}
