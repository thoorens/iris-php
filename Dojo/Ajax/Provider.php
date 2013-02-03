<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Dojo\Ajax;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * An Ajax provider written in Dojo
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Provider extends \Iris\Ajax\_AjaxProvider {

    const BEFORE = 'before';
    const AFTER = 'after';
    const REPLACE = 'replace';
    const ONLY = 'only';
    const FIRST = 'first';
    const LAST = 'last';
    
    
    
    
    /**
     * Direct get request
     * 
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function get($url, $target, $type = \NULL) {
        $place = $this->_placeMode;
        $bubble = $this->_getStandardBubble('ajax_get', $type);
        $bubble->defFonction(<<<JS1
{request("$url").then(function(text){
      domConst.place(text, "$target", '$place');
    });
}   
JS1
        );
    }

    /**
     * The request is made when an event is fired by an objetc provider
     * 
     * @param string $event The event name
     * @param string $object The object provider name
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function onEvent($event, $object, $url, $target, $type = \NULL) {
        $place = $this->_placeMode;
        $bubble = $this->_getStandardBubble("ajax_$event" . "_$object", $type);
        $bubble->defFonction(<<<JS
{on(dom.byId("$object"), "$event", function(){
    request("$url").then(function(text){
      domConst.place(text, "$target", '$place');
    });
  });
}   
JS
        );
    }

    /**
     * The request is made on clic on an object provider
     * 
     * @param string $object The object clicked
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function onClick($object, $url, $target, $type = \NULL) {
        $this->onEvent('click', $object, $url, $target, $type);
    }

    /**
     * The request is made after a delay
     * 
     * @param int $delay The delay in milliseconds
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function onTime($delay, $url, $target, $type = \NULL) {
        $place = $this->_placeMode;
        $bubble = $this->_getStandardBubble("ajax_delay" . $type);
        $bubble->defFonction(<<<JS
{setTimeout(function(){
    request("$url").then(function(text){
      domConst.place(text, "$target", '$place');
    });}, $delay);
}   
JS
        );
    }

    /**
     * The request is made upon reception of a message (through the topic
     * publish and subscribe mechanism). Two parameters sent with the message
     * are taken into account.
     * 
     * @param string $messageName The name of the message
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function onMessage($messageName, $url, $target, $type = \NULL) {
        $place = $this->_placeMode;
        $bubble = $this->_getStandardBubble("msg$messageName" . $type);
        $bubble->addModule('dojo/topic','topic');
        $bubble->defFonction(<<<JS
{topic.subscribe('$messageName',function(a,b){
    request("$url/"+a+'/'+b).then(function(text){
      domConst.place(text, "$target", '$place');
    });});
}   
JS
                );
    }

    /**
     * Prepares a dojo bubble (by retrieving or creating it) and places some standard
     * modules in it. If necessary, a special module is inserted (according to
     * the request type)
     * 
     * @param string $bubbleName
     * @param type $type
     * @return type
     */
    private function _getStandardBubble($bubbleName, $type = \Dojo\Engine\Bubble::TEXT) {
        $bubble = \Dojo\Engine\Bubble::GetBubble($bubbleName);
        $bubble->addModule('dojo/request', 'request')
                ->addModule('dojo/dom', 'dom')
                ->addModule('dojo/dom-construct', 'domConst')
                ->addModule('dojo/on', 'on')
                ->addSpecialModule($type)
                ->addModule('dojo/domReady!');
        return $bubble;
    }

}

