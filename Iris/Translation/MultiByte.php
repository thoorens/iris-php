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
 * @copyright 2012 Jacques THOORENS
 */

/**
 * A simple way to manage multibyte string
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 */
class MultiByte implements \Iterator {

    // for PHP 5.4   
//    public static function No_Accent($string){
//        $myTrans = \Transliterator::create('NFD; [:Nonspacing Mark:] Remove; NFC'); 
//        return \Transliterator::transliterate($myTrans, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'); 
//        return $newString;
//    }

    protected $_string;
    protected $_encoding;

    /**
     *
     * @param type $string
     * @param type $encoding 
     */
    public function __construct($string, $encoding) {
        $this->_string = $string;
        $this->_encoding = $encoding;
    }

    /**
     * Replaces any character with accent or modification to a pure ASCII
     * in the string 
     * 
     * @return MultiByte for fluent interface
     */
    public function noAccent() {
        $this->_string = self::No_Accent($this->_string, $this->_encoding);
        return $this;
    }

    /**
     * Removes spaces or replace them by _ in the internal string 
     * 
     * @return MultiByte for fluent interfac
     */
    public function noSpace($replace=FALSE) {
        $this->_string = self::No_Space($this->_string, $replace);
        return $this;
    }

    /**
     *
     * @return Multibyte for fluent interfac
     */
    public function spaceToUnderscore() {
        return $this->noSpace(TRUE);
    }

    /**
     * Removes any accent from a string
     * 
     * @param string $str
     * @param string $charset
     * @return Multibyte for fluent interfac
     */
    public static function No_Accent($str, $charset='utf-8') {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

        return $str;
    }

    /**
     * Returns the content of the string
     * 
     * @return string
     */
    public function __toString() {
        return $this->_string;
    }

    /**
     * 
     * @param type $string
     * @param type $charset
     * @return type
     */
    public static function StrLen($string, $charset='utf-8') {
        return mb_strlen($string, $charset);
    }

    /**
     * 
     * @param type $str
     * @param type $replace
     * @return type
     */
    public static function No_Space($str, $replace=FALSE) {
        if ($replace) {
            return str_replace(' ', '_', $str);
        }
        else {
            return str_replace(' ', '', $str);
        }
    }

    /**
     * 
     * @return \Iris\Translation\MultiByte
     */
    public function toUpper() {
        $this->_string = mb_strtoupper($this->_string, $this->_encoding);
        return $this;
    }

    /**
     * 
     * @return \Iris\Translation\MultiByte
     */
    public function toLower() {
        $this->_string = mb_strtolower($this->_string, $this->_encoding);
        return $this;
    }

    /**
     * 
     * @param type $string
     */
    public function reInit($string) {
        $this->_string = $string;
    }

    /**
     *
     * @var type 
     */
    protected $_index;

    /**
     * 
     * @return type
     */
    public function current() {
        return \mb_substr($this->_string, $this->_index, 1, $this->_encoding);
    }

    /**
     * 
     * @return type
     */
    public function key() {
        return $this->_index;
    }

    /**
     * 
     */
    public function next() {
        $this->_index++;
    }

    /**
     * 
     */
    public function rewind() {
        $this->_index = 0;
    }

    /**
     * 
     * @return boolean
     */
    public function valid() {
        return $this->_index < mb_strlen($this->_string, $this->_encoding);
    }

    /**
     * 
     * @param type $string
     * @return string
     */
    public static function FullTrim($string) {
        return preg_replace("#[ \t\r\n]?#", '', $string);
    }

}

?>
