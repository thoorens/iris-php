<?php



namespace Tutorial\Translation;

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
 * Adds a few message translations proper to Tutorial library
 * It is implemented as an array.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
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

?>
