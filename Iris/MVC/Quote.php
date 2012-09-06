<?php

namespace Iris\MVC;

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
 * A "Quote" is a view whose text is not in a standard
 * file of type .iview. It can be generated or found in a database.
 * 
 * @author Jacques THOORENS (irisphp@thoorens.net)
 * @see http://irisphp.org
 * @license GPL version 3.0 (http://www.gnu.org/licenses/gpl.html)
 * @version $Id: $ */

/**
 * A "Quote" is a view whose text is not in a standard
 * file of type .iview. It can be generated or found in a database.
 * 
 */
class Quote extends Partial {

    /**
     * Type of view
     * @var string
     */
    protected static $_ViewType = 'quoted view';

    /**
     * The text template to be returned
     * @var string
     */
    private $_textTemplate = '';

    /**
     * View error mechanism uses the script name. There is none here.
     * 
     * @var string
     */
    protected static $_LastUsedScript = 'Quoted string';

    public function __construct($text,$data) {
        $this->_transmit($data);
        $this->setTextTemplate($text);
    }

    /**
     * Modifies the texte template
     * 
     * @param string $text 
     */
    public function setTextTemplate($text) {
        $this->_textTemplate = $text;
    }

    /**
     * Get the text template
     * 
     * @param type $scriptName will be always NULL
     * @param string $scriptFileName (set to '==Generated template==')
     * @return string
     */
    protected function _getTemplate($scriptName, &$scriptFileName) {
        $scriptFileName = '==Generated template==';
        return explode("\n", $this->_textTemplate);
    }

    

}

