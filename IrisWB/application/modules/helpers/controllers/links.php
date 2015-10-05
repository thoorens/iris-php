<?php

namespace modules\helpers\controllers;

/**
 *
 * Created for IRIS-PHP 0.9 - beta
 * Description of links
 *
 * @author jacques
 * @license not defined
 */
class links extends _helpers {

    const WARNING = 'Clic on one of the various links to go back to main page.';

    /**
     * Some tests of links, image links and buttons
     */
    public function link1Action() {
        $this->_makeLinksTo(2, "link");
    }

    /**
     * A second page, target of link action to return to this page.
     */
    public function link2Action() {
        $this->_makeLinksTo(1, "link");
    }

    /**
     * Special cases of links and buttons
     */
    public function special1Action() {
        $this->_makeLinksTo(2, "special");
    }

    /**
     * A second page, target of link action to return to this page.
     */
    public function special2Action() {
        $this->_makeLinksTo(1, "special");
    }

    public function iLink1Action(){
        $this->_makeLinksTo(2, "iLink");
    }
    
    public function iLink2Action(){
        $this->_makeLinksTo(1, "iLink");
    }
    
    
    public function iButton1Action(){
        $this->_makeLinksTo(2, "iButton");
    }
    
    public function iButton2Action(){
        $this->_makeLinksTo(1, "iButton");
    }
    /**
     * Images with tooltip and other struffs
     */
    public function imagesAction() {
        $this->callViewHelper('styleLoader', 'explanations', 'td pre{font-size:0.8em}');
    }

    /**
     * Images with tooltip and other struffs
     */
    public function images3Action() {
        $this->callViewHelper('styleLoader', 'explanations', 'td pre{font-size:0.8em}');
    }

    /**
     * 
     */
    public function incomplete1Action() {
        $this->_makeLinksTo(2, 'incomplete');
    }

    /**
     * 
     */
    public function incomplete2Action() {
        $this->_makeLinksTo(1, 'incomplete');
    }

    /**
     * 
     * @param int $pageNumber
     */
    private function _makeLinksTo($pageNumber, $baseURL = 'link') {
        $this->setViewScriptName($baseURL);
        if($pageNumber === 1){
            $this->_specialScreen(self::WARNING);
        }
        $this->__link_label = $label = "Link to page $pageNumber";
        $this->__link_URL = $url = "/helpers/links/" . $baseURL . $pageNumber;
        $this->__link_title = $title = "This is a link to page $pageNumber";
        $this->__image = "button_p$pageNumber.png";
        $this->__icon = "icon_page$pageNumber.png";
        $this->__internalIcon = "/!documents/file/images/wbicons/WBIco_$pageNumber.png";
        $this->__link_array = [$label, $url, $title];
        // A link to main page will not be displayed
        $this->__prohibited_Link = ['An prohibited link','/main/index/end','This link will not be displayed'];
    }

//    private function lisser($args) {
//        $data = [];
//        $flat_array = [];
//
//        foreach (new RecursiveIteratorIterator(new \RecursiveArrayIterator($args))
//
//        as $k => $v) {
//
//            $flat_array[$k] = $v;
//        }
//        return $flat_array;
//
//        foreach ($args as $arg) {
//            if (is_array($arg)) {
//                $data = array_merge($data, $this->lisser($arg));
//            }
//            else {
//                $data[] = $arg;
//            }
//        }
//        return $data;
//    }

    /**
     * Links to internal and external resources, through ILO library
     */
    public function internalAction() {
        
    }

}
