<?php



namespace Dojo\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2015 Jacques THOORENS
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
    private $_data = [
        'This data is required' => 'Cette donnée est requise',
        '(Details)' => '(Détails)',
    ];

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


