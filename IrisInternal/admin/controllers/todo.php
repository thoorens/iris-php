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
 * In admin internal module, to manage the todo list for the project
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class todo extends _admin {

    protected $_priorities = [
        1 => 'Fatal',
        2 => 'Bug',
        3 => 'Functional',
        4 => 'Optional',
        5 => 'Aspect/style'
    ];

    public function indexAction() {
        $eTodo = \Iris\Admin\models\TTodo::GetEntity();
        $eTodo->order('Priority, Description')
                ->whereNull('ExecutionDate');
        $this->_fetch($eTodo);
        $this->__title = 'Tasks still to be done';
    }

    public function oldAction() {
        $eTodo = \Iris\Admin\models\TTodo::GetEntity();
        $eTodo->order('ExecutionDate, Priority, Description')
                ->whereNotNull('ExecutionDate');
        $this->_fetch($eTodo);
        $this->__title = "Tasks already done";
    }

    private function _fetch($eTodo){
        $todos = $eTodo->fetchAll();
        foreach ($todos as $todo) {
            $todo->extraField('PriorityDesc', $this->_priorities[$todo->Priority] );
        }
        $this->__todos = $todos;
    }
    
    public function createAction() {
        $data = \Iris\Engine\Superglobal::GetPost();
        if (count($data) == 0) {
            $this->reroute('/!admin/todo/new');
        }
        $eTodo = \Iris\Admin\models\TTodo::GetEntity();
        $new = $eTodo->createRow($data);
        $new->ExecutionDate = \NULL;
        $new->save();
        $this->reroute('/!admin/todo/index');
    }

    public function newAction() {
        $formFactory = \Iris\Forms\StandardFormFactory::GetDefaultFormFactory();
        $form = $formFactory->createForm('todo');
        $form->setAction('/!admin/todo/create');
        /* @var $priority \Iris\Forms\Elements\Option */
        $formFactory->createSelect('Priority')
                ->setLabel('Priority')
                ->addTo($form)
                ->addOptions($this->_priorities);
        $formFactory->createArea('Description')
                ->setLabel('Description')
                ->addTo($form);
        $formFactory->createSubmit('Submit')
                ->setValue('Save')
                ->addTo($form);
        $this->__form = $form->render();
    }

    public function markDoneAction($id = \NULL) {
        $now = new \Iris\Time\TimeDate;
        $nowString = $now->toString();
        $this->_mark($nowString, $id);
    }

    public function markUndoneAction($id = \NULL) {
        $this->_mark(\NULL, $id);
    }

    protected function _mark($date, $id) {
        if (!is_null($id)) {
            $eTodo = \Iris\Admin\models\TTodo::GetEntity();
            $todo = $eTodo->find($id);
            if (!is_null($todo)) {
                $todo->ExecutionDate = $date;
                $todo->save();
            }
        }
        $this->reroute('/!admin/todo/index');
    }

}
