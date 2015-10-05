<?php

namespace Iris\Users;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
 */

/**
 * An abstract class for managing passwords
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
abstract class _Password {

    /**
     * MODE_IRIS uses an internal routine to crypt the password
     */
    const MODE_IRIS = 1;

    /**
     * MODE_PHP54 uses a special routine written by Anthony Ferrara for use with 
     * a PHP 5.4 server
     */
    const MODE_PHP54 = 4;

    /**
     * MODE_PHP55 uses an internal routine implemented in PHP 5.5 and above
     */
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

    /**
     * Contains all the standard uppercase letters
     * @var string
     */
    private static $_UpperCaseLetters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * Contains all the standard lowercase letters
     * @var string
     */
    private static $_LowerCaseLetters = 'abcdefghijkmnopqrstuvwxyz';

    /**
     * Contains all the digits
     * @var string
     */
    private static $_Digits = '0123456789';

    /**
     * Contains five special characters
     * @var string
     */
    private static $_SpecialSigns = ",.:_$";

    /**
     * Will be inited with both uppercase en lowercase letters
     * @var string
     */
    private static $_Letters;

    /**
     * Will contains all letters and digits
     * @var string
     */
    private static $_DigitOrLetter;
    
    /**
     * The mode may contains a forced mode without paying attention to the 
     * actual setting
     * @var int 
     */
    private static $_Mode = \NULL;

    /**
     * ClassInitializer  initializes some variables and
     * load compatibility lib for PHP 5.5 password hash
     * if necessary
     * 
     * This class initializer is not executed in CLI context
     */
    public static function __ClassInit() {
        self::$_Letters = self::$_LowerCaseLetters . self::$_UpperCaseLetters;
        self::$_DigitOrLetter = self::$_Digits . self::$_Letters;
        //iris_debug(PHP_VERSION_ID);
        //iris_debug(version_compare(PHP_VERSION, '50500', '<'));
        //iris_debug(\Iris\SysConfig\Settings::$DefaultHashType);
        if (!version_compare(PHP_VERSION, '50500')) {
            iris_debug(PHP_VERSION);
            self::ForceCompatibility();
        }
        // CLI does not use this initializer
        define('NOTCLI', \TRUE);
    }

    /**
     * A clean way to charge compatibily file
     */
    public static function ForceCompatibility() {
        if (\Iris\SysConfig\Settings::$DefaultHashType == self::MODE_PHP55 and !defined('PWH_COMPATIBILITY')) {
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
                self::$_Mode = \Iris\SysConfig\Settings::$DefaultHashType;
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
                iris_debug('Mode = internal Iris');
                break;
            case self::MODE_PHP54:
                iris_debug('Mode = PHP emulation');
                break;
            case self::MODE_PHP55:
                iris_debug('Mode = PHP 5.5');
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
                self::ForceCompatibility();
                break;
            case self::MODE_PHP55:
                // PHP 5.5 mode cannot be forced on a PHP 5.4 server
                if (defined('NOT_CLI') and version_compare(PHP_VERSION, '5.5', '<')) {
                    self::$_Mode = self::MODE_PHP54;
                    self::ForceCompatibility();
                }
                else {
                    self::$_Mode = self::MODE_PHP55;
                }
                break;
        }
    }

    /**
     * Creates an encrypted password with salt
     * 
     * @param string $password
     * @return string
     */
    public static function EncodePassword($password, $mode = \NULL) {
        if (is_null($mode)) {
            $mode = self::GetMode();
        }
        if ($mode != self::MODE_IRIS) {
            if($mode == 4){
                self::ForceCompatibility();
            }
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
        if (is_null($mode)) {
            $mode = self::GetMode();
        }
        if ($hash[0] != '$') {
            $mode = self::MODE_IRIS;
        }
        if ($mode != self::MODE_IRIS) {
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

    
    public static function VerifySystem($password = \NULL, $irisHash = \NULL, $phpHash = \NULL ){
        if(is_null($password)){
            $password = 'azertyuiop';
            $irisHash = '9f961dcdfcfe19cdae4774ed2342ab5760';
            $phpHash = '$2y$10$IBm95bLVKz9c56.zoyNcj.PfuloDFMU4uJ7TwlblPLYj74J4kKU4K';
        }
        $testIris = self::VerifyPassword($password, $irisHash) ? 'GOOD' : 'BAD';
        $testPhp = self::VerifyPassword($password, $phpHash) ? 'GOOD' : 'BAD';
        echo <<<TABLE
<table>
    <tr>
        <th>
            The password (in clear)
        </th>
        <td>
            $password
        </td>
        <td></td>        
    </tr>    
    <tr>
        <th>
            The Iris crypted password    
        </th>
        <td>
            $irisHash
        </td>
        <td>
            $testIris
        </td>        
    </tr>    
    <tr>
        <th>
           The PHP crypted password
        </th>
        <td>
           $phpHash     
        </td>
        <td>
           $testPhp
        </td>        
    </tr>    
</table>        
TABLE;
        die('Password checked completed');
    }
}
