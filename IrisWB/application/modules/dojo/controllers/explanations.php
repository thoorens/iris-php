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
        $this->__details = $this->callViewHelper('dojo_details');
    }

    public function detailsTitlePaneAction() {
        $this->callViewHelper('dojo_titlePane','panes');
    }

    public function detailsMouseEventsAction() {
        $this->__details1 = $this->callViewHelper('dojo_details');
        $this->__details2 = $this->callViewHelper('dojo_details');
    }

    public function detailsTTAction() {
        $this->__detailsTT = $this->callViewHelper('dojo_detailsTT');
    }

    public function tooltipsAction() {
        $this->__toolTips = $this->callViewHelper('dojo_toolTip');
        $this->callViewHelper('styleLoader','djwidth', '.dijitTooltipContainer {max-width:400px;background-color:#FFD}');
    }

}
