<?php

namespace modules\acl\controllers;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */
/**
 * Project : srv_IrisWB
 * Created for IRIS-PHP 1.0 RC2
 * Description of login
 *
 * @author
 * @license
 */
class login extends _acl {

    /**
     * Settings specific to IrisWB display and sequence integration
     */
    protected function _init() {
        $this->_setLayout('main');
        $this->__bodyColor = 'ORANGE3';
        $this->_sequenceIntegrate();
    }

    /**
     * Displays information about the loged user and offer action buttons
     * to make a change
     *
     * @param int $status if status is not 0, a message insists if the
     * user is not connected
     */
    public function indexAction($status = 0) {
        $id = $this->_whoami();
        if ($status and $id == 0 ) {
            $this->__status = 'Your are not connected...';
        }
        else {
            $this->__status = 'You are connected...';
        }
    }

    /**
     * This page will be only accessible if the user has been identified
     */
    public function welcomeAction() {
        $this->_whoami();
        $this->__status = '';
    }

    /**
     * Place user data in the view <ul>
     * <li> id
     * <li> name
     * <li> role
     * <li> email address
     * </ul>
     * @return type
     */
    private function _whoami() {
        $identity = \Iris\Users\Identity::GetInstance();
        $id = $identity->getId();
        $this->__id = $id;
        $this->__name = $identity->getName();
        $this->__role = $identity->getRole();
        $this->__email = $identity->getEmailAddress();
        return $id;
    }

    /**
     * Lists all the known user
     */
    public function listAction(){
        $entity = \models\TUsers::GetEntity();
        // we don't need the password field
        $entity->select(['id','name','role','email']);
        $users = $entity->fetchAllInArray();
        $this->__titles = ['id','name','role','email'];
        $this->__users = $users;
        $this->__status = 'Liste des utilisateurs reconnus';
    }

    /**
     * Permits to login
     */
    public function loginAction() {
        $login = new \Iris\Users\Login();
        $login
        // in case of good user/password pair
                ->setWelcomeUrl('/acl/login/welcome')
                // in case of a bad password for an existing user
                ->setBadPairUrl('/acl/login/index/1')
                // in case of a bad account, it is possible to
                // switch to an alternative login screen (not frequent)
                ->setContinuationURL('/acl/login/login2')
                // default value in case of error
                //->setErrorURL('/error')
                ->setNameField('Name');
                //->setEntity(\models\TUsers::GetEntity());
        $login->getForm()->setLayout('NoLayout');
        $next = $login->login('\.\,');
        if (is_string($next)) {
            $this->reroute($next, TRUE);
        }
        $this->__form = $login->formRender();
        $this->__title = 'Login screen';
    }

    /**
     * Permits to login second try
     */
    public function login2Action() {
        $this->loginAction();
        $this->__title = 'Login screen (2nd chance)';
        $this->setViewScriptName('login');
    }

    /**
     * In case of logout, go to the main page of the controller
     */
    public function logoutAction() {
        \Iris\Users\Identity::GetInstance()->reset();
        $this->reroute('/acl/login/index');
    }

    /**
     * An example of a programatically set user
     */
    public function forceAction() {
        $now = time();
        $identity = \Iris\Users\Identity::GetInstance();


        // Method 1 : using a serialized string
        //$identity->unserialize('14&smith&tester&test@irisphp.org&' . $now);

        // Method 2 : creating a user and settings its attributes
        $identity->setId(14)
                ->setName('smith')
                ->setRole('tester')
                ->setEmailAddress('smith@irisphp.org')
                ->setTimer($now);
                //->set;

        $identity->sessionSave();
        $this->reroute('/acl/login/welcome');
    }

    /**
     * This part of the init is to do with special comments in column 1
     */
    private function _sequenceIntegrate() {
        $action = $this->getActionName();
        if (strpos('loginlogin2welcome', $action) !== \FALSE) {
            switch ($action) {
                case 'login2':
                case 'login':
                    $this->_specialScreen([
                        "Log in with one of the user account and password",
                        "Type a valid account with an incorrect password.",
                        "Type a dummy account and whatever password you want"]);
                    break;
                case 'welcome':
                    $this->__specialScreen = 1;
                    $this->_specialScreen("Log out to return to the base page of this module.");
                    break;
            }
        }
    }

}
