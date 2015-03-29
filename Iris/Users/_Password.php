<?php

namespace Iris\Users;

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
 * An abstract class for managing passwords
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Password {

    const MODE_IRIS = 1;
    const MODE_PHP54 = 4;
    const MODE_PHP55 = 5;
    const UPPER = 'U';
    const LOWER = 'L';
    const LETTER = 'C';
    const DIGIT = '9';
    const SPECIAL = 'S';
    const LETTER_DIGIT = '1';
    const ALL = '.';
    const UPPER_ALL = 'A';
    const UPPER_DIGIT = 'D';
    const LOWER_ALL = 'a';
    const LOWER_DIGIT = 'd';
    const LITTERAL = '"';

    private static $_UpperCaseLetters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    private static $_LowerCaseLetters = 'abcdefghijkmnopqrstuvwxyz';
    private static $_Letters;
    private static $_Digits = '123456789';
    private static $_SpecialSigns = ",.:_$";
    private static $_DigitOrLetter;
    private static $_Mode = \NULL;

    /**
     * ClassInitializer  initializes some variables and
     * load compatibility lib for PHP 5.5 password hash
     * if necessary
     * 
     * This class initializer is not loaded in CLI context
     */
    public static function __ClassInit() {
        self::$_Letters = self::$_LowerCaseLetters . self::$_UpperCaseLetters;
        self::$_DigitOrLetter = self::$_Digits . self::$_Letters;
        if (!version_compare(PHP_VERSION, '5.5') < 0 and \Iris\SysConfig\Settings::GetDefaultHashType() != self::MODE_PHP55) {
            self::ForceCompatibility();
        }
        // CLI does not use this initializer
        define('NOTCLI', \TRUE);
    }

    /**
     * A clean way to charge compatibily file
     */
    public static function ForceCompatibility() {
        if(!defined(PWH_COMPATIBILITY)){
            $compatibilityFile = dirname(__FILE__) . '/password.php';
            include_once $compatibilityFile;
        }
    }

    /**
     * Accessor Get for the mode, will charge the preset value for mode or
     * the default value set in Settings except in case we use CLI
     *  
     * @return int
     */
    public static function GetMode() {
        if (is_null(self::$_Mode)) {
            if (defined('NOTCLI')) {
                self::$_Mode = \Iris\SysConfig\Settings::getDefaultHashType();
            }
            else {
                self::$_Mode = self::MODE_IRIS;
            }
        }
        return self::$_Mode;
    }

    /**
     * A simple Debug routine to display the current mode
     */
    public static function Debug() {
        switch (self::GetMode()) {
            case self::MODE_IRIS:
                echoLine('Mode = internal Iris');
                break;
            case self::MODE_PHP54:
                echoLine('Mode = PHP emulation');
                break;
            case self::MODE_IRIS:
                echoLine('Mode = PHP 5.5');
                break;
        }
    }

    /**
     * An accessor Set for the mode
     * 
     * @param int $mode
     */
    public static function SettMode($mode) {
        switch ($mode) {
            case self::MODE_IRIS:
            case self::MODE_PHP54:
                self::$_Mode = $mode;
                break;
            case self::MODE_PHP55:
                if (defined('NOT_CLI') and version_compare(PHP_VERSION, '5.5') < 0) {
                    self::$_Mode = self::MODE_PHP54;
                    self::ForceCompatibility();
                }
                else {
                    self::$_Mode = self::MODE_PHP55;
                }
                break;
        }
    }

//    public static function UsePHPHash() {
//        if (!defined('NOT_CLI')) {
//            return \FALSE;
//        }
//        else {
//            return \Iris\SysConfig\Settings::IsStandardHashType();
//        }
//    }

    /**
     * Creates an uncrypted password with salt
     * 
     * @param string $password
     * @return string
     */
    public static function EncodePassword($password, $mode = \NULL) {
        if(is_null($mode)){
            $mode = self::GetMode();
        }
        if($mode != self::MODE_IRIS){
            $encrypt = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
        }
        else {
            $pos = rand(0, strlen($password) - 2);
            $subString = substr($password, $pos, $pos + 1);
            $md5 = md5($subString);
            $salt = substr($md5, 0, 2);
            $candidate = md5(crypt($password, $salt));
            $encrypt = $salt . $candidate;
        }
        return $encrypt;
    }

    /**
     * Verifies an password 
     * 
     * @param string $password 
     * @param string $hash
     * @return boolean 
     */
    public static function VerifyPassword($password, $hash, $mode = \NULL) {
        if(is_null($mode)){
            $mode = self::GetMode();
        }
        if($mode != self::MODE_IRIS){
            $result = password_verify($password, $hash);
        }
        else {
            $salt = substr($hash, 0, 2);
            $candidate = md5(crypt($password, $salt));
            $test = $salt . $candidate;
            $result = $hash == $test;
        }
        return $result;
    }

    /**
     * Generates a random password using a pattern. These special characters are used <ul>
     * <li>U : an uppercase letter (no I nor O)
     * <li>L : a lowercase letter (no l)
     * <li>9 : a digit (no 0)
     * <li>D : an uppercase letter or a digit
     * <li>d : a lowercase letter or a digit 
     * <li>1 : a digit or a letter
     * <li>S : a special char among ,.:_$
     * <li>. : a letter or a digit or a special
     * <li>A : an uppercase letter or a digit or a special
     * <li>a : a lowercase letter or a digit or a special
     * <li>" : take the next char as a literal
     * </ul>
     * 
     * @param string $pattern
     * @return string
     */
    public static function GeneratePassword($pattern) {

        $pattern = str_split($pattern);
        $length = count($pattern);
        $password = '';
        while ($length) {
            $source = '';
            switch (array_shift($pattern)) {

                case self::UPPER_ALL:
                    $source .= self::$_SpecialSigns;
                case self::UPPER_DIGIT:
                    $source .= self::$_Digits;
                case self::UPPER :
                    $source .= self::$_UpperCaseLetters;

                    $password .= self::GetRandomChar($source);
                    break;

                case self::LOWER_ALL:
                    $source .= self::$_SpecialSigns;
                case self::LOWER_DIGIT :
                    $source .= self::$_Digits;
                case self::LOWER:
                    $source .= self::$_LowerCaseLetters;

                    $password .= self::GetRandomChar($source);
                    break;

                case self::ALL:
                    $source .= self::$_SpecialSigns;
                case self::LETTER_DIGIT:
                    $source .= self::$_Digits;
                case self::LETTER:
                    $source .= self::$_Letters;

                    $password .= self::GetRandomChar($source);
                    break;

                case self::DIGIT:
                    $source .= self::$_Digits;

                    $password .= self::GetRandomChar($source);
                    break;

                case self::SPECIAL:
                    $source .= self::$_SpecialSigns;

                    $password .= self::GetRandomChar($source);
                    break;

                case self::LITTERAL:
                    $password .= (array_shift($pattern));
                    $length--;
                    break;
            }
            $length--;
        }
        return $password;
    }

    /**
     * Gets a random character from the source
     * 
     * @param string $source
     * @return string
     */
    public static function GetRandomChar($source) {
        $pos = rand(0, strlen($source) - 1);
        return $source [$pos];
    }

}
