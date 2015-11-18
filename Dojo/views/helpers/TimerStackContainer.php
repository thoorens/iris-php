<?php

namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * This helper permits to use stack containers of Dojo together with a timer. 
 * This helper is not to be used in a non javascript environment
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class TimerStackContainer extends StackContainer {

    protected static $_Type = 'StackContainer';
    
    protected $_firstPause = 0;

    /**
     * 
     * @return TimerStackContainer
     */
    public static function GetInstance() {
        return parent::GetInstance();
    }

    /**
     * Do nothing, since controllers buttons cannot be used with a timer
     *  
     * @param type $position
     * @return \Dojo\views\helpers\TimerStackContainer for fluent interface
     */
    public function setPosition($position) {
        return $this;
    }

    /**
     * 
     * @param type $option
     * @return \Dojo\views\helpers\TimerStackContainer for fluent interface
     */
    public function doLayout($option) {
        $value = $option ? 'true' : 'false';
        $this->_specials[] = sprintf("doLayout:'%s' ", $value);
        return $this;
    }

    public function endMaster() {
        $closingDiv = parent::endMaster();
        $timer = $this->_timer();
        return $closingDiv ."\n". $timer;
    }

    public function putTimes($times, $first = 0){
        if(count($this->_items) == 0){
            throw new \Iris\Exceptions\LibraryException('You must put times after page definitions');
        }
        $this->_firstPause = $first;
        $i = 0;
        foreach($this->_items as $item){
            $item->setTime($times[$i++]);
        }
        return $this;
    }
    
    protected function _timer() {
        $mess = 'TSC_' . $this->_name;
        $items = $this->getItems();
        foreach ($items as $item) {
            $tabArray[] = $item->getName(\TRUE);
            $limitArray[] = $item->getTime();
        }
        $tabArray2 = implode(', ', $tabArray);
        $limitArray2 = implode(', ', $limitArray);
        $pause = $this->_firstPause;
        $max = count($items) - 1;
        return <<<JS
     <script type="text/javascript">
            
        require(["dojo/topic", "dojo/domReady!"], function(topic) {
                ms = 0;
                function innerloop() {
                    topic.publish('$mess', ms);
                    setTimeout(function(signal) {
                       innerloop()
                    }, 1000);
                    ms += 1000;
                }
                // Start
                innerloop();
            });
        
require(["dijit/registry", "dojo/topic", "dojo/domReady!"], function(registry, topic) {
                    var i = 0;
                    var current = 0;
                    var pause = $pause;
                    var tab = [$tabArray2];
                    var limits = [$limitArray2];
                    topic.subscribe('$mess', function(time) {
                    if (time % 1000 == 0){
                        i++;
                        limit = current == 0 ? limits[current] + pause : limits[current];
                        if(i==limit){
                           var s1 = registry.byId('mycontainer');
                           if(typeof(s1)=="undefined"){
                                i--;
                           }     
                           else{     
                                current = current == $max ? 0 : current+1;
                                s1.selectChild(tab[current]);
                                i=0;
                                pause = 0;
                           }
                       }
                    }
                });
            });
        </script>   
JS;
    }

}
