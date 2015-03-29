<?php

namespace modules\acl\controllers;



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
 * @copyright 2011-2013 Jacques THOORENS
 *
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
    protected $_aclIgnore = \FALSE;
    
    protected function _moduleInit() {
        $this->_setLayout('main');
        $this->callViewHelper('subtitle','Access Control Lists');
        $this->__bodyColor = 'ORANGE3';
    }

    
    
}
