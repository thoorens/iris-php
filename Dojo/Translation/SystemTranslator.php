<?php



namespace Dojo\Translation;

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
 * Adds a few message translation proper to Dojo library
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class SystemTranslator extends \Iris\Translation\SystemTranslator {

    private static $_DojoInstance = NULL;
    private $_data = array(
        'This data is required' => 'Cette donnée est requise'
    );

    public static function GetInstance() {
        if (static::$_DojoInstance == NULL) {
            static::$_DojoInstance = new static();
        }
        return static::$_DojoInstance;
    }

    private function __construct() {
        
    }

    public function translate($message, $language = NULL) {
        // if possible find specific translation
        if (isset($this->_data[$message])) {
            return $this->_data[$message];
        }
        // otherwise return standard translation
        else {
            return parent::translate($message, $language);
        }
    }

}


