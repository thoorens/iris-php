<?php

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * This abstract class is overridden by others to provide
 * real translation.
 *
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 *
 * PHP 5.4 cumpliant
 *
 * @version $Id: $ */
abstract class _Translator {

    const FRAMEWORKLANGUAGE = 'EN';

    protected static $CurrentLanguage;

    /**
     * The constructor is protected
     */
    protected function __construc(){
        
    }
    
    /**
     * There is only one main translator in the application, defined in settings
     */
    public static function GetInstance(){
        static $mainTranslator = \NULL;
        if(is_null($mainTranslator)){
            $mainTranslatorName = \Iris\SysConfig\Settings::GetDefaultTranslator();
            $mainTranslator = new $mainTranslatorName();
            self::SetLanguage();
        }
        return $mainTranslator;
    }
    

    /**
     * A static method to determine the current language. In CLI mode, it
     * can be used to change the language (by default English).
     */
    public static function SetLanguage($newLanguage = \NULL) {
        static $cliLanguage = 'English';
        if (php_sapi_name() == 'cli') {
            if(!is_null($newLanguage)){
                $cliLanguage = $newLanguage;
            }
            self::$CurrentLanguage = $cliLanguage;
        }
        else {
            $client = new \Iris\System\Client();
            self::$CurrentLanguage = $client->getLanguage();
        }
    }

    

    /**
     * This method needs to be overridden to provide a real translation.
     *
     * @param string $message
     * @param string $language
     * @return string
     */
    public function translate($message, $language = NULL) {
        return $message;
    }

}


