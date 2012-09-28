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
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */

/**
 * Provides a way to mask a part of a text (with a default "details" link
 * to unmask or remask the masked part.
 *
 */
class Mask extends _DojoHelper {

    protected static $_Singleton = TRUE;
    
    private $_label;

    public function help() {
        return $this;
    }

    protected function _init() {
        $this->_manager->addRequisite('dijit.form.ToggleButton');
        $this->_requiredDone = TRUE;
    }

    public function buttonMask($text, $label=NULL) {
        if (is_null($label)) {
            $label = $text."_details";
        }
        $this->_label = $label;
        return <<<HTML
            <button dojoType="dijit.form.ToggleButton" iconClass="dijitCheckBoxIcon">
                 <script type="dojo/method" event="onChange" args="newValue">
                 if(newValue){
                     dojo.removeClass("$label", "dojoIrisMask");
                 }
                 else {
                      dojo.addClass("$label", "dojoIrisMask");
                      }
                </script>
                    $text
                </button>
HTML;
    }
    
    public function attributes(){
        $label = $this->_label;
        return "class=\"dojoIrisMask\" id=\"$label\"";
    }

}