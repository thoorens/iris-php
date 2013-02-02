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
 * Description of Dojo
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class Provider extends \Iris\Ajax\_AjaxProvider {

    public function get($url, $target, $type = \NULL) {
        $bubble = $this->getBubble('ajax_get', $type);
        $bubble->defFonction(<<<JS1
{request("$url").then(function(text){
      domConst.place(text, "$target");
    });
}   
JS1
        );
    }

    public function onEvent($event, $object, $url, $target, $type = \NULL) {
        $bubble = $this->getBubble("ajax_$event" . "_$object", $type);
        $bubble->defFonction(<<<JS
{on(dom.byId("$object"), "$event", function(){
    request("$url").then(function(text){
      domConst.place(text, "$target");
    });
  });
}   
JS
        );
    }

    public function onClick($object, $url, $target, $type = \NULL) {
        $this->onEvent('click', $object, $url, $target, $type);
    }

    public function onTime($delay, $url, $target, $type = \NULL) {
        $bubble = $this->getBubble("ajax_delay" . $type);
        $bubble->defFonction(<<<JS
{setTimeout(function(){
    request("$url").then(function(text){
      domConst.place(text, "$target");
    });}, $delay);
}   
JS
        );
    }

    public function onMessage($messageName, $url, $target, $type = \NULL) {
        $bubble = $this->getBubble("msg$messageName" . $type);
        $bubble->addModule('dojo/topic','topic');
        $bubble->defFonction(<<<JS
{topic.subscribe('$messageName',function(a,b){
    request("$url/"+a+'/'+b).then(function(text){
      domConst.place(text, "$target");
    });});
}   
JS
                );
    }

    private function getBubble($bubbleName, $type = \Dojo\Engine\Bubble::TEXT) {
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

