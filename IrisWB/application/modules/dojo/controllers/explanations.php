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

    protected function _init() {
        $this->__data = [
    '2011'=>'2011 is the year <i>Iris-PHP</i> has been started',
    '1953'=>'The developper of the framework is born in 1953',
    '5.4'=>'5.4 is the <b>minimum version</b> required by Iris-PHP',
    '4'=>'There are 4 ways to specify a <i>layout</i> in Iris-PHP',
];
    }

    
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
