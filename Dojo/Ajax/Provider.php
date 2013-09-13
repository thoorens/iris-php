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

    public static function __ClassInit(){
        \Dojo\Manager::GetInstance();
    }
    /**
     * Direct get request
     * 
     * @param string  $url the URL to execute
     * @param string $target idname of the object to modify
     * @param string $type MIME type for the request (text by default)
     */
    public function get($url, $target, $type = \NULL) {
        $place = $this->_placeMode;
        $bubble = $this->_getStandardBubble('ajax_get', $url, $type);
        $action = $this->_getAction($type, $target, $place);
        $handler = $this->_getTypeHandler($type);
        $bubble->defFunction(<<<JS1

        request("$url",
            {handleAs : "$handler"}).then(function(text){
            $action;
        });
 
JS1
        );
    }

    public function getExec($object, $url, $type = \NULL) {

        $bubble = $this->_getStandardBubble('ajax_getExec', $url, $type);
        $bubble->addModule('dojo/json', 'json');
        $script = $this->_debug($bubble);
        $action = $this->_getAction($type, $target, $place);
        $script .= <<< JS1

        on(dom.byId("$object"), "click", function(){
            request("$url", {
                handleAs: "javascript"
            }).then(function(data){
                //...
            });
        });
JS1;
        $bubble->defFunction($script);
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
        $bubble = $this->_getStandardBubble("ajax_$event", $url, $type);
        $script = $this->_debug($bubble);
        $handler = $this->_getTypeHandler($type);
        $action = $this->_getAction($type, $target, $place);
        $script .= <<<JS

        on(dom.byId("$object"), "$event", function(){
            request("$url",
                {handleAs : "$handler"}).then(function(text){
                $action;
            });
        });
   
JS;
        $bubble->defFunction($script);
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
        $bubble = $this->_getStandardBubble("ajax_delay" . $url, $type);
        $script = $this->_debug($bubble);
        $handler = $this->_getTypeHandler($type);
        $action = $this->_getAction($type, $target, $place);
        $script .= <<<JS

        setTimeout(function(){
            request("$url",
                {handleAs : "$handler"}).then(function(text){
                $action;
        });}, $delay);
   
JS;
        $bubble->defFunction($script);
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
        $bubble = $this->_getStandardBubble("msg$messageName", $url . $type);
        $bubble->addModule('dojo/topic', 'topic');
        list($urlParam, $jsParams) = $this->_generateParameters();
        $script = $this->_debug($bubble);
        $handler = $this->_getTypeHandler($type);
        $action = $this->_getAction($type, $target, $place);
        $script .= <<<JS

        topic.subscribe('$messageName',function($jsParams){
            request('$url/'+$urlParam,
                {handleAs : "$handler"}).then(function(text){
                $action;
            });
        });
   
JS;
        $bubble->defFunction($script);
    }

    /**
     * Prepares a dojo bubble (by retrieving or creating it) and places some standard
     * modules in it. If necessary, a special module is inserted (according to
     * the request type)
     * 
     * @param string $bubbleType
     * @param $url 
     * @param string $type
     * @return type
     */
    private function _getStandardBubble($bubbleType, $url, $type = \Dojo\Engine\Bubble::TEXT) {
        $bubbleName = $bubbleType . md5($url);
        $bubble = \Dojo\Engine\Bubble::getBubble($bubbleName);
        $bubble->addModule('dojo/request', 'request')
                ->addModule('dojo/dom', 'dom')
                ->addModule('dojo/dom-construct', 'domConst')
                ->addModule('dojo/on', 'on')
                ->addSpecialModule($type)
                ->addModule('dojo/domReady!');
        return $bubble;
    }

    protected function _getAction($type, $target, $place){
        if(is_null($type) or $type == \Dojo\Engine\Bubble::TEXT) {
            return "domConst.place(text, '$target', '$place')".CRLF;
        }
        else{
            return $this->_action.CRLF;
        }
    }
    
    
    protected function _debug($param) {
        if (is_null($this->_debugDisplayObject)) {
            return '';
        }
        else {
            $output = $this->_debugDisplayObject;
            $param->addModule('dojo/request/notify', 'notify');
            return <<< JS1
   
        notify("start", function(){
                domConst.place("<p>start</p>", "$output");
        });
        notify("send", function(response, cancel){
            // cancel() can be called to prevent the request from
            // being sent
            domConst.place("<p>send: <code>" + JSON.stringify(response) + "</code></p>", "$output");
        });
        notify("load", function(response){
            domConst.place("<p>load: <code>" + JSON.stringify(response) + "</code></p>", "$output");
        });
        notify("error", function(response){
            domConst.place("<p>error: <code>" + JSON.stringify(response) + "</code></p>", "$output");
        });
        notify("done", function(response){
            domConst.place("<p>done: <code>" + JSON.stringify(response) + "</code></p>", "$output");
        });
        notify("stop", function(){
            domConst.place("<p>stop</p>", "$output");
        }); 
JS1;
        }
    }

}

Provider::__ClassInit();