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

    protected $_functions = 0;
    protected $_url = 0;
    
    protected $_switch = 72; // self::BSTART + self::BSTOP;
    
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
        $bubble->addModule('dojo/request', 'request');
        $bubble->addModule('dojo/request/script', 'script');
        $bubble->addModule('dojo/dom-construct', 'domConst');
        $noURL = self::NO_URL;
        $controllerCode = $this->_getSignalControlCode($externalSignal);
        $internal = $this->_internal();
        $bubble->defFunction(<<<JS
   ms = 0;
   running = $running;
   old = '';
   max = $max;

{$internal['url']['current']} {$internal['url']['next']} {$internal['url']['previous']}    
    
{$internal['function']['gotourl']} {$internal['function']['restart']} {$internal['function']['next']} {$internal['function']['previous']}
                
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
        $switch = $this->_internal()['switch'];
        if (is_null($signal)) {
            return '';
        }
        else {
            return<<< CONT
        topic.subscribe('$signal',function(msg){
            if(msg!=old){
                old = 'none';    
                switch(msg){
{$switch['stop']} {$switch['start']} {$switch['restart']} {$switch['next']} {$switch['previous']}
                }
            }
        });
CONT;
        }
    }

    /**
     * Returns and creates if necessary, the pieces of JS code
     * 
     * @staticvar string[] $values
     * @return string[]
     */
    protected function _internal() {
        static $values = \NULL;
        if ($values === \NULL) {
            $code = Code::GetInstance();
            $values['switch'] = $code->getSynchroSwitch($this->_switch);
            //$values['switch'] = $this->_internalSwitch();
            $values['function'] = $this->_internalFunctions();
            $values['url'] = $this->_internalUrl();
        }
        return $values;
    }

    /**
     * Creates the instructions to be inserted in the innerLoop function
     * 
     * @return string[]
     */
    protected function _internalSwitch() {
        $values['stop'] = $this->_switch & self::BSTOP ? <<<END_OF_CASE
                  case 'stop':
                     running = 0;
                     old = msg;
                     break;
END_OF_CASE
                :'';
        $values['start'] = $this->_switch & self::BSTART ? <<<END_OF_CASE
                  case 'start':
                     running = 1; 
                     old = msg;
                     break;
END_OF_CASE
                :'';
        $values['restart'] = $this->_switch & self::BRESTART ? <<<END_OF_CASE
                  case 'restart':
                     restart();
                     break;
END_OF_CASE
                :'';
        $values['next'] = $this->_switch & self::BNEXT ? <<<END_OF_CASE
                  case 'next':
                     next();
                     break;
END_OF_CASE
                : '';
        $values['previous'] = $this->_switch & self::BPREVIOUS ? <<<END_OF_CASE
                  case 'previous':
                     previous();
                     break;
END_OF_CASE
                : '';
        return $values;
    }

    /**
     * Creates the internal functions corresponding to various tasks
     * 
     * @return string[]
     */
    protected function _internalFunctions(){
        $nourl = self::NO_URL;
        $values['gotourl'] = $this->_switch & self::BGOTO ? <<<END_OF_FUNCTION
   function gotoUrl(url){
        if(url!='$nourl') 
            window.location.href=url;  
   }
END_OF_FUNCTION
                : '';
        $values['restart'] =  $this->_switch & self::BRESTART ? <<<END_OF_FUNCTION
    function restart(){
       gotoUrl(currentData.URL);
   }
END_OF_FUNCTION
                : '';
        $values['next'] = $this->_switch & self::BNEXT ? <<<END_OF_FUNCTION
   function next(){
       gotoUrl(nextData.URL);
   }
END_OF_FUNCTION
                : '';
        $values['previous'] = $this->_switch & self::BPREVIOUS ? <<<END_OF_FUNCTION
   function previous(){
       gotoUrl(previousData.URL); 
   }
END_OF_FUNCTION
                : '';
        return $values;
    }
    
    
    /**
     * Creates the url corresponding to the three main continuation of the code
     * 
     * @return string[]
     */
    protected function _internalUrl(){
        list($previous, $current, $next) = $this->_context;
        $values['next'] = $this->_url & self::BNEXT ? <<<END_OF_URL
nextData={
    URL : '$next',
}
END_OF_URL
                : '';
        $values['previous'] =  $this->_url & self::BPREVIOUS ?<<<END_OF_URL
previousData ={
    URL : '$previous',
}
END_OF_URL
                : '';
        $values['current'] =  $this->_url & self::BCURRENT ? <<<END_OF_URL
currentData ={
    URL : '$current',
}
END_OF_URL
                : '';
        return $values;
    }
}
