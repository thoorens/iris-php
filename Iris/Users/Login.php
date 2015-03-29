<?php

namespace Iris\Users;

use Iris\Forms\Validators\Force as vf;

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
 * A specialized _Crud mainly design to realize a login
 *  
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class Login extends \Iris\DB\DataBrowser\_Crud {

    /**
     * A specific mode for 
     */
    const LOGIN_MODE = 5;
    const DIGIT = 1;
    const ASCIILETTER = 2;
    const ACCENT = 3;
    const BLANK = 4;
    const ERR_NOUSER = 21;
    const ERR_BADPWD = 22;

    /*
     * These 5 field names may overridden in subclasses. They are
     */

    /**
     * The field containing the primary key of the user table
     * (it must be simple)
     * 
     * @var string
     */
    private $_idField = 'id';

    /**
     * The field containing the username
     * 
     * @var string
     */
    private $_nameField = 'Username';

    /**
     * The field containing the role of the user
     * @var string
     */
    private $_roleField = "Role";

    /**
     * The field containing the password
     * @var string
     */
    private $_passwordField = 'Password';

    /**
     * The field containing the email address
     * @var string
     */
    private $_emailField = "Email";
    
    /**
     * A special URL for a bad password corresponding to an existent user name
     * 
     * @var string
     */
    private $_badPairTreatment = '/';
    
    /**
     * A form factory to create the form (may be specified to supercede
     * the default one
     * 
     * @var \Iris\Forms\_FormFactory
     */
    private $_formFactory;

    

    /**
     * A method to test a username and password against the database
     * 
     * @return string an URL to go to (or NULL in case of unknown user)
     */
    public function login($specialChars = \NULL) {
        $this->_initObjects(self::LOGIN_MODE);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            if ($this->_form->isValid($formData)) {
                $this->_returnCode = $this->_validateLogin($formData, $specialChars);
            }
            else {
                $this->_returnCode = NULL;
            }
        }
        else {
            $this->_returnCode = NULL;
        }
        return $this->_returnCode;
    }

    /**
     * Considers username and password (included in data) and
     * returns one of three values:<ul>
     * <li> NULL if user unknown
     * <li> $this->finalTreatment if username and password matched
     * <li> $this->errorTreatment if password doesn't match
     * </ul>
     * 
     * @param mixed[] $data
     * @return mixed 
     */
    protected function _validateLogin($data, $specialChars = \NULL) {
        if(is_null($specialChars)){
            $specialChars = self::ASCIILETTER + self::DIGIT;
        }
        $userName = $this->_clean($data[$this->_nameField], $specialChars);
        $password = $data[$this->_passwordField];
        $entity = $this->getCrudEntity();
        $entity->where($this->_nameField . '=', $userName);
        $userObject = $entity->fetchRow();
        //iris_debug($userObject);
        // user unknown
        if ($userObject == null) {
            $returnCode = $this->getContinuationURL();
        }
        else {
            $encrypt = $userObject->Password;
            if (\Iris\Users\_Password::VerifyPassword($password, $encrypt)) {
                $this->_postLogin($userObject, $userName);
                $identity = \Iris\Users\Identity::GetInstance();
                $idField = $this->_idField;
                $roleField = $this->_roleField;
                $emailField = $this->_emailField;
                $identity->setName($userName)
                        ->setEmailAddress($userObject->$emailField)
                        ->setRole($userObject->$roleField)
                        ->setId($userObject->$idField)
                        ->sessionSave();
                // OK
                $returnCode = $this->_endURL;
            }
            else {
                $returnCode = $this->_badPairTreatment;
            }
        }
        return $returnCode;
    }

    /**
     * This method may do a small job after login: e.g. write login information.
     * If may be overloaded in a subclass or enabled by a call to 
     * definePostLogin(callable) method.
     * 
     * @param \Iris\DB\Object $userObject
     * @param string $userName
     * @return boolean
     */
    protected function _postLogin($object, $username) {
       if(isset($this->_callBack['postlogin'])){
           $postLoginFunction = $this->_callBack['postlogin'];
           $postLoginFunction($object, $username);
       } 
    }

    

    /**
     * Keeps only accepted characters 
     * 
     * @param string $toClean
     * @param int $admited
     * @param string $specialChars
     * @return string
     */
    protected function _clean($toClean, $admited, $specialChars = '') {
        $accept = '/[^';
        if ($admited && self::DIGIT) {
            $accept .= "\\d";
        }
        if ($admited && self::ASCIILETTER) {
            $accept .="a-zA-Z";
        }
        if ($admited && self::ACCENT) {
            $accept .="âêîôûŷäëïöüÿáéíóúýàèìòùỳçÂÊÎÔÛŶÄËÏÖÜŸÁÉÍÓÚÝÀÈÌÒÙỲÇ";
        }
        if ($admited && self::BLANK) {
            $accept .=" ";
        }
        $accept .= $specialChars;
        $accept .= ']/si';
        return trim(preg_replace($accept, '', $toClean));
    }

    
    /**
     * Special case: the user exists but the password is not correct.
     * Advice: do not stay on login page, to difficult repetitive tries
     * 
     * @param type $url
     * @return \Iris\Users\Login for fluent interface
     */
    public function setBadPairUrl($url) {
        $this->_badPairTreatment = $url;
        return $this;
    }

    /**
     * An alias for setEndURL: the final treatment may be a welcome screen
     * 
     * @param string $url
     * @return \Iris\Users\Login for fluent interface
     */
    public function setWelcomeUrl($url) {
        $this->_endURL = $url;
        return $this;
    }

    public function setIdField($idField) {
        $this->_idField = $idField;
        return $this;
    }

    public function setNameField($nameField) {
        $this->_nameField = $nameField;
        return $this;
    }

    public function setRoleField($roleField) {
        $this->_roleField = $roleField;
        return $this;
    }

    public function setPasswordField($passwordField) {
        $this->_passwordField = $passwordField;
        return $this;
    }

    public function setEmailField($emailField) {
        $this->_emailField = $emailField;
        return $this;
    }

    /**
     * A minimal form to login in
     * @return \Iris\Forms\_Form
     */
    protected function _makeDefaultForm() {
        $ff = $this->getFormFactory();
        $form = $ff->createForm('Login');

        $ff->createText($this->_nameField)
                ->setLabel($this->_('User name:'))
                ->setTitle('Use the name ')
                ->addValidator($ff->validatorForce(vf::ALPHA | vf::NUM | vf::LOWER | vf::FORCE, '_'))
                ->addTo($form)
                ->addValidator('Required');
        $ff->createPassword($this->_passwordField)
                ->setLabel($this->_('Password:'))
                ->setTitle('')
                //->addValidator($ff->validatorRequired())
                ->addTo($form)
                ->addValidator('Required');
        $ff->createSubmit('Submit')
                ->setValue($this->_('Log In'))
                ->addTo($form);
       
        return $form;
    }

    /**
     * Developper can set a alternative form factory
     * 
     * @param \Iris\Forms\_FormFactory $formFactory
     * @return \Iris\Users\Login
     */
    public function setFormFactory($formFactory) {
        $this->_formFactory = $formFactory;
        return $this;
    }

    /**
     * If no user defined form factory, takes the defaul one
     * 
     * @return \Iris\Forms\_FormFactory
     */
    public function getFormFactory() {
        if (is_null($this->_formFactory)) {
            $factory = \Iris\Forms\_FormFactory::GetDefaultFormFactory();
        }
        else {
            $factory = $this->_formFactory;
        }
        return $factory;
    }

    

}
