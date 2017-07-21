<?php

namespace Iris\Engine;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2017 Jacques THOORENS
 */

/**
 *  A way to access superglobals, with security and default value
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 */
abstract class Security {

    public static function Debug() {
        i_d(filter_list());
        die('OK');
    }

    protected static function _GetData($type, $default, $key = \NULL, $source = \NULL) {
        // verifies default and key coherence
        if(empty($default) and $key === \NULL){
            throw new \Iris\Exceptions\BadParameterException("Security\\Get functions need a default value or a key");
        }
        // verifies key and source coherence
        if(!empty($key) and $source == \NULL){
            throw new \Iris\Exceptions\BadParameterException("Security\\Get functions with a keyneed a source");
        }
        if($source === \NULL or ! isset($source[$key])){
            $value = $default;
        }
        else{
            $value = $source[$key];
        }
        i_dnd(filter_input($value, FILTER_VALIDATE_INT));
        i_dnd($value);
        return $value;
    }

    public static function GetInt($default, $key = \NULL, $source = \NULL) {
        return self::_GetData(FILTER_VALIDATE_INT, $default, $key, $source);
    }

    public static function GetBoolean($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetFloat($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetRegexp($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetDomain($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetURL($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetIP($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetString($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetStripped($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetEncoded($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetSpecialChars($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetFullSpecialChars($default, $key = \NULL, $source = \NULL) {
        
    }

    public static function GetUnsafeRow($default, $key = \NULL, $source = \NULL) {
        
    }

    /* ================================================================================
     * Email address
     * ================================================================================
     */
    
    /**
     * 
     * @param type $default
     * @param type $key
     * @param type $source
     */
    public static function GetEmail($default, $key = \NULL, $source = INPUT_POST) {
        $value = self::_GetData(FILTER_VALIDATE_EMAIL, $default, $key, $source);
        if($value == \FALSE){
            $value = self::VerifyEmail($default);
        }
        return $value;
    }

    public static function VerifyEmail($value, $checkDomain = \FALSE){
        $returnValue = filter_var(FILTER_SANITIZE_EMAIL, $value);
        
        if($checkDomain){
            
        }
        i_d($returnValue);
        return $returnValue;
    }
    
//    public static function Get($default, $key = \NULL, $source = \NULL) {
//        
//    }
//
//    public static function Get($default, $key = \NULL, $source = \NULL) {
//        
//    }

    /*
      "int"
      "boolean"
      "float"

      "validate_regexp"
      "validate_domain"
      "validate_url"
      "validate_email"
      "validate_ip"
      "validate_mac"

      "string"
      "stripped"
      "encoded"
      "special_chars"
      "full_special_chars"
      "unsafe_raw"
      "email"
      "url"
      "number_int"
      "number_float"
      "magic_quotes"
      "callback"
     */
}
