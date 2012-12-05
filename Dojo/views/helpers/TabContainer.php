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
 * This helper permits to use Tabs containers of Dojo. If javascript
 * is not available on the client, it simulate the tab with buttons and interaction
 * with the server. Another option is to display all the items, with <h5> title in front
 * of them.
 *

 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class TabContainer extends _Container{

    const LEFT = 1;
    const RIGHT = 2;
    const BOTTOM = 3;
    const TOP = 4;
    
    protected $_position = 'top';
    
    //@todo : add closable/disabled and flexible height
    protected static $_Type = 'TabContainer';
    
    protected function _specialAttributes() {
        switch($this->_position){
            case self::LEFT :
                $this->_specials[] = "tabPosition:'left-h'";
                break;
            case self::RIGHT :
                $this->_specials[] = "tabPosition:'right-h'";
                break;
            case self::BOTTOM :
                $this->_specials[] = "tabPosition:'bottom'";
                break;
        }

        if(\TRUE){
            //$this->_specials[] = " tabStrip:'true' ";
        }
        return parent::_specialAttributes();
    }

    public function setPosition($position) {
        $this->_position = $position;
        return $this;
    }



}
