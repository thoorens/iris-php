<?php

namespace Dojo\Ajax;

defined('CRLF') or define('CRLF', "\n");

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
class Counter extends \Iris\Ajax\_Counter {

    /**
     * An dojo inner function to create a counter linked to a DOM object
     * 
     * @param int $mode UP or DOWN
     * @param string $messageId The message Id
     * @param int $max the max value to start from
     * @param string $objectName The optional DOM object to serve as display zone
     * @param mixed[] $args The arguments to be send with the message
     */
    protected function _linkedCounter($mode, $messageId, &$max, $objectName, $args) {
        list($operator, $test) = $this->_engine($mode, $max);
        $params = $this->_implodeArgs($args);
        $counterName = $this->_counterName;
        $this->_renderer->callViewHelper('javascriptLoader',$counterName, <<<JS
    function $counterName(seconds){
        require(['dojo','dojo/topic'],function(dojo,topic){
          counter = dojo.byId('$objectName');
          seconds $operator;
          counter.innerHTML = seconds;
          if(seconds $test){
              topic.publish('$messageId'$params);
              return;
          }
          setTimeout(function(){ $counterName(seconds)},1000); 
        })
    }
JS
        );
    }

    /**
     * An dojo inner function to create a counter without link to a DOM object
     * 
     * @param int $mode UP or DOWN
     * @param string $messageId The message Id
     * @param int $max the max value to grow to from 0
     * @param mixed[] $args The arguments to be send with the message
     */
    protected function _unlinkedCounter($mode, $messageId, &$max, $args) {
        list($operator, $test) = $this->_engine($mode, $max);
        $counterName = $this->_counterName;
        $params = $this->_implodeArgs($args);
        $this->_renderer->callViewHelper('javascriptLoader',$counterName, <<<JS
    function $counterName(seconds){
        require(['dojo','dojo/topic'],function(dojo,topic){
          seconds $operator;
          if(seconds $test){
              topic.publish('$messageId' $params);
              return;
          }
          setTimeout(function(){ $counterName(seconds)},1000); 
        })
    }
JS
        );
    }

    

    /**
     * A layout for the arguments (implementation dependant) 
     * 
     * @param mixed[] $args
     * @return string
     */
    protected function _implodeArgs(array $args) {
        $params = implode("','", $args);
        if($params == ''){
            return '';
        }
        return ", '$params'";
    }

}
