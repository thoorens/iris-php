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
class ModeIcon extends _ViewHelper {

    public function help() {
        if(\ILO\views\helpers\AdminToolBar::$AjaxMode){
        $icon = $this->callViewHelper('image', '/!documents/file/resource/images/icons/ajax.png', 'ajax symbol', $this->_('Toolbar managed by Ajax'));
        }
        else{
            $icon = '';
        }
        return $icon;
    }

}
