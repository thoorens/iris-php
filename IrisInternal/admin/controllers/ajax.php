<?php

namespace IrisInternal\admin\controllers;

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
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class ajax extends \Iris\MVC\_AjaxController {


    public function security() {
    }

    /**
     * Ajax version of the tool bar
     * 
     * @param boolean $menu If true, the action menu is displayed
     * @param string $color Background color for toolbar
     */
    public function toolbarAction($menu = \FALSE, $color ='#145'){
        $time = \Iris\Users\Session::GetInstance()->getValue('PreviousTime', 0.0);
        // the code is shared by the non ajax version (see Iris\controllers\helpers)
        $this->sharedToolbar($time,$menu, $color, \TRUE);
        $this->__modeIcon = $this->callViewHelper('image','/!documents/file/resource/images/icons/ajax.png',
                'ajax symbol','Toolbar managed by Ajax');
        $this->_renderScript('islToolbar_toolbar');
    }

}
