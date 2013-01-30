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
 * 
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class AjaxGet extends _DojoHelper {

    
    public function help($url, $target, $trigger = \NULL, $type = \Dojo\Engine\Bubble::TEXT) {
        $bubble = \Dojo\Engine\Bubble::GetBubble('ajax_get');
        $bubble->addModule('dojo/request', 'request')
                ->addModule('dojo/dom', 'dom')
                ->addModule('dojo/dom-construct', 'domConst')
                ->addModule('dojo/on', 'on')
                ->addSpecialModule($type)
                ->addModule('dojo/domReady!');
        if (is_null($trigger)) {
            $bubble->defFonction(<<<JS1
{request("$url").then(function(text){
      domConst.place(text, "$target");
    });
}   
JS1
                    );
        }
        else {
            $bubble->defFonction(<<<JS
{on(dom.byId("$trigger"), "click", function(){
    request("$url").then(function(text){
      domConst.place(text, "$target");
    });
  });
}   
JS
            );
        }
    }

}

?>
