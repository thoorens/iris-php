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
 * This class offers an access to a expandable series of controllers
 * whose methods returns messages in the client language. Two series
 * are provides : 'wb' for explanation on Work Bench screens and
 * 'error' a special commentary to planned errors in Work Bench.
 * Any class containing many localized strings can be accessed this way.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */
class _Messages{
    use \Iris\Translation\tTranslatable;
    
    
    private static $_Constrollers = array(
        'wb' => "\\workbench\\messages\\%s",
        'error' => "\\modules\\main\\messages\\%s"
    );

    public static function GetSender($serie) {
        $client = new \Iris\System\Client();
        $language = $client->getLanguage(\TRUE);
        return sprintf(self::$_Constrollers[$serie], $language);
    }

    public function __call($name, $arguments) {
        echo 'No specific explanation defined...';
    }

    protected function _intentionalError() {
        return '<div class="intentionalerror">'.
                '<style> body { background-color : #FDD;} </style>'.
        $this->_('This error screen is intentional and expected').
                '</div>';
    }

    public static function AddController($name,$path){
        self::$_Constrollers[$name] = $path;
    }
}

?>
