<?php
namespace Dojo\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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

    protected static $_Type = 'BorderContainer';
    
    /**
     *
     * @var type 
     */
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
    
    /**
     * 
     */
    protected function _init() {
        $this->_layoutMode = self::HEADLINE;
    }

    /**
     * 
     * @return type
     */
    public function getLayoutMode() {
        return $this->_layoutMode;
    }

    /**
     * 
     * @param type $layoutMode
     * @return \Dojo\views\helpers\BorderContainer
     */
    public function setLayoutMode($layoutMode) {
        $this->_layoutMode = $layoutMode;
        return $this;
    }

    /**
     * 
     * @param type $name
     * @param type $label
     * @return \Dojo\views\helpers\BorderContainer
     */
    public function addItem($name, $label) {
        $region = $this->_convertRegion($name);
        parent::addItem($region , $label);
        /* @var $newItem \Dojo\Engine\Item */
        $newItem = $this->_items[$region];
        $newItem->addSpecialProp("region:'$region'");
        return $this;
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function getItem($name) {
        return parent::getItem($this->_convertRegion($name));
    }

    /**
     * 
     * @param type $name
     * @return type
     */
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
        $mode = $this->_layoutMode == self::HEADLINE ? 'headline' : 'sidebar';
        $this->_specials[] = "design:'$mode'";
        //$this->_specials[] = "gutters:true";
        //$this->_specials[] = "liveSplitters:true";
        return parent::_specialAttributes();
    }

}
