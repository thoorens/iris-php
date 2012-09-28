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
 * Provides a way to display advanced tool tips with HTML tags inside.
 *
 */
class ToolTip extends _DojoHelper {

    protected static $_Singleton = TRUE;
    protected $_connectId;
    protected $_position='above';
    protected $_showDelay = 400;
    protected $_label;
    private $_class=NULL;

    public function help() {
        return $this;
    }

    protected function _init() {
        $this->_manager->addRequisite("dijit/Tooltip");
    }

    public function render($connectId=NULL,$text = NULL) {
        if(is_null($connectId)){
            $connectId = $this->_connectId;
        }
        if (is_null($text)) {
            $text = $this->_label;
        }
        if(is_null($this->_class)){
            $class ='';
        }
        else{
            $className = $this->_class;
            $class = " class=\"$className\"";
        }
        return <<< JS
        <div data-dojo-type="dijit/Tooltip" $class data-dojo-props="connectId:'$connectId',position:['$this->_position'], showDelay:'$this->_showDelay'">
        $text
        </div >
JS;
    }

    public function setRequiredDone($requiredDone) {
        $this->_requiredDone = $requiredDone;
    }

    public function setConnectId($connectId) {
        $this->_connectId = $connectId;
        return $this;
    }

    public function setPosition($position) {
        $this->_position = $position;
        return $this;
    }

    public function setShowDelay($showDelay) {
        $this->_showDelay = $showDelay;
        return $this;
    }

    public function setLabel($label) {
        $this->_label = $label;
        return $this;
    }

    public function setClass($class){
        $this->_class = $class;
    }
}

