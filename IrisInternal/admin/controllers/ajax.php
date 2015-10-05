<?php
namespace IrisInternal\admin\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An internal to display the admin toolbar by ajax mean
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
        $this->__color=$color;
        $this->__time = $time;
        $this->__MENU = $menu;
        $this->_renderScript('islToolbar_toolbar');
    }

}
