<?php

namespace modules\dojo\controllers;

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of links
 * 
 * @author jacques
 * @license not defined
 */
class links extends _dojo {

    protected function _init() {
        $this->setViewScriptName('links');
        $this->_setLayout('simple');
    }

       
    public function link1Action() {
        $this->_makeLinksTo(2);
    }

    public function link2Action() {
        $this->_makeLinksTo(1);
    }
    
    /**
     * 
     * @param int $pageNumber
     */
    private function _makeLinksTo($pageNumber, $baseURL = 'link') {
        $this->__link_label = $label = "Link to page $pageNumber";
        $this->__link_URL = $url = "/dojo/links/" . $baseURL . $pageNumber;
        $this->__link_title = $title = "This is a link to page $pageNumber";
        $this->__image = "button_p$pageNumber.png";
        $this->__icon = "icon_page$pageNumber.png";
        $this->__internalIcon = "/!documents/file/images/wbicons/WBIco_$pageNumber.png";
        $this->__link_array = [$label, $url, $title];
        // A link to main page will not be displayed
        $this->__prohibited_Link = ['An prohibited link','/main/index/end','This link will not be displayed'];
        $this->__warning = '';
    }
    
}
