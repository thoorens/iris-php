<?php

namespace modules\main\controllers;

class index extends _main {

    /**
     * Set a layout at controller level
     */
    public function _init() {
        $this->_setLayout('cont');
    }

    public function indexAction() {
        $this->_setLayout('color');
        $this->__bodyColor = 'WHITE';
    }

    public function errorAction($number) {
        switch ($number) {
            case 1:
                $this->__message = 'Error in sequence';
        }
    }

    public function endAction() {
        
    }

    public function dojoAction() {
        \Iris\Time\StopWatch::DisableRTDDisplay();
    }

    public function tocAction() {
        $this->_view->dojo_Mask();
        $list = $this->_sequence->getStructuredSequence();
        foreach ($list as $key => $value) {
            if (is_array($value)) {
                array_walk($value,array($this,'_keepDescription'));
                $newList[$key] = $value;
            }
            else {
                $newList[$key] = $this->_keepDescription($value);
            }
        }
        $this->__sequence = $newList;
    }

    private function _keepDescription(&$value, $dummy=\NULL) {
        list($description, $dum) = explode('|', $value . '|');
        $value = $description;
        return $value;
    }

    public function controllerAction() {
        
    }

}
