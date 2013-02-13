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
        $this->setViewScriptName('dojoall');
        $this->_setLayout('application');
    }

       
    public function link1Action() {
        $this->__(\NULL, [
            'link_message' => 'Dojo Link to page 2',
            'link_URL' => '/dojo/links/link2',
            'link_title' => 'This is a link to page 2',
            'image' => 'button_p2.png',
            'icon' => 'icon_page2.png',
            'link_array' => ['Dojo Link to page 2', '/dojo/links/link2', 'This is a link to page 2']
                ]
        );
    }

    public function link2Action() {
        $this->__(\NULL, [
            'link_message' => 'Dojo Link to page 1',
            'link_URL' => '/dojo/links/link1',
            'link_title' => 'This is a link to page 1',
            'image' => 'button_p1.png',
            'icon' => 'icon_page1.png',
            'link_array' => ['Dojo Link to page 1', '/dojo/links/link1', 'This is a link to page 1']
        ]);
    }
    
    
}
