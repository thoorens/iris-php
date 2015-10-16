<?php
namespace ILO\views\helpers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 * 
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Display user information
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
class DisplayUser extends \Iris\views\helpers\_ViewHelper {

    const LABEL = 1;
    const TEXT = 2; 
    const NAMETOOLTIP = 4;
    const GROUPTOOLTIP = 8;
    const TBMODE = 9; 

    protected static $_Singleton = \TRUE;
    
    private $_userName;
    private $_groupName;
    
    public function help($mode = self::TBMODE) {
        // name and group
        $userLabel = $this->_('User');
        $groupLabel = $this->_('Group');

        if ($mode & self::NAMETOOLTIP and $mode & self::GROUPTOOLTIP) {
            $tooltip = "$this->_userName ($this->_groupName)";
            $nameAndGroup = '';
            $label = $userLabel;
        }
        elseif ($mode & self::NAMETOOLTIP) {
            $tooltip = "$userLabel $this->_userName";
            $nameAndGroup = "$this->_groupName";
            $label = $groupLabel . ': ';
        }
        elseif ($mode & self::GROUPTOOLTIP) {
            $tooltip = "$groupLabel $this->_groupName";
            $nameAndGroup = "$this->_userName";
            $label = $userLabel . ': ';
        }
        else {
            $tooltip = ''; //"$groupLabel $groupName";
            $nameAndGroup = "<b>$this->_userName</b> (<i>$this->_groupName</i>)";
            $label = '';
        }
        // label
        if ($mode & self::LABEL) {
            if ($mode & self::TEXT) {
                $label = $label;
            }
            else {
                $label = $this->callViewHelper('image', '/!documents/file/images/icons/user2.png', 'user symbol', $tooltip);
            }
        }
        else {
            $label = '';
        }


        return sprintf('<span title="%s">%s %s</span>', $tooltip, $label, $nameAndGroup);
    }

    protected function _init() {
        \Iris\Users\Session::GetInstance();
        $identity = \Iris\Users\Identity::GetInstance();
        $this->_userName = $identity->getName();
        $this->_groupName = $identity->getRole();
    }

}

