<?php

namespace modules\manager\controllers;

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
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * 
 * Created for IRIS-PHP 0.9 - beta
 * Description of categories
 * 
 * @author jacques
 * @license not defined
 */
class sections extends _manager {

    public function indexAction() {
        $icons = \Iris\Subhelpers\Crud::getInstance();
        $icons
                // controller responsible of the data management
                ->setController('/manager/sections')
                // the action suffix : here action will be e.g. insert_section
                ->setActionName('section')
                // entity name and its gender in human language (localized)
                ->setEntity("F_section")
                // the description field for human messsage
                ->setDescField('GroupName')
                // the primary key field name
                ->setIdField('id');
        $tSection = \Iris\DB\TableEntity::GetEntity('sections');
        $tSection->where('id<>', 0);
        $this->__data = $tSection->fetchAll();
        $this->__sectionMode = \TRUE;
    }

    /**
     * Customizes some values in the CrudManager according to function
     * 
     * @param string $actionName
     */
    protected function _customize($actionName) {
        $this->__sectionMode = \TRUE;
        switch($actionName){
            case 'create':
                $this->__Function = "Add a new category";
                break;
            case 'update':
                $this->__Function = "Modify a category";
                break;
            case 'delete':
                $this->__Function = "Delete a category";
                break;
        }
    }
    
}
