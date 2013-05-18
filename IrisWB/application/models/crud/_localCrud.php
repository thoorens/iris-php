<?php

namespace models\crud;
use modules\db\controllers\helpers;

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
 
    /**
     * The name of the table
     * 
     * @var string
     */
    protected static $_TableName;
    /**
     * The list of fields in primary key
     * 
     * @var array
     */
    protected static $_IdName;
    
    public function __construct($param = NULL) {
        die('CRUD problem');
        parent::__construct($param);
        $dbType = \Iris\Users\Session::GetInstance()->getValue('dbType', 'sqlite');
        $entity = \Iris\DB\DataBrowser\AutoEntity::EntityBuilder(static::$_TableName, [static::$_IdName],
                \models\_invoiceManager::getEM($dbType));
        $this->setEntity($entity);
        $this->setActions("error", static::$_TableName);
   
    }

    private function _test(){
        iris_debug(static::$_TableName);
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
