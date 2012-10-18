<?php

namespace IrisInternal\admin\controllers;

use \Iris\Engine\Memory as Memory;

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
class admin_col1 extends \IrisInternal\main\controllers\_SecureIslet {

    use \Iris\Translation\tSystemTranslatable;
    
    public function indexAction() {

        $this->__image1 ='Iris.png';
        $this->__commentaire1 = 'Jacques THOORENS';
        $functions[] = $this->_('Role tester|/!admin/roles/switch|Switch to a dummy user having a specific role',TRUE);
        $functions[] = $this->_('ACL management|/!admin/roles/acl|Display and edit all Access Control Lists',TRUE);
        $functions[] = $this->_('Structure management||Manage modules, controllers and action',TRUE);
        $functions[] = $this->_('Function 4||Future enhancement',TRUE);
        $functions[] = $this->_('Function 5||Future enhancement',TRUE);
        foreach($functions as $function){
            $buttons[] = explode('|',$function);
        }
        $this->__buttons = $buttons;
        $this->__test = 'Yes';
        //$this->render('pub');
    }

    

}
