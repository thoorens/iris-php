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
 * This helper adds a class to <body> so that Dojo could
 * use the good CSS. 
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class AjaxGet16 extends _DojoHelper {

    /**
     * The style of dojo widget may be set in body tag
     * No effect if no Dojo tool is used in the page.
     * 
     * @return string 
     */
    public function help($url, $id, $type) {
        $js = <<<JS
<script>dojo.ready(function(){
  // Look up the node we'll stick the text under.
  var targetNode = dojo.byId("$id");

  // The parameters to pass to xhrGet, the url, how to handle it, and the callbacks.
  var xhrArgs = {
    url: "$url",
    handleAs: "$type",
    load: function(data){
      targetNode.innerHTML = data;
    },
    error: function(error){
      targetNode.innerHTML = "An unexpected error occurred: " + error;
    }
  }

  // Call the asynchronous xhrGet
  var deferred = dojo.xhrGet(xhrArgs);
});</script>
   
JS;
        return $js;
        //$this->_manager->
    }

}

?>
