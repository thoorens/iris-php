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
 * This class will manage the screen definitions of the work bench
 */
class screens extends _manager {

    
    public function indexAction($section = 10) {
        $icons = \Iris\Subhelpers\Crud::getInstance();
        $icons
                // controller responsible of the data management
                ->setController('/manager/screens')
                // the action suffix : here action will be e.g. insert_screen
                ->setActionName('screen')
                // entity name and its gender in human language (localized)
                ->setEntity("N_screen")
                // the description field for human messsage
                ->setDescField('Description')
                // the primary key field name
                ->setIdField('id');
        $tSequence = \Iris\DB\TableEntity::GetEntity('sequence');
        $tSequence->where('section_id=', $section);
        $screens = $tSequence->fetchAll();
        $this->__screens = $screens;
        $category = \models_internal\TSections::GetSectionName($section);
        $this->__category = $category;
        $this->__sectionMode = \FALSE;
        $icons->setSubtype($section, $category);
    }

    /**
     * Project of renumber routine
     * 
     * @param type $from
     * @param type $to
     * @param int $value
     */
    public function renumberAction($from, $to, $value){
        $tSequence = \models_internal\TSequence::GetEntity();
        $tSequence->whereBetween('id', $from, $to);
        $screens = $tSequence->fetchAll();
/* @var $screen \Iris\DB\Object */
        foreach($screens as $screen){
            $screen->extraField('NewId', $value);
            $value +=10;
            echo $screen->id.': '.$screen->NewId." ".$screen->Description.'<br>';
        }
        die('ok');
    }
    /**
     * Customizes some values in the CrudManager according to function
     * 
     * @param string $actionName
     */
    protected function _customize($actionName) {
        $this->__sectionMode = \FALSE;
        $parameters = $this->getParameters();

        switch ($actionName) {

            case 'create':
                $param0 = $parameters[0];
                $section = is_null($param0) ? 10 : $param0;
                $category = \models_internal\TSections::GetSectionName($section);
                $this->__Operation = "Add a new screen in $category";
                $this->__section = $section;
                break;
            case 'update':
                $this->__Operation = "Modify a screen content";
                $tSequence = \models_internal\TSequence::GetEntity();
                $screen = $tSequence->find($parameters[0]);
                $section = $screen->section_id;
                if (!is_null($section)){
                    $this->__section = $section;
                }
                else{
                    $this->__section = 0;
                }
                break;
            case 'delete':
                $this->__Operation = "Delete a screen";
                $tSequence = \Iris\DB\TableEntity::GetEntity('sections');
                $screen = $tSequence->find($parameters[0]);
                $section = $screen->section_id;
                if (!is_null($section))
                    $this->__section = $section;
                else
                    $this->__section = 0;
                break;
        }
    }
    

}
