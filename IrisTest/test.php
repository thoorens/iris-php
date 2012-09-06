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
 *
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * Description of test
 * 
 */
class test extends \IrisInternal\main\controllers\_SecureInternal {

    public function indexAction() {
//        $session = u\Session::GetInstance();
//        $identity = u\Identity::GetInstance();
//        echo $identity->getName() . '<br/>';
//        if ($identity->hasRole('testeur')) {
//            $identity->reset();
//        } else {
//            $other = new u\Somebody('testeur&testeur@thoorens.net&testeur');
//            $identity->change($other);
//        }
//        echo $identity->getName() . '<br/>';
//        $users = new \models\TUsers;
//        $user = $users->fetchUser('admin');
//        //\Iris\Engine\Debug::DumpAndDie($user);
//        echo "Role obtenu : ".$user->Description_at_role_id.'<br/>';
//        echo $user->getName()." avec role(s)<br/> ";
//        \Iris\Engine\Debug::Dump($user->getRoles());

        $roles = new \models\TRoles();
        $role = $roles->find(1);
        echo $role->name;
        $test = $role->_children_user2;

        die('fin du test');
    }

}

?>
