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
 * @copyright 2011-2014 Jacques THOORENS
 */

/**
 * An extension of _Entity with translation capabilities
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
abstract class _TranslatedEntity extends \Iris\DB\_Entity {

    protected $_translatedFields = [];
    protected static $_Language;

    public static function __classInit(){
        self::$_Language = strtoupper(_Translator::GetCurrentLanguage());
    }
    
    public function fetchAll($array = FALSE) {
        $lines = parent::fetchAll($array);
        if($array){
            foreach($lines as $object){
                $this->_translateArray($object);
            }
        }
        else{
            foreach($lines as $object){
                $this->_translateObject($object);
            }
        }
        return $lines;
    }

    public function fetchRow($condition = \NULL, $value = \NULL) {
        $object = parent::fetchRow($condition, $value);
        $this->_translateObject($object);
        return $object;
    }

    public function find($idValues) {
        $object = parent::find($idValues);
        $this->_translateObject($object);
        return $object;
    }

    private function _translateObject($object) {
        foreach ($this->_translatedFields as $field) {
            $otherField = $field . self::$_Language;
            $translation = $object->$otherField;
            if (!empty($translation)) {
                $object->$field = $translation;
            }
        }
    }

    private function _translateArray($array) {
        foreach ($this->_translatedFields as $field) {
            $otherField = $field . self::$_Language;
            if (!empty($array[$otherField])) {
                $array[$field] = $array[$otherField];
            }
        }
    }
    
}
