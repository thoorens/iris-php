<?php

namespace ILO\views\helpers;

use \Iris\views\helpers\_ViewHelper;

/*
 * This file is part of IRIS-PHP.
 *
 * IRIS-PHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your li) any later version.
 *
 * IRIS-PHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with IRIS-PHP.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @copyright 201-2013 Jacques THOORENS
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */

/**
 * Display user information
 *
 */
class DisplayUser extends _ViewHelper {

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
                $label = $this->callViewHelper('image', '/!documents/file/resource/images/icons/user2.png', 'user symbol', $tooltip);
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

