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

    protected function _init() {
        $this->setViewScriptName('all');
    }

   
    
    public function link1Action() {
        $this->__(\NULL, [
            'link_message' => 'Link to page 2',
            'link_URL' => '/helpers/links/link2',
            'link_title' => 'This is a link to page 2',
            'image' => 'button_p2.png',
            'icon' => 'icon_page2.png',
            'link_array' => ['Link to page 2', '/helpers/links/link2', 'This is a link to page 2']
                ]
        );
    }

    public function link2Action() {
        $this->__(\NULL, [
            'link_message' => 'Link to page 1',
            'link_URL' => '/helpers/links/link1',
            'link_title' => 'This is a link to page 1',
            'image' => 'button_p1.png',
            'icon' => 'icon_page1.png',
            'link_array' => ['Link to page 1', '/helpers/links/link1', 'This is a link to page 1']
        ]);
    }
    
    

    public function imagesAction() {
        $this->callViewHelper('styleLoader', 'explanations','td pre{font-size:0.8em}');
        $this->setViewScriptName(\NULL);
    }
}
