<?php

namespace IrisInternal\admin\controllers;

use \Iris\Users as u;

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
 * In admin internal module, permits to switch role in order to test
 * application behaviour.
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class roles extends _admin {

    public function switchAction() {
        $form1 = $this->_roleChooser('Role name', 'A role to be tested', 'Force role');
        if (is_null($form1)) {
            $this->__form1 = $form1;
        }
        else {
            $this->__form1 = $form1->render();
        }
        $form2 = $this->_userSwitcher();
        if (is_null($form2)) {
            $this->__form2 = $form2;
        }
        else {
            $this->__form2 = $form2->render();
        }
    }

    public function newRoleAction() {
        $role = \Iris\Engine\Superglobal::GetPost('SelectedRole', NULL);
        if (is_null($role)) {
            $this->reroute('/!admin', TRUE);
            return;
        }
        u\Session::GetInstance();
        $identity = u\Identity::GetInstance();
        $identity->setName($role . "_test")
                ->setRole($role)
                ->setId(0)
                ->setEmailAddress('info@irisphp.org')
                ->sessionSave();
        $this->reroute('/', TRUE);
    }

    public function aclAction($role='') {
        throw new \Iris\Exceptions\NotSupportedException('Not developed yet');
        $form = $this->_roleChooser('Role name', 'A role to be tested', 'Force');
        $this->form = $form->render();
    }

    public function newUserAction() {
        $newUser = \Iris\Engine\Superglobal::GetPost('NewUser', NULL);
        if (is_null($newUser)) {
            $this->reroute('/!admin', TRUE);
            return;
        }
        if (is_null(u\TUsers::$systemUserEntity)) {
            throw new \Iris\Exceptions\InternalException('To switch user, you need to define "\Iris\Users\TUsers::$systemUserEntity".');
        }
        $entity = u\TUsers::$systemUserEntity;
        $users = $entity::GetEntity();
        $user = $users->find($newUser);
        $name = $user->getName();
        $role = $user->getRole();

        $identity = \Iris\Users\Identity::GetInstance();
        $identity->userClone($user);
        $identity->sessionSave();
        $this->reroute('/');
    }

    /**
     * 
     */
    private function _roleChooser($label, $tooltip, $submitMessage) {
        $roles = \Iris\Users\Role::GetCollection();
        if (count($roles) == 0) {
            return \NULL;
        }
        $roles = array_keys($roles);
        $ff = new \Iris\Forms\StandardFormFactory();
        $form = $ff->createForm('NewRole');
        $form->setAction('/!admin/roles/newRole');
        $ff->createSelect('SelectedRole', '-')
                ->setLabel("$label:")
                ->setTitle($tooltip)
                ->addTo($form)
                ->addOptions($roles, TRUE);
        $ff->createSubmit('Send')
                ->setValue($submitMessage)
                ->addTo($form);
        return $form;
    }

    private function _userSwitcher() {
        if (is_null(u\TUsers::$systemUserEntity)) {
            return \NULL;
        }
        $entity = u\TUsers::$systemUserEntity;
        $users = $entity::GetEntity();

        $list = $users->userList();
        $ff = new \Iris\Forms\StandardFormFactory();
        $form = $ff->createForm('NewRole');
        $form->setAction('/!admin/roles/newUser');
        $ff->createSelect('NewUser')
                ->setLabel("User ID")
                ->setTitle("Choose an existing user id")
                ->addTo($form)
                ->addOptions($list);
        $ff->createSubmit('Send')
                ->setValue('Force user')
                ->addTo($form);
        return $form;
    }

}

?>
