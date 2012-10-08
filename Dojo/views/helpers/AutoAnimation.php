<?php

namespace Dojo\views\helpers;

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
 */

/**
 * This helper will provides mechanisms to display or modify a text 
 * using Dojo. For now, only fadein is implemented.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class AutoAnimation extends \Iris\views\helpers\_ViewHelper {

    public function help() {
        return $this;
        
    }
    
    public function fadeIn($objectId,$delay=0,$duration=5000,$controller=array()){
        if(count($controller)==0){
            $cont = 'null';
            $event = 'onload';
            $dec = '';
        }else{
            list($cont,$event) = $controller;
            $dec = "var $cont = dojo.byId(\"$cont\");";
        }
        return <<< SCRIPT
        <script type="text/javascript">
dojo.style("$objectId", "opacity", "0");           
dojo.addOnLoad(function(){
$dec
dojo.connect($cont,"$event",
function (evt){
var animated = dojo.fadeIn({node:$objectId,duration:$duration,delay:$delay});
    animated.play();
    });
});
</script>
SCRIPT;
    }

}

?>
