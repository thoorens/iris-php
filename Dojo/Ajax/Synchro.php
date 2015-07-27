<?php

namespace Dojo\Ajax;

\defined('CRLF') or \define('CRLF', "\n");

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
class Synchro extends \Iris\Ajax\_Synchro {

    protected static $_Instance = \NULL;

    /**
     * Creates a scheduler, optionally controlled by another message transmitter
     * 
     * @param string $messageName The message name
     * @param int $interval
     * @param int $max Duration of the sender (by default 3600 sec)
     * @param type $externalSignal Optional signal to control the sender
     */
    public function send($messageName, $max = self::MINUTE, $externalSignal = \NULL, $context = \NULL) {
        $this->_max = $max;
        $running = $this->_autostart ? 1 : 0;
        $granularity = $this->_granularity; 
        $senderName = "snd_$messageName";
        $bubble = $this->_getBubble($senderName);
        $bubble->addModule('dojo/request','request');
        $bubble->addModule('dojo/request/script','script');
        $bubble->addModule('dojo/dom-construct','domConst');
        $context = $this->_createContext();
        $noURL = self::NO_URL;
        $controllerCode = $this->_getSignalControlCode($externalSignal);
        $bubble->defFunction(<<<JS
   
   ms = 0;
   running = $running;
   old = '';
   max = $max;
   
   $context 
       
   function gotoUrl(url){
        if(url!='$noURL') 
            window.location.href=url;  
   }
                
   function restart(){
       gotoUrl(currentData.URL);
   }
                
   function previous(){
       gotoUrl(previousData.URL); 
   }
                
   function next(){
       gotoUrl(nextData.URL);
   }
   
                
   function innerloop(){ 
$controllerCode   
        if(running)topic.publish('$messageName',ms,max);
        if(ms<max){setTimeout(function(signal){ innerloop()},$granularity);}
        if(ms>=max){next()}    
        if(running){
            ms+=$granularity; 
        }
}
// Start
innerloop();

JS
        );
    }

    protected function _display($id, $messageName, $targetId, $mode, $htmlCode) {
        $mode = $mode == self::REPLACE ? 'replace' : 'last';
        $refreshingInterval = $this->_refreshingInterval;
        $treatment = "if(time%$refreshingInterval==0)domConst.place($htmlCode, '$targetId', '$mode');";
        $req = ['domConst' => 'dojo/dom-construct'];
        $this->_genericReceiver($id, $messageName, $treatment, $req);
    }

    protected function _genericReceiver($id, $messageName, $treatment, $requisites) {
        $receiverName = "rec_$messageName" . "_$id";
        $bubble = $this->_getBubble($receiverName);
        foreach ($requisites as $var => $module) {
            $bubble->addModule($module, $var);
        }
        $bubble->defFunction(<<<JS
topic.subscribe('$messageName',function(time,max){
      $treatment
    ;});
      
JS
        );
    }

    protected function _createContext(){
        list($previous, $current, $next) = $this->_context;
        return <<<CONTEXT
currentData ={
    URL : '$current',
}
previousData ={
    URL : '$previous',
}
nextData={
    URL : '$next',
}    
CONTEXT;
    }
    
    
    /**
     * Creates or retrieves a bubble by its name and add it
     * the standard modules (dom, domReady and topic)
     * 
     * @param string $bubbleName
     * @return type
     */
    private function _getBubble($bubbleName) {
        $bubble = \Dojo\Engine\Bubble::getBubble($bubbleName);
        $bubble->addModule('dojo/dom', 'dom')
                ->addModule('dojo/topic', 'topic')
                ->addModule('dojo/domReady!');
        return $bubble;
    }

    /**
     * Gets the controller part of the code: the switch corresponding to 
     * all the possible messages.
     * 
     * @param string $signal
     * @return string
     */
    private function _getSignalControlCode($signal) {
        if (is_null($signal)) {
            return '';
        }
        else {
            return<<< CONT
        topic.subscribe('$signal',function(msg){
            if(msg!=old){
                old = 'none';    
                switch(msg){
                  case 'stop':
                     running = 0;
                     old = msg;
                     break;
                  case 'start':
                     running = 1; 
                     old = msg;
                     break;
                  case 'restart':
                     restart();
                     break;
                  case 'next':
                     next();
                     break;
                  case 'previous':
                     previous();
                     break;
                }
            }
        });
CONT;
        }
    }

}

