<?php

namespace Dojo\Ajax;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * A series of counters, wich may be linked to dom object where to display
 * their values and send a message in a bottle.
 * 
 * Project IRIS-PHP
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.thoorens.net
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version :$Id:
 */
class EventManager extends \Iris\Ajax\_EventManager {

    /**
     *
     * @var string[]
     */
    private $_modules = [];
    
    /**
     * 
     * @param string[] $modules
     * 
     * @return \Dojo\Ajax\EventManager
     */
    public function addModules($modules){
        $this->_modules = $modules;
        return $this;
    }
    
    public function onClick($sender, $functionCode, $modules = [])  {
        $this->_onEvent('click' ,$sender, $functionCode, $modules);
    }

    
    
    private function _onEvent($eventName, $sender, $functionCode, $modules = []) {
        $bubble = \Dojo\Engine\Bubble::getBubble($sender."_$eventName");
        $bubble->addModule('dojo/dom','dom');
        //$bubble->addModule('dojo/connect','connect');
        $bubble->addModule('dojo/on','on');
        $bubble->addModule('dojo/domReady!');
        foreach(array_merge($modules,$this->_modules) as $var=>$module){
            $bubble->addModule($module, $var);
        }
        $bubble->defFunction(<<<JS

on(dom.byId("$sender"), "$eventName", function(){
    $functionCode
  });

JS
);
//iris_debug($bubble);
    }
}