<?php
namespace Iris\DB\DataBrowser;

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


abstract class _Login extends _Crud {
    const LOGIN = 5;

    const DIGIT = 1;
    const ASCIILETTER = 2;
    const ACCENT = 3;
    const BLANK = 4;
    
    protected static $_IdField = 'id';
    protected static $_NameField = 'Username';
    protected static $_PasswordField = 'Password';
    protected static $_EmailField = "Email";
    protected static $_RoleField = "Role";        
    
    /**
     * A method to test a username and password against the database
     * 
     * @return string an URL to go to (or NULL in case of unknown user)
     */
    public function login($specialChars = '') {
        $this->_initObjects(self::LOGIN);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            if ($this->_form->isValid($formData)) {
                return $this->_validateLogin($formData, $specialChars);
            }
            return NULL;
        }
        return NULL;
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
    protected function _validateLogin($data, $specialChars) {
        $userName = $this->_clean($data[self::$_NameField], self::ASCIILETTER + self::DIGIT);
        $password = $data[self::$_PasswordField];
        $entity = $this->_entity;
        $entity->where(self::$_NameField.'=', $userName);
        $userObject = $entity->fetchRow();
        //iris_debug($userObject);
        // user unknown
        if ($userObject == null) {
            return null;
        }
        $encrypt = $userObject->Password;
        if (\Iris\Users\_Password::VerifyPassword($password, $encrypt)) {
            $this->_postLogin($userObject, $userName);
            $identity = \Iris\Users\Identity::GetInstance();
            $idField = self::$_IdField;
            $roleField = self::$_RoleField;
            $emailField = self::$_EmailField;
            $identity->setName($userName)
                    ->setEmailAddress($userObject->$emailField)
                    ->setRole($userObject->$roleField)
                    ->setId($userObject->$idField)
                    ->sessionSave();
            // OK
            return $this->_endTreatment;
        }
        else {
            // bad password
            return $this->_errorTreatment;
        }
    }
    /**
     * 
     * @param \Iris\DB\Object $userObject
     * @param string $userName
     * @return boolean
     */
    protected abstract function _postLogin($object, $username);
    
    
    /**
     * Keeps only accepted characters 
     * 
     * @param string $toClean
     * @param int $admited
     * @param string $specialChars
     * @return string
     */
    protected function _clean($toClean, $admited, $specialChars='') {
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
}


