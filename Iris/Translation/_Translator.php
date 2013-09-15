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
     * A translator is provided by default.
     * 
     * @var _Translator
     */
    protected static $_CurrentTranslator = NULL;

    /**
     * A translator is installed by default or set by the developper.
     * The NullTranslator does not translate anything, but can be
     * customized to mark which text is to be translated
     * 
     * @return _Translator
     */
    public static function GetCurrentTranslator() {
        if (is_null(self::$_CurrentTranslator)) {
            self::$_CurrentTranslator = new \Iris\Translation\NullTranslator();
            self::SetLanguage();
        }
        return self::$_CurrentTranslator;
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
     *
     * @param _Translator $translator 
     */
    public static function SetCurrentTranslator(_Translator $translator) {
        self::$_CurrentTranslator = $translator;
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


