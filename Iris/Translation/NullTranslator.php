<?php


namespace Iris\Translation;

use Iris\Exceptions as ie;

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
 * This class is the default translator used by any user translation (as opposed
 * to system translation for messages from the framework). It does nothing
 * except returning the string passed as a parameter to the "translate" method.
 * It can be replaced by another default translator by means of the static method
 * \Iris\Translation\_Translator::SetCurrentTranslator.
 * 
 * After calling, \Iris\Translation\NullTranslator::SetMarkers()with appropriate arguments,
 * initial and ending markers wil be preprend and appened to the string as a way to identy
 * which parts of a text has been "translated".
 * 
 * In any case, the language is ignored.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ * 
 * PHP 5.4 cumpliant
 */
class NullTranslator extends _Translator{
    
    protected static $_Markers = NULL;
    
    
    
    /**
     * A dummy translator, returning the string without change, except
     * leading and ending marker if they have been defined.
     * 
     * @param string $message the text to translate
     * @param string $language if provided, it is ignored
     * @return type 
     */
    public function translate($message, $language = NULL) {
        if(is_array(self::$_Markers)){
            return self::$_Markers[0].$message.self::$_Markers[1];
        }
        elseif(is_string(self::$_Markers)){
            return sprintf(self::$_Markers,$message);
        }
        return parent::translate($message, $language);
    }

    /**
     * Initializes the markers for the translator. A leading and an ending
     * markers are provided or a unique format string for sprintf.
     * 
     * @param string $leadingMarker the leading maker or a format string
     * @param string $endingMarker the ending marker if necessary
     */
    public static function SetMarkers($leadingMarker,$endingMarker=NULL){
        if(is_null($endingMarker)){
            if(strpos($leadingMarker,'%s')===FALSE){
                throw new ie\BadParameterException('The unique marker must contain %s.');
            }
            self::$_Markers = $leadingMarker;
        }else{
            self::$_Markers = array($leadingMarker,$endingMarker);
        }
    }
    
}


