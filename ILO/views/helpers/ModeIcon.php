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
 * Displays an ajax icon if this mode is enabled by the Settings
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $
 */
class ModeIcon extends \Iris\views\helpers\_ViewHelper {

    public function help() {
        if (\Iris\Sysconfig\Settings::$AdminToolbarAjaxMode) {
            $icon = $this->callViewHelper('image', '/!documents/file/images/icons/ajax.png', 'ajax symbol', $this->_('Toolbar managed by Ajax'));
        }
        else {
            $icon = '';
        }
        return $icon;
    }

}

