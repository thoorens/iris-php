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
 * This helper permits to use border containers of Dojo. If javascript
 * is not available on the client, it simulate the tab with buttons and interaction
 * with the server. Another option is to display all the items, with <h5> title in front
 * of them.
 *

 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class BorderContainer extends _Container {
    // layout mode

    const HEADLINE = 1;
    const SIDEBAR = 2;

    // region
    const TOP = 1;
    const RIGHT = 2;
    const BOTTOM = 3;
    const LEFT = 4;
    const CENTER = 5;
    // As left and right except in right to left environment
    const LEADING = 40;
    const TRAILING = 20;

    private $_regions = [
        self::TOP => 'top',
        self::RIGHT => 'right',
        self::BOTTOM => 'bottom',
        self::LEFT => 'left',
        self::CENTER => 'center',
        self::LEADING => 'leading',
        self::TRAILING => 'trailing',
    ];
    private $_layoutMode;
    protected static $_Type = 'BorderContainer';

    protected function _init() {
        $this->_layoutMode = self::HEADLINE;
    }

    public function getLayoutMode() {
        return $this->_layoutMode;
    }

    public function setLayoutMode($layoutMode) {
        $this->_layoutMode = $layoutMode;
        return $this;
    }

    public function addItem($name, $label) {
        $region = $this->_convertRegion($name);
        parent::addItem($region , $label);
        /* @var $newItem \Dojo\Engine\Item */
        $newItem = $this->_items[$region];
        $newItem->addSpecialProp("region:'$region'");
        return $this;
    }

    public function getItem($name) {
        return parent::getItem($this->_convertRegion($name));
    }

    private function _convertRegion($name) {
        if (is_numeric($name)) {
            $region = $this->_regions[$name];
        }
        else {
            $region = strtolower($name);
        }
        return $region;
    }

    
    protected function _specialAttributes() {
        $mode = $this->_layoutMode = self::HEADLINE ? 'headline' : 'sidebar';
        $this->_specials[] = "design:'$mode'";
        //$this->_specials[] = "gutters:true";
        //$this->_specials[] = "liveSplitters:true";
        return parent::_specialAttributes();
    }

}
