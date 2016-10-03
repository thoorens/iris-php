<?php

namespace models\crud;

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
 *
 * 
 */

/**
 * 
 * Test of basic crud operations on invoices
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _localCrud extends \Iris\DB\DataBrowser\_Crud {
 
    public static function __ClassInit() {
        $EM = \models\_invoiceEntity::GetEM();
        static::$_EntityManager = $EM;
    }
    
    
    protected function _postUpdate($object) {
        $this->_setModified();
    }

    protected function _postDelete(&$object) {
        $this->_setModified();
    }
    
    protected function _postCreate($object) {
        $this->_setModified();
    }
    
    private function _setModified(){
        \Iris\controllers\helpers\_ControllerHelper::HelperCall('dbState', [],\NULL)->setModified();
    }
}
