<?php

namespace Iris\views\helpers;

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
 * @copyright 2012 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * 
 */

/**
 * A counter, with interface to an objet. It counts down to 0 from a max value
 * and send a message through the space.
 * 
 */
class CountDown extends _ViewHelper {

    public function help($messageName, $max, $objectName = \NULL, $suffix = '',$args=[]){
        if(is_null($messageName)){
            return $this;
        }
        else{
            return $this->_render($messageName, $max, $objectName, $suffix, $args);
        }
    }
    
    private function _render($messageName, $max, $objectName, $suffix,$args) {
        if(count($args)){
            $params = "'".implode("','",$args)."'";
            print_r($params);
        }
        else{
            $params = '';
        }
        $this->javascriptLoader("countdown$suffix", <<<JS
    function countdown$suffix(seconds){
        require(['dojo','dojo/topic'],function(dojo,topic){
          counter = dojo.byId('$objectName');
          seconds -= 1;
          counter.innerHTML = seconds;
          if(seconds == 0){
              topic.publish('$messageName', $params);
              return;
          }
          setTimeout(function(){countdown$suffix(seconds)},1000); 
        })
    }
JS
                );
        $this->javascriptStarter("cd$suffix", "countdown$suffix($max)");

    }

}

