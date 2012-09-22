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

    /**
     * Effectue le travail d'un login en vérifiant le mot de passe
     * en fonction de l'utisateur. renvoie l'action (ou l'erreur)
     * à faire suivre.
     * @return <String> l'action a effectuer
     */
    public function login() {
        $this->_initObjects(self::LOGIN);
        if (\Iris\Engine\Superglobal::GetServer('REQUEST_METHOD') == 'POST') {
            $formData = $this->_form->getDataFromPost();
            if ($this->_form->isValid($formData)) {
                return $this->_postLogin($formData);
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
     * @param array $data
     * @return mixed 
     */
    protected function _postLogin($data) {
        return $this->_errorTreatment;
    }

    
}

?>
