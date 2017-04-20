<?php

namespace IrisInternal\admin\controllers;

use \Iris\Users as u;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
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

    /**
     * Permits, if possible, to switch to another user or to a pseudo user without
     * a password in DEVELOPPER mode
     * The action tries to create 2 forms
     */
    public function switchAction() {
        // tries to find the existing roles in acl.ini
        $form1 = $this->_roleChooser('Role name', 'A role to be tested', 'Force role');
        if (is_null($form1)) {
            $this->__form1 = $form1;
        }
        else {
            $this->__form1 = $form1->render();
        }
        // tries to find the user names in a database
        $form2 = $this->_userSwitcher();
        if (is_null($form2)) {
            $this->__form2 = $form2;
        }
        else {
            $this->__form2 = $form2->render();
        }
    }

    public function rolelistAction() {
        $this->__roles = \Iris\Users\Role::GetCollection();
    }

    /**
     * Saves a new pseudo user found in POST in the session file
     */
    public function newRoleAction() {
        $role = \Iris\Engine\Superglobal::GetPost('SelectedRole', \NULL);
        if (is_null($role)) {
            $this->reroute('/!admin', \TRUE);
        }
        else {
            $identity = \Iris\Users\Identity::GetInstance();
            $identity->setName($role . "_test")
                    ->setRole($role)
                    ->setId(0)
                    ->setEmailAddress("$role@irisphp.org")
                    ->sessionSave();
            $this->reroute('/', \TRUE);
        }
    }

    public function userlistAction() {
        $fields = ['id', 'Name','Role','Email','Password'];
        $tUsers = \models\TUsers::GetEntity()->select($fields);
        $users = $tUsers->fetchAllInArray();
        $tab = new \Iris\Subhelpers\Table();
        $tab->setTitles($fields)->setContent($users);
        $this->__tab = $tab;
    }

    /**
     * Specifies a new user and go to the main page
     * 
     * @throws \Iris\Exceptions\InternalException
     */
    public function newUserAction() {
        $newUser = \Iris\Engine\Superglobal::GetPost('NewUser', NULL);
        if (is_null($newUser)) {
            $this->reroute('/!admin', TRUE);
        }
        else {
            if (is_null(\Iris\SysConfig\Settings::$SystemUserEntity)) {
                throw new \Iris\Exceptions\InternalException('To switch user, you need to define " \Iris\SysConfig\Settings::$SystemUserEntity".');
            }
            $entity = \Iris\SysConfig\Settings::$SystemUserEntity;
            $users = $entity::GetEntity();
            $user = $users->find($newUser);
//            $name = $user->getName();
//            $role = $user->getRole();

            $identity = \Iris\Users\Identity::GetInstance();
            $identity->userClone($user);
            $identity->sessionSave();
            $this->reroute('/');
        }
    }

    /**
     * 
     * @param type $role
     */
    public function aclAction($role = '') {
        $form = $this->_roleChooser('Role name', 'A role to be tested', 'Force');
        $this->form = $form->render();
    }

    /**
     * 
     */
    private function _roleChooser($label, $tooltip, $submitMessage) {
        $roleCollection = \Iris\Users\Role::GetCollection();
        if (count($roleCollection) == 0) {
            $returnValue = \NULL;
        }
        else {
            $roles = array_keys($roleCollection);
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
            $returnValue = $form;
        }
        return $returnValue;
    }

    /**
     * 
     * @return type
     */
    private function _userSwitcher() {
        if (is_null(\Iris\SysConfig\Settings::$SystemUserEntity)) {
            return \NULL;
        }
        $entity = \Iris\SysConfig\Settings::$SystemUserEntity;
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
