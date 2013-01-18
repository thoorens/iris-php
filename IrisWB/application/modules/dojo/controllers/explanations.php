<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of explanations
 * 
 * @author jacques
 * @license not defined
 */
class explanations extends _dojo {

    public function detailsClickAction() {
        $this->__details = $this->_view->dojo_details();
    }

    public function detailsTitlePaneAction() {
        $this->_view->dojo_titlePane('panes');
    }

    public function detailsMouseEventsAction() {
        $this->__details1 = $this->_view->dojo_details();
        $this->__details2 = $this->_view->dojo_details();
    }

    public function detailsTTAction() {
        $this->__detailsTT = $this->_view->dojo_detailsTT();
    }

    public function tooltipsAction() {
        $this->__toolTips = $this->_view->dojo_toolTip();
        $this->_view->styleLoader('djwidth', '.dijitTooltipContainer {max-width:400px;background-color:#FFD}');
    }

}
