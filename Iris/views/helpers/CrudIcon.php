<?php

namespace Iris\views\helpers;

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
 * @copyright 2011-2013 Jacques THOORENS
 */

/**
 * This helper is part of the CRUD facilites offered by Iris. It serves to 
 * display icons for the different actions. The most part of its job is
 * done by \Iris\Subhelpers\Crud.
 *
 */
class CrudIcon extends _ViewHelper implements \Iris\Subhelpers\iRenderer {

    use \Iris\Subhelpers\tSubhelperLink;

    protected static $_AlternateSubhelper = \NULL;
    
    protected $_baseDir = '/images/icones';

    /**
     * Defines the subhelper used by the class. One can use another subhelper 
     * in a derived class by defining $_AlternateSubhelper
     */
    protected function _init() {
        if (!is_null(static::$_AlternateSubhelper)) {
            $this->_subhelperName = static::$_AlternateSubhelper;
        }
        else {
            $this->_subhelperName = '\Iris\Subhelpers\Crud';
        }
    }

    public static function SetAlternateSubhelper($subhelperName){
        static::$_AlternateSubhelper = $subhelperName;
    }


    /**
     *
     * @param array $params : nom de l'opération et de l'icône
     * @param boolean $iconeActive: choix d'une icone désactivée (ce n'est pas un lien 
     * @return String (cette méthode brise la chaîne)
     */
    public function render(array $params, $iconeActive = TRUE) {
        $operation = $params['operation'];
        $dir = $params['dir'];
        $ref = $params['ref'];
        $help = $params['help'];

        if ($iconeActive) {
            $icon = $this->callViewHelper('image', $operation . ".png", $operation, $help, $dir);
            return '<a href="' . $ref . '">' . $icon . '</a>';
        }
        else {
            $file = $operation . "_des.png";
            $help = $this->_("Operation not possible in context", TRUE);
            $desc = "$operation inactive";
            return $this->callViewHelper('image', $file, $desc, $help, $dir);
        }
    }

    /**
     * Accessor set for icon folder
     * 
     * @param type $baseDir the folder name.
     */
    public function setBaseDir($baseDir) {
        $this->_baseDir = $baseDir;
    }

    /**
     * Translates a message by using its ancestor method with default 
     * to system message
     * 
     * @param string $message Message to display
     * @param boolean $system System message (by default yes)
     * @return string 
     */
    public function _($message, $system = \TRUE) {
        return parent::_($message, $system);
    }

}
