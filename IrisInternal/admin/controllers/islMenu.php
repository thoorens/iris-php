<?php

namespace IrisInternal\admin\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */


/**
 * 
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class islMenu extends \IrisInternal\main\controllers\_SecureIslet { //implements \Iris\Translation\iTranslatable {

    use \Iris\Translation\tSystemTranslatable;
    
    public function indexAction() {
        $this->__image1 ='Iris.png';
        $this->__commentaire1 = 'Jacques THOORENS';
        $functions[] = $this->_('Role tester|/!admin/roles/switch|Switch to a dummy user having a specific role',TRUE);
        $functions[] = $this->_('ACL management|/!admin/roles/acl|Display and edit all Access Control Lists',TRUE);
        $functions[] = $this->_('Password generator|/!admin/password/index|Create hash for password',TRUE);
        $functions[] = $this->_('System parameters|/!admin/parameters/system|Display all system parameters',TRUE);
        $functions[] = $this->_('Error parameters|/!admin/parameters/error|Display all error parameters',TRUE);
        $functions[] = $this->_('Dojo parameters|/!admin/parameters/dojo|Display all Dojo parameters',TRUE);
        $functions[] = $this->_('Structure management|/!admin/structure/index|Manage modules, controllers and action',TRUE);
        $functions[] = $this->_('Sitemap creation|/!admin/sitemap/index|Create a site map for Google indexation',TRUE);
        $functions[] = $this->_('Todo list|/!admin/todo/index|Manage the todo list for the project',TRUE);
        //$functions[] = $this->_('Function 5||Future enhancement',TRUE);
        foreach($functions as $function){
            $buttons[] = explode('|',$function);
        }
        $this->__buttons = $buttons;
        $this->__test = 'Yes';
        //$this->render('pub');
    }

    

}
