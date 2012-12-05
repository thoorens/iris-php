<?php

namespace modules\manager\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of sides
 * 
 * @author jacques
 * @license not defined
 */
class sides extends \Iris\MVC\_Islet {

    public function leftAction() {
        $tSections = new \models\TSections();
        $tSections->where('id>', 0);
        $this->__sections = $tSections->fetchall();
    }

    public function rightAction() {
        $this->_view->dojo_Mask();
        $this->__sequence = []; //$this->getScreenList($this->_sequence);
    }

}
