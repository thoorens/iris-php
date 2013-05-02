<?php

namespace modules\dojo\controllers;

/**
 * 
 */
class getdata extends \Iris\MVC\_AjaxController {

    /**
     * Recuperates a JSON file for a slideshow demo
     */
    public function imagesAction() {
        $this->setAjaxType(self::JSON);
        $images = array('items' => $this->ExampleImages());
        $this->_renderLiteral(json_encode($images));
    }

}
