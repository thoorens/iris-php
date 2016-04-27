<?php
namespace Iris\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
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
        if(strpos(\Iris\SysConfig\Settings::$AvailableLanguages, $newLanguage)===\FALSE){
            $newLanguage = \Iris\SysConfig\Settings::$DefaultLanguage;
            iris_debug($newLanguage);
        }
        $_SESSION['Language'] = $newLanguage;
        \Iris\Translation\_Translator::__ClassInit();
        $this->reroute('/' . implode('/', $parameters));
    }

    

}
