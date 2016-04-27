<?php
namespace modules\acl\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * Description of simple
 * 
 *
 * @author Jacques THOORENS (jacques@thoorens.net)
 * 
 * @license GPL 3.0 http://www.gnu.org/licenses/gpl.html
 * @version $Id: $ */
abstract class _acl extends \modules\_application {

    /**
     * In this module, we are going to use the ACL in a normal way
     * @var boolean
     */
    public $aclIgnore = \FALSE;
    
    protected function _moduleInit() {
        $this->_setLayout('main');
        $this->callViewHelper('subtitle','Access Control Lists');
        $this->__bodyColor = 'ORANGE3';
    }

    
    
}
