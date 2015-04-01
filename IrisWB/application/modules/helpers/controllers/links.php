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
        $this->setViewScriptName('all');
        $this->_toPageX(2);
    }

    /**
     * 
     * @param int $pageNumber
     */
    private function _toPageX($pageNumber, $baseURL = 'link') {

        $this->__link_label = "Link to page $pageNumber";
        $this->__link_URL = "/helpers/links/$baseURL$pageNumber";
        $this->__link_title = "This is a link to page $pageNumber";
        $this->__image = "button_p$pageNumber.png";
        $this->__icon = "icon_page$pageNumber.png";
        $this->__internalIcon = "/!documents/file/images/wbicons/WBIco_$pageNumber.png";
        $this->__link_array = ["Link to page $pageNumber", "/helpers/links/$baseURL$pageNumber", "This is a link to page $pageNumber"];
    }

    /**
     * A second page, target of link action to return to this page.
     */
    public function link2Action() {
        $this->_specialScreen(self::WARNING);
        $this->setViewScriptName('all');
        $this->_toPageX(1);
    }

    public function lisser($args) {
        $data = [];
        $flat_array = [];

        foreach (new RecursiveIteratorIterator(new \RecursiveArrayIterator($args))

        as $k => $v) {

            $flat_array[$k] = $v;
        }
        return $flat_array;

        foreach ($args as $arg) {
            if (is_array($arg)) {
                $data = array_merge($data, $this->lisser($arg));
            }
            else {
                $data[] = $arg;
            }
        }
        return $data;
    }

    /**
     * Images with tooltip and other struffs
     */
    public function imagesAction() {
        $this->callViewHelper('styleLoader', 'explanations', 'td pre{font-size:0.8em}');
    }

    public function incomplete1Action() {
        $this->_toPageX(2, 'incomplete');
        $this->setViewScriptName('incomplete');
    }

    public function incomplete2Action() {
        $this->_specialScreen(self::WARNING);
        $this->_toPageX(1, 'incomplete');
        $this->setViewScriptName('incomplete');
    }

    /**
     * Links to internal and external resources, through ILO library
     */
    public function internalAction() {
        
    }

}
