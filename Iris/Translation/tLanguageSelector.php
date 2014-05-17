<?php

/* PHP 5.4 ONLY */

namespace Iris\Translation;

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
 * Trait to provide language selector. Any controller using it becomes
 * a language selector. The action name is considered as the languageto switch
 * to and the parameters are meant as the complete URL of the page to go to.
 * In conjonction with the helper \Iris\views\helpers\Language, it is the
 * URL of the page in which the language button has been pressed.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
trait tLanguageSelector {

    public function __callAction($actionName, $parameters) {
        $newLanguage = substr($actionName, 0, 2);
        if(strpos(\Iris\SysConfig\Settings::GetAvailableLanguages(), $newLanguage)===\FALSE){
            $newLanguage = \Iris\SysConfig\Settings::GetDefaultLanguage();
        }
        $_SESSION['Language'] = $newLanguage;
        \Iris\Translation\_Translator::__ClassInit();
        $this->reroute('/' . implode('/', $parameters));
    }

    

}
