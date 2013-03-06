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
 * A project of link to FaceBook
 * 
 */
class Facebook extends _ViewHelper {

    public function help($command=NULL) {
        if ($command == NULL) {
            return $this->_render();
        }
        else{
            return $this->_js();
        }
    }

    private function _js() {
        $text = <<< JS
        (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
JS;
        $this->callViewHelper('javascriptLoader','Facebook',$text);
    }

    private function _render() {
        return <<< RENDER
   <div class="fb-like" data-href="http://www.facebook.com/pages/IrisPHP/373632602651651" 
       data-send="true" data-width="450" data-show-faces="true"></div>
RENDER;
        
    }
}

    