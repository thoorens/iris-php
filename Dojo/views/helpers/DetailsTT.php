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
 */

 /**
 * Similar to Details but using a tooltop instead of a hidden zone.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class DetailsTT extends Details {

    protected static $_NotAfterHead = \TRUE;
    private $_toolTip;

    protected function _init() {
        parent::_init();
        $this->_toolTip = new ToolTip();
    }

    /**
     *
     * @return ToolTip
     */
    public function getToolTip(){
        return $this->_toolTip;
    }
    
    public function tagAndToolTip($title, $details){
        $id = "tt_".$this->_num++;
        $zone = sprintf($this->_toggler,$id,$title)."\n";
        return $zone.$this->_toolTip->render($id,$details);
    }
}

