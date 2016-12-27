<?php

namespace Tutorial\Translation;

/*
 * This file is part of IRIS-PHP, distributed under the General Public License version 3.
 * A copy of the GNU General Public Version 3 is readable in /library/gpl-3.0.txt.
 * More details about the copyright may be found at
 * <http://irisphp.org/copyright> or <http://www.gnu.org/licenses/>
 *  
 * @copyright 2011-2016 Jacques THOORENS
 */

/**
 * Adds a few message translations proper to Tutorial library
 * It is implemented as an array.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ 
 * @deprecated since version 2015
 */
class SystemTranslator extends \Iris\Translation\SystemTranslator {

    private static $_TutorialInstance = NULL;
    private $_data = array(
        'Restart' => 'Recommencer',
        'Previous' => 'Précédent',
        'Next' => 'Suivant',
        'Stop' => 'Arrêter',
        'Play' => 'Démarrer',
        'Explanations' => 'Explications',
        'Toggle sound' => 'Couper/rétablir le son',
        'Reduce volume' => 'Diminuer le volume sonore',
        'Increase volume' => 'Augmenter le volume sonore',
        'Start again' => 'Recommencer',
        'Next screen' => 'Écran suivant',
        'Previous screen' => 'Ecran précédent',
    );

    public static function GetInstance() {
        if (static::$_TutorialInstance == NULL) {
            static::$_TutorialInstance = new static();
        }
        return static::$_TutorialInstance;
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


