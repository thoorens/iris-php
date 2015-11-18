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

    /**
     * Setter for the position  of the container controller (not to be
     * used in tabcontainer, where it repeats the tabs)
     * 
     * @param type $position
     * @return \Dojo\views\helpers\TabContainer
     */
    public function setPosition($position) {
        $this->_position = $position;
        return $this;
    }



}
